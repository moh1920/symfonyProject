<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonnelleController extends AbstractController
{
    #[Route('/personnelle', name: 'app_personnelle')]
    public function index(): Response
    {
        return $this->render('personnelle/index.html.twig', [
            'controller_name' => 'PersonnelleController',
        ]);
    }
}
