<?php

namespace App\Event;

use App\Entity\User;
use App\Traits\UserAwareTrait;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class LoginEvent.
 */
class LoginEvent extends Event
{
    use UserAwareTrait;

    const NAME = 'user.login';

    /**
     * Constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
