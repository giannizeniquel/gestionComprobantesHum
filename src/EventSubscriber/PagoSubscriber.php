<?php

namespace App\EventSubscriber;

use App\Entity\Pago;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class PagoSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var AdminUrlGenerator
     */
    private $adminUrlGenerator;
    
    public function __construct(Security $security, AdminUrlGenerator $adminUrlGenerator) //
    {
        $this->security = $security;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersistedEvent',
            AfterEntityPersistedEvent::class => 'onAfterEntityPersistedEvent',
            AfterEntityUpdatedEvent::class => 'onAfterEntityUpdatedEvent',
        ];
    }

    public function onBeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Pago) {
            $entity->setUser($this->security->getUser());
        } 
    }

    public function onAfterEntityPersistedEvent(): Response
    {
        $url =  $this->adminUrlGenerator->setController('App\\Controller\\Admin\\UserCrudController')->setAction('obtenerPagosUsuario');
        return (new RedirectResponse($url))->send(); 
    }

    public function onAfterEntityUpdatedEvent(): Response
    {
        $url =  $this->adminUrlGenerator->setController('App\\Controller\\Admin\\UserCrudController')->setAction('obtenerPagosUsuario');
        return (new RedirectResponse($url))->send();  
    }

}
