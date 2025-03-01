<?php

namespace App\Admin;

use App\Entity\User;
use App\Enum\Roles;
use App\Handler\DeleteHandler;
use App\Helper\PasswordUpdater;
use App\Traits\DomainGuesserAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\DateTime;

class UserAdmin extends Admin
{
    use DomainGuesserAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'user';
    /**
     * {@inheritdoc}
     */
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'creationTime',
    ];
    /**
     * @var PasswordUpdater
     */
    private $passwordUpdater;
    /**
     * @var DeleteHandler
     */
    private $deleteHandler;

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        /** @var User $instance */
        $instance = parent::getNewInstance();

        $instance->setRoles([Roles::USER]);

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $user = $this->getRoot()->getSubject();

        $formMapper
            ->add('email', EmailType::class, ['disabled' => !$this->isNewObject()])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'form.password',
                'required' => $this->isNewObject(),
                'disabled' => (null !== $user) ? $user->hasMailCryptSecretBox() : false,
                'help' => (null !== $user && $user->hasMailCryptSecretBox()) ? 'Disabled because user has a MailCrypt key defined' : null,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [Roles::getAll()],
                'multiple' => true,
                'expanded' => true,
                'label' => 'form.roles',
            ])
            ->add('quota', null, [
                'help' => 'Custom mailbox quota in MB',
            ])
            ->add('mailCrypt', CheckboxType::class, ['disabled' => true])
            ->add('deleted', CheckboxType::class, ['disabled' => true]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email', null, [
                'show_filter' => true,
            ])
            ->add('domain', null, [
                'show_filter' => false,
            ])
            ->add('registration_since', 'doctrine_orm_callback', [
                'callback' => function (ProxyQuery $proxyQuery, $alias, $field, $value) {
                    if (isset($value['value']) && null !== $value = $value['value']) {
                        /** @var QueryBuilder $qb */
                        $qb = $proxyQuery->getQueryBuilder();
                        $field = sprintf('%s.creationTime', $alias);
                        $qb->andWhere(sprintf('%s >= :datetime', $field))
                            ->setParameter('datetime', new DateTime($value))
                            ->orderBy($field, 'DESC');
                    }

                    return true;
                },
                'field_type' => TextType::class,
                'show_filter' => true,
            ])
            ->add('roles', null, [
                'field_options' => [
                    'required' => false,
                    'choices' => [Roles::getAll()],
                ],
                'field_type' => ChoiceType::class,
                'show_filter' => true,
            ])
            ->add('recoverySecretBox', 'doctrine_orm_callback', [
                'field_type' => BooleanType::class,
                'label' => 'Recovery Token',
                'callback' => function (ProxyQuery $proxyQuery, $alias, $field, $value) {
                    if (is_array($value) && 2 === $value['value']) {
                        $query = sprintf('%s.recoverySecretBox IS NULL', $alias);
                    } else {
                        $query = sprintf('%s.recoverySecretBox IS NOT NULL', $alias);
                    }

                    $proxyQuery->getQueryBuilder()->andWhere($query);

                    return true;
                },
            ])
            ->add('mailCrypt', 'doctrine_orm_choice', [
                'field_options' => [
                    'required' => false,
                    'choices' => [0 => 'No', 1 => 'Yes'],
                ],
                'field_type' => ChoiceType::class,
                'show_filter' => true,
            ])
            ->add('deleted', 'doctrine_orm_choice', [
                'field_options' => [
                    'required' => false,
                    'choices' => [0 => 'No', 1 => 'Yes'],
                ],
                'field_type' => ChoiceType::class,
                'show_filter' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('email')
            ->add('creationTime')
            ->add('updatedTime')
            ->add('recoverySecretBox', 'boolean', [
                'label' => 'Recovery Token',
            ])
            ->add('mailCrypt')
            ->add('deleted');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureBatchActions($actions)
    {
        if ($this->hasRoute('edit') && $this->hasAccess('edit')) {
            $actions['remove_vouchers'] = [
                'ask_confirmation' => true,
            ];
        }

        return $actions;
    }

    /**
     * @param User $user
     */
    public function prePersist($user)
    {
        $this->passwordUpdater->updatePassword($user);

        if (null === $user->getDomain() && null !== $domain = $this->domainGuesser->guess($user->getEmail())) {
            $user->setDomain($domain);
        }
    }

    /**
     * @param User $user
     */
    public function preUpdate($user)
    {
        if (!empty($user->getPlainPassword())) {
            $this->passwordUpdater->updatePassword($user);
        } else {
            $user->updateUpdatedTime();
        }
    }

    /**
     * @param User $user
     */
    public function delete($user)
    {
        $this->deleteHandler->deleteUser($user);
    }

    /**
     * @param PasswordUpdater $passwordUpdater
     */
    public function setPasswordUpdater(PasswordUpdater $passwordUpdater)
    {
        $this->passwordUpdater = $passwordUpdater;
    }

    /**
     * @param DeleteHandler $deleteHandler
     */
    public function setDeleteHandler(DeleteHandler $deleteHandler)
    {
        $this->deleteHandler = $deleteHandler;
    }
}
