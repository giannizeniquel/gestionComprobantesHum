<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
  /**
   * @Route("/login", name="app_login")
   */
  public function index(AuthenticationUtils $authenticationUtils): Response
  {

    if ($this->getUser()) {
      return $this->redirectToRoute('admin');
    }
    
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('@EasyAdmin/page/login.html.twig', [
      'last_username' => $lastUsername,
      'error'         => $error,
      'page_title' => 'Iniciar Sesión',
      'csrf_token_intention' => 'authenticate',
      'target_path' => 'admin',
      'username_label' => 'Email',
      'password_label' => 'Contraseña',
      'sign_in_label' => 'Ingresar',

    ]);
  }

  /**
   * @Route("/logout", name="app_logout")
   */
  public function logout(): void
  {
    throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }
}