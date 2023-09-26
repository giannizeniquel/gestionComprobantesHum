<?php

namespace App\EventSubscriber;

use App\Entity\Pago;
use App\Entity\PagoDetalle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
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
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
            AfterEntityPersistedEvent::class => 'onAfterEntityPersistedEvent',
            AfterEntityUpdatedEvent::class => 'onAfterEntityUpdatedEvent',
            AfterEntityDeletedEvent::class => 'onAfterEntityDeletedEvent',
            
        ];
    }

    public function onBeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Pago) {
            $pagoDetalles = $entity->getPagoDetalles();
            $montoTotalCuotas = 0;
            foreach ($pagoDetalles as $pagoDetalle) {
                $cuotasPagoDetalles = $pagoDetalle->getCuotas();
                foreach ($cuotasPagoDetalles as $cuota) {
                    $montoTotalCuotas = $montoTotalCuotas + $cuota->getMonto();
                }
            }

            $entity->setUser($this->security->getUser());
            $entity->setMonto($montoTotalCuotas);
        }
    }

    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Pago) {
            $pagoDetalles = $entity->getPagoDetalles();
            $montoTotalCuotas = 0;
            foreach ($pagoDetalles as $pagoDetalle) {
                $cuotasPagoDetalles = $pagoDetalle->getCuotas();
                foreach ($cuotasPagoDetalles as $cuota) {
                    $montoTotalCuotas = $montoTotalCuotas + $cuota->getMonto();
                }
            }
            $entity->setUser($this->security->getUser());
            $entity->setMonto($montoTotalCuotas);
        }
    }

    public function onAfterEntityPersistedEvent(AfterEntityPersistedEvent $event): Response
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Pago) {
            $url =  $this->adminUrlGenerator->setController('App\\Controller\\Admin\\UserCrudController')->setAction('obtenerPagosUsuario');
            return (new RedirectResponse($url))->send();
        } else {
            $url =  $this->adminUrlGenerator->setController('App\\Controller\\Admin\\DashboardController')->setAction('index');
            return (new RedirectResponse($url))->send();
        }
    }

    public function onAfterEntityUpdatedEvent(AfterEntityUpdatedEvent $event): Response
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Pago) {
            $url =  $this->adminUrlGenerator->setController('App\\Controller\\Admin\\PagoCrudController')->setAction('detail');
            return (new RedirectResponse($url))->send();
        } else {
            $url =  $this->adminUrlGenerator->setController('App\\Controller\\Admin\\DashboardController')->setAction('index');
            return (new RedirectResponse($url))->send();
        }
    }

    public function onAfterEntityDeletedEvent(AfterEntityDeletedEvent $event): Response
    {
        $entity = $event->getEntityInstance();
        if (($entity instanceof Pago)) {
            $url =  $this->adminUrlGenerator->setController('App\\Controller\\Admin\\DashboardController')->setAction('index');
            return (new RedirectResponse($url))->send();
        }
    }
}
