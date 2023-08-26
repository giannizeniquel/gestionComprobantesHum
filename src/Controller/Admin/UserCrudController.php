<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use LDAP\Result;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Repository\CursoRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Usuario')
            ->setEntityLabelInPlural('Usuarios')

            // in addition to a string, the argument of the singular and plural label methods
            // can be a closure that defines two nullable arguments: entityInstance (which will
            // be null in 'index' and 'new' pages) and the current page name
            // ->setEntityLabelInSingular(
            //     fn (?Curso $curso) => $curso ? $curso->toString() : 'Curso'
            // )
            // ->setEntityLabelInPlural(function (?Category $category, ?string $pageName) {
            //     return 'edit' === $pageName ? $category->getLabel() : 'Categories';
            // })

            // the Symfony Security permission needed to manage the entity
            // (none by default, so you can manage all instances of the entity)
            //->setEntityPermission('ROLE_EDITOR')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('apellido'),
            TextField::new('nombre'),
            TextField::new('dni'),
            TextField::new('email'),
            //TextField::new('password', 'Contraseña')->hideOnForm(),
            TextField::new('telefono','Teléfono'),
            TextField::new('domicilio'),
            ArrayField::new('roles')->setPermission('ROLE_ADMIN'),
            AssociationField::new('cursos', 'Cursos inscriptos')->setPermission('ROLE_ADMIN'),
        ];
        
        // if (Crud::PAGE_INDEX === $pageName)
        // {
        //      yield IdField::new('id')->hideOnForm();
        //      yield TextField::new('nombre');
        //      yield TextField::new('apellido');
        // }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->setPermission(Action::NEW, 'ROLE_ADMIN')
        ->setPermission(Action::DELETE, 'ROLE_ADMIN')
        ->setPermission(Action::INDEX, 'ROLE_ADMIN');
    }
    
    /**
     * @Route("/admin/misCursos", name="misCursos")
     */
    public function obtenerCursosUsuario(UserRepository $userRepository): Response
    {
        $userId = $this->getUser()->getId();
        $cursos = $userRepository
            ->findByMisCursos($userId);
        
            return $this->render('user/userCursos.html.twig', ['cursos' => $cursos]);
    }




/**
 * @Route("/upload-excel", name="xlsx", methods={"POST"})
 * @param Request 
 * @param UserRepository 
 * @param CursoRepository 
 * @throws \Exception
 */
public function xslx(Request $request, UserRepository $userRepository, CursoRepository $cursoRepository)
{
    $file = $request->files->get('file');

    if (!$file) {
        return $this->json('Archivo no encontrado en la solicitud', 400);
    }

    $fileFolder = __DIR__ . '/../../public/uploads/';
    $filePathName = md5(uniqid()) . $file->getClientOriginalName();

    try {
        $file->move($fileFolder, $filePathName);
    } catch (FileException $e) {
        return $this->json('Error al mover el archivo', 500);
    }

    $spreadsheet = IOFactory::load($fileFolder . $filePathName);
    $row = $spreadsheet->getActiveSheet()->removeRow(1);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $entityManager = $this->getDoctrine()->getManager();

    foreach ($sheetData as $row) {
        $nombre = $row['A'];
        $apellido = $row['B'];
        $email = $row['C'];
        $dni= $row['F'];
        $password = $row['E'];
        $cursoId = $row['F'];



        $userExists = $userRepository->findOneBy(['email' => $email]);

        if (!$userExists) {
            $user = new User();
            $user->setNombre($nombre);
            $user->setApellido($apellido);
            $user->setEmail($email);
            $user->setPassword('$2y$13$tB5VfB66JCSzioZaUXKWx.nxDdXQ5knrABCR0P4IsdKOM6FfqE6.C');
            $user->setDni($dni);
            $entityManager->persist($user);

            
            $curso = $cursoRepository->find($cursoId); // Obtener el curso por ID
            if ($curso) {
                $user->addCurso($curso); // Establecer la relación entre usuario y curso
            }
        }
    }

    $entityManager->flush();

    return $this->json('Usuarios registrados y cursos asignados', 200);
 }



 

}