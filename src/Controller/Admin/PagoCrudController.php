<?php

namespace App\Controller\Admin;

use App\Entity\Cuota;
use App\Entity\Pago;
use App\Entity\User;
use App\Form\PagoDetalleType;
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
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;

class PagoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pago::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('curso')
            ->setFormTypeOptions([
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('curso')
                        ->join('curso.users', 'u')
                        ->where('u.id = :userId')
                        ->setParameter('userId', $this->getUser()->getId());
                },
                'by_reference' => false,
            ]);
        yield IdField::new('id')->hideOnForm()
            ->hideOnForm();
        yield AssociationField::new('user')
            ->autocomplete()
            ->hideOnForm();
        yield NumberField::new('monto', 'Monto a abonado');
        yield TextField::new('observacion', 'Observaciones');
        yield CollectionField::new('pagoDetalles', 'Detalle')
            ->allowDelete()
            ->setEntryIsComplex(true)
            ->setEntryType(PagoDetalleType::class)
            ->setFormTypeOptions([
                'by_reference' => false,
            ]);
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
        $cuotas = $cuotaRepository->findByCuotasDeCurso($idCurso);
        $cuotasData = [];
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
        
        return $this->json($cuotasData);
    }
}
