<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignUpController extends AbstractController
{
    #[Route('/signUp', name: 'app_sign_up')]
    public function index(): Response
    {
        return $this->render('sign-up.html.twig');

    }
}
