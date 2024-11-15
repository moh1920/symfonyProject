<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GestionnaireDeFoyerController extends AbstractController
{
    #[Route('/gestionnaire/de/foyer', name: 'app_gestionnaire_de_foyer')]
    public function index(): Response
    {
        return $this->render('gestionnaire_de_foyer/index.html.twig', [
            'controller_name' => 'GestionnaireDeFoyerController',
        ]);
    }
}
