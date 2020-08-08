<?php
namespace App\EventListener\User;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserHashPassword
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function preUpdate(User $user, LifecycleEventArgs $event)
    {
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );
    }

    public function prePersist($user, LifecycleEventArgs $event)
    {
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );
    }
}