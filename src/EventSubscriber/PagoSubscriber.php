<?php

namespace App\EventSubscriber;

use App\Entity\Pago;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class PagoSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function onBeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Pago) {
            $entity->setUser($this->security->getUser());
        } 
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersistedEvent',
        ];
    }
}
