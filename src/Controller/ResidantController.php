<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ResidantController extends AbstractController
{
    #[Route('/residant', name: 'app_residant')]
    public function index(): Response
    {
        return $this->render('residant/index.html.twig', [
            'controller_name' => 'ResidantController',
        ]);
    }
}
