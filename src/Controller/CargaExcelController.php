<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CursoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Form\CargaExcelType;

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

            $batchSize = 50; // Cantidad de registros por lote
            $entityManager = $this->getDoctrine()->getManager();
            $batchCount = 0;


            foreach ($sheetData as $row) {
                $email = $row['A'];
                $apellido = $row['B'];
                $nombre = $row['C'];
                $dni = $row['D'];
                $password = $row['E'];
                $cursoId = $row['F'];

                $userExists = $userRepository->findOneBy(['email' => $email]);

                if (!$userExists) {
                    $user = new User();
                    $user->setEmail($email);
                    $user->setApellido($apellido);
                    $user->setNombre($nombre);
                    $user->setDni($dni);
                    // $hashedPassword = $passwordHasher->hashPassword($user, $password);
                    $user->setPassword('$2y$13$fGb8QrKMwIz6Vwn4ok/GG.Vlb93gHz/2ZXZthrIw.NVlUe0zBmQtK');  // Establece la contraseña codificada
                    $curso = $cursoRepository->find($cursoId); // Obtener el curso por ID

                    if ($curso) {
                        $user->addCurso($curso); // Establece la relación entre usuario y curso
                    }
                    $entityManager->persist($user);

                    ++$batchCount;
                    if ($batchCount % $batchSize === 0) {
                        $entityManager->flush();
                        $entityManager->clear();
                    }
                }
            }

            // Asegurarse de que los registros finales se persistan
            if ($batchCount % $batchSize !== 0) {
                $entityManager->flush();
                $entityManager->clear();
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
