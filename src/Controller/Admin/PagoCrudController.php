<?php

namespace App\Controller\Admin;

use App\Entity\Pago;
use App\Form\PagoDetalleType;
use App\Form\BuscarFechaType;
use App\Repository\CuotaRepository;
use App\Repository\PagoRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        if (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
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
                    'by_reference' => true,
                ])
                ->renderAsNativeWidget();
        } else {
            yield AssociationField::new('curso');
        }
        yield IdField::new('id')->hideOnDetail()
            ->hideOnForm();
        yield AssociationField::new('user', 'Creador')
            ->autocomplete()
            ->hideOnForm();
        yield NumberField::new('monto', 'Monto Total')
            ->hideOnForm()
            ->setHelp('Se calcula de la suma de todas las cuotas');
        yield TextField::new('observacion', 'Observaciones');
        yield CollectionField::new('pagoDetalles', 'Detalle')
            ->hideOnDetail()
            ->setEntryIsComplex(true)
            ->setEntryType(PagoDetalleType::class)
            ->setFormTypeOptions([
                'by_reference' => false,
            ]);
        if (Crud::PAGE_DETAIL === $pageName) {
            yield FormField::addPanel('Detalles del Pago');
            yield CollectionField::new('getPagoMasDetallesObj', '')
                ->setTemplatePath('admin/actions/my_custom_action.html.twig');
        }
    }

    public function obtenerIdCurso(AdminContext $context, Request $request, PagoRepository $pagoRepository)
    {
        $idCurso = $context->getRequest()->get('idCurso');
        $idPago = $context->getRequest()->get('idPago');
        $accion = $context->getRequest()->get('accionEditar');
        $session = $request->getSession();
        $session->set('idCurso', $idCurso);

        if ($accion == 'editar') {
            $url = $this->adminUrlGenerator
                ->setController(PagoCrudController::class)
                ->setAction('edit')
                ->setEntityId($idPago)
                ->generateUrl();
        } else {
            $url = $this->adminUrlGenerator
                ->setController(PagoCrudController::class)
                ->setAction('new')
                ->generateUrl();
        }

        return $this->redirect($url);
    }

    public function configureActions(Actions $actions): Actions
    {
        $user = $this->getUser();

        if($user){
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $actions
                    ->add(Crud::PAGE_INDEX, Action::DETAIL)
                    ->setPermission(Action::INDEX, 'ROLE_ADMIN')
                    ->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN');
            } else {
                return $actions
                    ->add(Crud::PAGE_INDEX, Action::DETAIL)
                    ->setPermission(Action::INDEX, 'ROLE_ADMIN');
            }
        }else{
            return $this->redirectToRoute('app_login');
        }
    }


    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('user')
            ->add('curso');
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
                                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css"/>
                                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/pago.js');
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

        foreach ($cuotasPagadas as $cuotaP) {
            $idCuotaP = $cuotaP->getId();
            $cuotasPagadasData[] = [
                'idCuota' => $idCuotaP,
            ];
        }

        foreach ($cuotas as $cuota) {
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
     * @Route("/lista-pago.{_format}", name="lista_pago", defaults={"_format"="html"}, requirements={"_format"="html|xlsx"})
     * @throws \Exception
     */
    public function indexAllPagos(Request $request, PagoRepository $pagoRepository): Response
    { 
        $buscarFiltroForm = $this->createForm(BuscarFechaType::class, null, [
            'action' => $this->generateUrl('lista_pago'),
        ]);
        $buscarFiltroForm->handleRequest($request);

        if ($buscarFiltroForm->isSubmitted() && $buscarFiltroForm->isValid()) {
            $filtro = $buscarFiltroForm->getData();

                if ($filtro !== null) {
                $pagos = $pagoRepository
                    ->findAllPagosPorDniFecha($filtro['dni'], 
                                            $filtro['startDate'], 
                                            $filtro['endDate']);      
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

        if ($request->getRequestFormat() == 'xlsx') {
            $datosExcel = array(
                'encabezado' => array(
                    'titulo' => 'REPORTE DE PAGOS',
                    'filtro' => array(
                        'DNI' => ($filtro !== null) ? $filtro['dni'] : 'N/A',
                        'Fecha Desde' => ($filtro !== null && $filtro['startDate'] !== null) ? $filtro['startDate']->format('d-M-Y') : 'N/A',
                        'Fecha Hasta' => ($filtro !== null && $filtro['endDate'] !== null) ? $filtro['endDate']->format('d-M-Y') : 'N/A',
                    ),
                ),
                'columnas' => array(
                    'ID',
                    'Pago',
                    'Estudiante',
                    'Curso',
                    'Monto Total',
                    'Monto Cuota',
                    'Números Ticket',
                    'Fecha',
                    'CuotaDescripcion',
                    'Observaciones',
                ),
                'pagos' => $pagos, 
            );

            $response = $this->renderExcel($datosExcel);
            $response->headers->set('Content-Disposition', 'attachment; filename="reporte_pagos.xlsx"');

            return $response;
        } else {
            return $this->render('reportes/reporte.html.twig', [
                'pagos' => $pagos,
                'buscar' => $buscarFiltroForm->createView(),
            ]);
        }
    }

    private function renderExcel($pagos)
    {
        $spreadsheet = new Spreadsheet();

        // Establecer propiedades
        $this->ponerPropiedades($spreadsheet, 'Gestión Pagos Estudiantes');

        // Genera el contenido
        $this->generarEncabezadoExcel($spreadsheet, $pagos['encabezado']);
        $this->generarEncabezadoColumnas($spreadsheet, $pagos['columnas']);
        $this->generarDatos($spreadsheet, $pagos['pagos']);

        $response = $this->generarExcelResponse($spreadsheet);

        return $response;
    }


    private function ponerPropiedades($spreadsheet, $titulo)
    {
        $spreadsheet->getProperties()
            ->setCustomProperty('Humanidades', 'Ges  Pagos')
            ->setLastModifiedBy('Sistema')
            ->setTitle($titulo)
            ->setSubject('')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');
    }

    private function generarEncabezadoColumnas($spreadsheet, $encabezadoColumnas, $fila = 3)
    {
        $columna = 0;
        foreach ($encabezadoColumnas as $titulo) {
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columna++, $fila, $titulo);
        }
        $rango = 'A'.$fila.':'.chr(65 + --$columna).$fila;

        $spreadsheet->getActiveSheet()->getStyle($rango)->applyFromArray($this->estiloTitulo());
        $spreadsheet->getActiveSheet()->getStyle($rango)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '006D6D'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    private function estiloTitulo()
    {
        $styleArray = [
            'font' => [
                'name' => 'Verdana',
             //   'bold' => true,
                'italic' => false,
                'strike' => false,
            //   'size' => 16,
            // 'color' => ['rgb' => 'FFFFFF'],
            ],
        ];

        return $styleArray;
    }

    private function generarEncabezadoExcel($spreadsheet, $encabezado)
    {
        // Establece el título del encabezado en la celda A1
        $spreadsheet->getActiveSheet()->setCellValue('A1', $encabezado['titulo']);
        
        // Inicializa la columna para los filtros
        $columna = 0;
        
        // Itera sobre los filtros y valores
        foreach ($encabezado['filtro'] as $filtro => $valor) {
            // Combina el filtro y el valor, en la fila 3, empezando en la columna actual
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columna, 3, $filtro . ': ' . $valor);
            
            // Aumenta la columna para el próximo filtro
            $columna++;
        }
    }

    private function generarDatos($spreadsheet, $pagos)
    {
        //dd($pagos);
        $fila = 4;
        foreach ($pagos as $pago) {
        
            foreach ($pago->getPagoDetalles() as $detalle) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, $pago->getId());
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $pago->getUser()->getDni());
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $pago->getCurso()->getNombre());
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $pago->getMonto());
                
                // Llena las celdas con los datos del detalle de pago actual
                $montoDetalle = $detalle->getMontoTicket();
                $numeroTicket = $detalle->getNumeroTicket();
                $fechaTicket = $detalle->getFechaTicket();
                $observaciones = $detalle->getObservacion();

                // Llena las celdas con los datos de Detalle de Pago
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $montoDetalle);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $numeroTicket);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $fechaTicket);
                foreach ($detalle->getCuotas() as $cuota) {
                    $descripcion = $cuota->getNumeroCuota();
                       $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $descripcion);
                  }
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $observaciones);

                ++$fila;
            }
        }
    }

    private function generarExcelResponse($spreadsheet)
    {
        // Crea un archivo temporal
        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel');

        // Guarda el contenido en el archivo temporal
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);
        $response = new BinaryFileResponse($tempFilePath);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="nombre_del_archivo.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
}
