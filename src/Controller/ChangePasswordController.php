<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\ChangePasswordType;

class ChangePasswordController extends AbstractDashboardController
{
    /**
     * @Route("admin/change-password", name="change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        if($user){
            if ($form->isSubmitted() && $form->isValid()) {
                $formData = $form->getData();

                // Validar la contraseña actual
                if (!$userPasswordHasher->isPasswordValid($user, $formData['currentPassword'])) {
                    $this->addFlash('warning', 'La contraseña actual es incorrecta.');
                    return $this->redirectToRoute('change_password');
                } elseif ($formData['newPassword'] !== $formData['confirmPassword']) {
                    // Verificar si las contraseñas nuevas coinciden
                    $this->addFlash('warning', 'La contraseña nueva es diferente a la confirmada.');
                    return $this->redirectToRoute('change_password');
                } else {
                    // Cambiar la contraseña del usuario
                    $newEncodedPassword = $userPasswordHasher->encodePassword($user, $formData['newPassword']);
                    $user->setPassword($newEncodedPassword);
                    $user->setIsVerified(true);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();

                    $this->addFlash('success', 'Contraseña cambiada exitosamente.');

                    return $this->redirectToRoute('app_login');
                }
            }

            return $this->render('change_password/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }else{
            return $this->redirectToRoute('app_login');
        }
    }
}
