<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GestionnaireDeRestaurantController extends AbstractController
{
    #[Route('/gestionnaire/de/restaurant', name: 'app_gestionnaire_de_restaurant')]
    public function index(): Response
    {
        return $this->render('gestionnaire_de_restaurant/index.html.twig', [
            'controller_name' => 'GestionnaireDeRestaurantController',
        ]);
    }
}
