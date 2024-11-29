<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexMenuController extends AbstractController
{
    #[Route('/index/menu', name: 'app_index_menu')]
    public function index(): Response
    {
        return $this->render('index_menu/index.html.twig', [
            'controller_name' => 'IndexMenuController',
        ]);
    }
}
