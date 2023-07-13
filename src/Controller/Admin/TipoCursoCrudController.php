<?php

namespace App\Controller\Admin;

use App\Entity\TipoCurso;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TipoCursoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TipoCurso::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
