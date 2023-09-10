<?php

namespace App\Controller\Admin;

use App\Entity\Cuota;
use App\Entity\Pago;
use App\Entity\User;
use App\Form\PagoDetalleType;
use App\Form\BuscarFechaType;
use App\Repository\CuotaRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ComparisonFilter;
use App\Repository\UserRepository;
use App\Repository\PagoRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;



class PagoCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    public static function getEntityFqcn(): string
    {
        return Pago::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_NEW === $pageName)
        {
            yield AssociationField::new('curso')
            ->setFormTypeOptions([
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('curso')
                        ->join('curso.users', 'u')
                        ->where('u.id = :userId')
                        ->andWhere('curso.id = :idCurso')
                        ->setParameter('userId', $this->getUser()->getId())
                        ->setParameter('idCurso', $this->get('session')->get('idCurso'));
                },
                'by_reference' => false,
            ])
            ->renderAsNativeWidget();
        }else if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_EDIT === $pageName || Crud::PAGE_DETAIL === $pageName) {
            yield AssociationField::new('curso');
        }

        if (Crud::PAGE_DETAIL === $pageName) {
            yield CollectionField::new('getPagoMasDetallesObj', '')
            ->setTemplatePath('admin/actions/my_custom_action.html.twig');
        }
        
        yield IdField::new('id')->hideOnDetail()
            ->hideOnForm();
        yield AssociationField::new('user','Creador')
            ->autocomplete()
            ->hideOnForm();
        yield NumberField::new('monto', 'Monto total') ;//->hideOnDetail();
        yield TextField::new('observacion', 'Observaciones');
        yield CollectionField::new('pagoDetalles', 'Detalle') ->hideOnDetail()
            ->allowDelete()
            ->setEntryIsComplex(true)
            ->setEntryType(PagoDetalleType::class)
            ->setFormTypeOptions([
                'by_reference' => false,
            ]);
            
    }


    public function obtenerIdCurso(AdminContext $context, Request $request)
    {
        $idCurso = $context->getRequest()->get('idCurso');
        $session = $request->getSession();
        $session->set('idCurso', $idCurso);

        $url = $this->adminUrlGenerator
        ->setController(PagoCrudController::class)
        ->setAction('new')
        ->generateUrl();

        return $this->redirect($url);

    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->setPermission(Action::INDEX, 'ROLE_ADMIN');
        
    }


    public function configureFilters(Filters $filters): Filters
    {
        return $filters
        ->add('user')
        ;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
                                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/pago.js')

        ;
    }

    /**
     * @Route("obtenerCuotasDeCurso", name="obtenerCuotasDeCurso",  methods={"POST"})
     * 
     */
    public function obtenerCuotasDeCurso(Request $request, CuotaRepository $cuotaRepository): JsonResponse
    {
        $idCurso = $request->request->get('idCurso');
        $cuotas = $cuotaRepository->findByCuotasDeCursoAjax($idCurso);
        $cuotasPagadas = $cuotaRepository->findByCuotasPagadasDeCursoAjax($idCurso);
        $cuotasPagadasData = [];
        $cuotasData = [];
        $totalCuotas = [];

        foreach ($cuotasPagadas as $cuotaP){
            $idCuotaP = $cuotaP->getId();
            $cuotasPagadasData[] = [
                'idCuota' => $idCuotaP,
            ];
        }

        foreach ($cuotas as $cuota){
            // Acceder a las propiedades de la cuota
            $idCuota = $cuota->getId();
            $monto = $cuota->getMonto();
            $descripcion = $cuota->getDescripcion();
            $numeroCuota = $cuota->getNumeroCuota();
            $toString = $cuota->__toString();
            // ... y otras propiedades
            
            // Realizar acciones con los datos de la cuota
            // Por ejemplo, puedes agregarlos a un array para usarlos después
            $cuotasData[] = [
                'idCuota' => $idCuota,
                'monto' => $monto,
                'descripcion' => $descripcion,
                'numeroCuota' => $numeroCuota,
                'toString' => $toString,
            ];
        }

        $totalCuotas[] = [
            'cuotasData' => $cuotasData,
            'cuotasPagadasData' => $cuotasPagadasData
        ];
        
        return $this->json($totalCuotas);
    }


    /**
     * @Route("/lista-pago", name="lista_pago")
     * @throws \Exception
     */
    public function indexAllPagos(Request $request, PagoRepository $pagoRepository, $filtro = null): Response
   { 
      
    $buscarFiltroForm = $this->createForm(BuscarFechaType::class, null, [
        'action' => $this->generateUrl('lista_pago'),
    ]);
    $buscarFiltroForm->handleRequest($request);

    // Verificar si el formulario fue enviado y es válido antes de obtener los datos del filtro
    if ($buscarFiltroForm->isSubmitted() && $buscarFiltroForm->isValid()) {
        $filtro = $buscarFiltroForm->getData();
        
         // Asegurarse de que $filtro no sea nulo
        if ($filtro !== null) {
            $pagos = $pagoRepository->findAllPagosPorDniFecha($filtro['dni'], $filtro['startDate'], $filtro['endDate']);      
        } else {
            // Si $filtro es nulo, puedes manejarlo de acuerdo a tus necesidades.
            // Por ejemplo, puedes establecer $datos en un valor por defecto.
            $pagos = []; // O cualquier otro valor por defecto que desees
        }
    } else {
        // Si el formulario no fue enviado o no es válido, también puedes manejarlo según tus necesidades.
        $pagos = $pagoRepository->findAllPagos();
        // O cualquier otro valor por defecto que desees
    }

    return $this->render('reportes/reporte.html.twig', [
        'pagos' => $pagos,
       
        'buscar' => $buscarFiltroForm->createView(),
    ]);
}
}
