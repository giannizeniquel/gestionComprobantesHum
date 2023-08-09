<?php

namespace App\Controller\Admin;

use App\Entity\Pago;
use App\Entity\User;
use App\Form\PagoDetalleType;
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
                        ->setParameter('userId', $this->getUser());
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
}
