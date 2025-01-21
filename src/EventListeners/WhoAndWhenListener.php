<?php

namespace App\EventListeners;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class WhoAndWhenListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (method_exists($entity, 'setCreatedAt') && method_exists($entity, 'setUpdatedAt')) {
            $now = new \DateTimeImmutable();
            $entity->setCreatedAt($now);
            $entity->setUpdatedAt(new \DateTime());

            // Récupère l'utilisateur connecté pour "createdBy"
            $user = $this->security->getUser();
            if (method_exists($entity, 'setCreatedBy') && $user instanceof \App\Entity\User) {
                $entity->setCreatedBy($user);
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTime());
        }

        // Récupère l'utilisateur connecté pour "updatedBy"
        $user = $this->security->getUser();
        if (method_exists($entity, 'setUpdatedBy') && $user instanceof \App\Entity\User) {
            $entity->setUpdatedBy($user);
        }
    }
}