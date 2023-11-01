<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Curso;
use App\Entity\Pago;
use App\Entity\User;
use App\Entity\Carrera;
use App\Entity\Reclamo;
use App\Entity\TipoCurso;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        //$adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        //return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        //you can also redirect to different pages depending on the current user
        // if (in_array('ROLE_ADMIN',$this->getUser()->getRoles())) {
        //    return $this->redirect('...');
        // }

        // you can also render some template to display a proper Dashboard
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
         
        if ($this->getUser() && $this->getUser()->isVerified()==false) {
           return $this->redirectToRoute('change_password');
        } else {
            return $this->render('home/home.html.twig');
        }
        //return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion de Comprobantes')

            //->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>')

            // the path defined in this method is passed to the Twig asset() function
            ->setFaviconPath('unne.png')

            // the domain used by default is 'messages'
            ->setTranslationDomain('admin')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr')

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()


            //->renderSidebarMinimized()

            // by default, all backend URLs include a signature hash. If a user changes any
            // query parameter (to "hack" the backend) the signature won't match and EasyAdmin
            // triggers an error. If this causes any issue in your backend, call this method
            // to disable this feature and remove all URL signature checks
            ->disableUrlSignatures()

            // by default, all backend URLs are generated as absolute URLs. If you
            // need to generate relative URLs instead, call this method
            ->generateRelativeUrls();
    }



    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $role = '';

        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            $role = 'Dev';
        } else if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $role = 'Adm';
        }

        if($role != ''){
            return parent::configureUserMenu($user)
            ->setName($user->getFullname() . ' (' . $role . ')');
        } else {
            return parent::configureUserMenu($user)
            ->setName($user->getFullname());
        }
        
    }


    public function configureMenuItems(): iterable
    {
        $user = $this->getUser();
        
        if($user){
            yield MenuItem::linkToDashboard('Inicio', 'fa fa-home');
            if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
                yield MenuItem::linkToCrud('Usuarios', 'fa fa-users', User::class);
                yield MenuItem::linktoRoute('Cargar usuarios', 'fas fa-upload', 'xlsx');

                yield MenuItem::linkToCrud('Cursos', 'fa fa-chalkboard', Curso::class);
                yield MenuItem::linkToCrud('Propuestas', 'fa fa-tag', Carrera::class);
                yield MenuItem::linkToCrud('Ofertas', 'fa fa-tags', TipoCurso::class);

                // yield MenuItem::linkToCrud('Cuotas', 'fa fa-shapes', Cuota::class);
                yield MenuItem::linkToCrud('Pagos', 'fa fa-file-text-o', Pago::class);

                yield MenuItem::section('');
                yield MenuItem::linktoRoute('Agregar un usuario', 'fa fa-user', 'app_register');

                yield MenuItem::section('Reportes');
                yield MenuItem::linktoRoute('Reporte general', 'fas fa-file-excel', 'lista_pago');

                // yield MenuItem::linkToCrud('Pagos Detalles', 'fa fa-shapes', PagoDetalle::class);
                // yield MenuItem::section('Mensajes');
                // yield MenuItem::linkToCrud('Reclamos', 'fas fa-ticket-alt', Reclamo::class);
                // yield MenuItem::linktoRoute('Mensajes', 'fa fa-commenting-o', 'app_websocket');

                yield MenuItem::section('Seguridad');

                yield MenuItem::linktoRoute('Cambiar contraseña', 'fas fa-key', 'change_password');

                yield MenuItem::section('Links');
                yield MenuItem::linkToUrl('Guia de uso', 'fab fa-youtube', 'https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html')->setLinkTarget('_blank');
                yield MenuItem::linkToUrl('Humanidades', 'fas fa-university', 'https://hum.unne.edu.ar/')->setLinkTarget('_blank');
            } else {
                yield MenuItem::section('Menu usuario');
                yield MenuItem::linkToCrud('Mi Perfil', 'fa fa-user', User::class)
                    ->setAction('detail')
                    ->setEntityId($user->getId());
                yield MenuItem::linktoRoute('Mis Cursos', 'fa fa-chalkboard', 'misCursos');
                yield MenuItem::linktoRoute('Mis Pagos', 'fa fa-file-text-o', 'misPagos');
                // yield MenuItem::linktoRoute('Mis Reclamos', 'fas fa-ticket-alt', 'misReclamos');

                // yield MenuItem::section('Mensajes');
                // yield MenuItem::linktoRoute('Mensajes', 'fa fa-commenting-o', 'app_websocket');

                yield MenuItem::section('Seguridad');
                yield MenuItem::linktoRoute('Cambiar contraseña', 'fas fa-key', 'change_password');

                yield MenuItem::section('Recursos');
                yield MenuItem::linkToUrl('Guia de uso', 'fab fa-youtube', 'https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html')->setLinkTarget('_blank');
                yield MenuItem::linkToUrl('Humanidades', 'fas fa-university', 'https://hum.unne.edu.ar/')->setLinkTarget('_blank');
            }
        } else {
            yield MenuItem::linkToUrl('Login', 'fas fa-sign-in-alt', 'https://www.gespagoshum.wiz.com.ar/login');
            return $this->redirectToRoute('app_login');
        }
    }
}
