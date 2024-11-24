<?php

namespace App\Controller;

use App\Entity\Repat;
use App\Repository\RepatRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class PageAdminRepasController extends AbstractController
{
    
    #[Route('/page/admin/repas', name: 'app_page_admin_repas')]
    public function getAllRaps(Request $request,EntityManagerInterface $entityManager): Response
    {
        {
            // Récupération du terme de recherche depuis la requête
            $searchTerm = $request->query->get('search', '');
    
            // Création de la requête avec QueryBuilder
            $queryBuilder = $entityManager->getRepository(Repat::class)->createQueryBuilder('m');
    
            // Si un terme de recherche est fourni, ajouter une condition
            if (!empty($searchTerm)) {
                $queryBuilder->where('m.nomRepat LIKE :searchTerm')
                             ->setParameter('searchTerm', '%' . $searchTerm . '%');
            }
    
            // Exécuter la requête pour obtenir les résultats
            $repas = $queryBuilder->getQuery()->getResult();
    
            // Retourner la vue avec les menus
            return $this->render('page_admin_menu/tablesRepas.html.twig', [
                'repas' => $repas,
                'searchTerm' => $searchTerm,
            ]);
    }



    }
}
