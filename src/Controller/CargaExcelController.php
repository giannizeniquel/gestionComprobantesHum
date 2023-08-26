<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Curso;
use App\Repository\UserRepository;
use App\Repository\CursoRepository;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Form\CargaExcelType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;



class CargaExcelController extends AbstractDashboardController
{
   
    /**
     * @Route("/upload-excel", name="xlsx", methods={"GET", "POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param CursoRepository $cursoRepository
     * @throws \Exception
     */
    public function xslx(Request $request, UserRepository $userRepository, CursoRepository $cursoRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $form = $this->createForm(CargaExcelType::class);
        $form->handleRequest($request);
      

        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener el archivo subido
            $file = $form['file']->getData();
           // dd($file);
            $fileFolder = __DIR__ . '/../../public/uploads/';
            $filePathName = md5(uniqid()) . $file->getClientOriginalName();

            try {
                $file->move($fileFolder, $filePathName);
            } catch (FileException $e) {
                return $this->json('Error al mover el archivo', 500);
            }
             //Para que no almacene el excel en servidor se puede hacer con esto, pero consume 
             //recursos de procesamiento y memoria método getPathname() de la clase UploadedFile 
             //para obtener la ruta del archivo cargado en memoria. 
             //$spreadsheet = IOFactory::load($file->getPathname());
                      
            $spreadsheet = IOFactory::load($fileFolder . $filePathName);
            $row = $spreadsheet->getActiveSheet()->removeRow(1);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $entityManager = $this->getDoctrine()->getManager();

            foreach ($sheetData as $row) {
                $nombre = $row['A'];
                $apellido = $row['B'];
                $email = $row['C'];
                $password = $row['D'];
                $dni= $row['E'];
                $cursoId = $row['F'];

          $userExists = $userRepository->findOneBy(['email' => $email]);

            if (!$userExists) {
                $user = new User();
                $user->setNombre($nombre);
                $user->setApellido($apellido);
                $user->setEmail($email);
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);  // Establece la contraseña codificada
                $user->setDni($dni);
                $entityManager->persist($user);
                $curso = $cursoRepository->find($cursoId); // Obtener el curso por ID
                if ($curso) {
                    $user->addCurso($curso); // Establece la relación entre usuario y curso
                }
            }
        }

        $entityManager->flush();
        $this->addFlash('success', 'Usuarios registrados y cursos asignados');
        return $this->redirectToRoute('admin');

        //return $this->json('Usuarios registrados y cursos asignados', 200);
    }

    return $this->render('upload/upload.html.twig', [
        'form' => $form->createView(),
    ]);
    }

}
