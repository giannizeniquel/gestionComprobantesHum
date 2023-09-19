<?php


namespace App\Controller;

use App\Repository\PagoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\BuscarFechaType;

class ReporteController extends AbstractController
{
    /**
     * @Route("/reporte-pago", name="reporte_pago", methods={"GET"})
     * @throws \Exception
     */
    public function indexAllPagos(Request $request, PagoRepository $pagoRepository,$filtro=null): Response
    {
        
         //dd($filtro);
         if ($filtro) {
            $dni = $filtro['dni'];
            $startDate = $filtro['startDate'];
            $endDate = $filtro['endDate'];
            $pagos = $pagoRepository->findAllPagosPorDniFecha($dni, $startDate, $endDate);
        } else {
            $pagos = $pagoRepository->findAllPagos();
        }

            $buscarFiltroForm = $this->createForm(BuscarFechaType::class, null, [
                'action' => $this->generateUrl('pagos_buscar'),
        ]);

            return $this->render('reportes/reporte.html.twig', [
                'pagos' => $pagos,
                'buscar' => $buscarFiltroForm->createView(),
            ]);
    
    }

        /**
         * @Route("/buscar", name="pagos_buscar")
         */
        public function buscarAction(Request $request)
        {
            // Obtén los valores del formulario
            $filtro = [
                'dni' => $request->query->get('buscar_fecha')['dni'],
                'startDate' => $request->query->get('buscar_fecha')['startDate'],
                'endDate' => $request->query->get('buscar_fecha')['endDate'],
            ];
            
            // Pasa los valores a la acción correspondiente
            return $this->forward('App\Controller\ReporteController::indexAllPagos', [
                'request' => $request,
                'filtro' => $filtro,
            ]);
        }

}