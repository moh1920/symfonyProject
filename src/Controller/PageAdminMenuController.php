<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\RepatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;


class PageAdminMenuController extends AbstractController
{
    #[Route('/admin/menus', name: 'app_page_admin_menu')]
    public function index(Request $request, EntityManagerInterface $entityManager , RepatRepository $repatRepository): Response
    {
        // Récupération du terme de recherche depuis la requête
        $searchTerm = $request->query->get('search', '');

        // Création de la requête avec QueryBuilder
        $queryBuilder = $entityManager->getRepository(Menu::class)->createQueryBuilder('m');

        // Si un terme de recherche est fourni, ajouter une condition
        if (!empty($searchTerm)) {
            $queryBuilder->where('m.menuNom LIKE :searchTerm')
                         ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Exécuter la requête pour obtenir les résultats
        $menus = $queryBuilder->getQuery()->getResult();
        $nombreMenu = 0;
        foreach ($menus as $key => $menu) {
            $nombreMenu ++ ;
            // Récupérer la collection des repas pour ce menu
            $listOfRepas = $menu->getRepas();
            
            // Vérifier si la collection de repas est vide (ou si elle contient moins d'un repas)
            if ($listOfRepas->count() == 0) {
                // Supprimer le menu si il n'a pas de repas
                $entityManager->remove($menu);
                $entityManager->flush();
        
                // Supprimer le menu de la liste locale
                unset($menus[$key]); // Utilisation de unset() pour retirer l'élément du tableau
            }
        }
        $repas = $repatRepository->findAll();
        $totalRepas = 0 ;
        foreach ($repas as $repas){
            if($repas->isEstDisponible()==true){
                $totalRepas ++ ;
            }
        }

        // Retourner la vue avec les menus
        return $this->render('page_admin_menu/tablesMenu.html.twig', [
            'menus' => $menus,
            'searchTerm' => $searchTerm,
            'nombreMenu' => $nombreMenu,
            'totalRepas' => $totalRepas,
        ]);
    }

    
}
