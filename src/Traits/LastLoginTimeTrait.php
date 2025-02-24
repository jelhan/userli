<?php

namespace App\Traits;

trait LastLoginTimeTrait
{
    /**
     * @var \DateTime|null
     */
    private $lastLoginTime;

    /**
     * @return \DateTime|null
     */
    public function getLastLoginTime()
    {
        return $this->lastLoginTime;
    }

    /**
     * @param \DateTime $LastLoginTime
     */
    public function setLastLoginTime(\DateTime $LastLoginTime)
    {
        $this->lastLoginTime = $LastLoginTime;
    }

    public function updateLastLoginTime()
    {
        $this->setLastLoginTime(new \DateTime());
    }
}
