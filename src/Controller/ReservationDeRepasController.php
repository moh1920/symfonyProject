<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationDeRepasController extends AbstractController
{
    #[Route('/reservation/de/repas', name: 'app_reservation_de_repas')]
    public function index(): Response
    {
        return $this->render('reservation_de_repas/index.html.twig', [
            'controller_name' => 'ReservationDeRepasController',
        ]);
    }
}
