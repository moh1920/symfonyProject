<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;  // Corriger ici l'importation
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/sayari")]
class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(): Response
    {
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

    #[Route('/addMenu', name: 'app_addMenu')]
    public function addMenu(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();

        // Création du formulaire avec MenuType et ajout d'un bouton Submit
        $form = $this->createForm(MenuType::class, $menu);
        $form->add('Ajouter', SubmitType::class, ['label' => 'Ajouter un menu']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //prepare requet SQL
            $entityManager->persist($menu);
            // Enregistrement de l'entité dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_menu');
        }

        return $this->render('menu/addMenu.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/editMenu/{id}', name: 'app_editMenu')]
    public function editMenu(MenuRepository $menuRepository, EntityManagerInterface $entityManager, $id, Request $request)
    {
        // Récupération de l'entité
        $menu = $menuRepository->find($id);
    
        // Vérifiez que le repas existe
        if (!$menu) {
            throw $this->createNotFoundException('Le repas avec cet ID n\'existe pas.');
        }
    
        // Création du formulaire
        $form = $this->createForm(MenuType::class, $menu);
        $form->add('update', SubmitType::class);
        $form->handleRequest($request);
    
        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menu);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_getAllMenu');
        }
    
        // Rendu du formulaire
        return $this->render('menu/editMenu.html.twig', [
            'form' => $form->createView(),
        ]);
    }    
    
    #[Route('/getAllMenu', name: 'app_getAllMenu')]
    public function getAllMenu(MenuRepository $menuRepository): Response{
        $menus = $menuRepository->findAll();

        return $this->render('menu/getAllMenu.html.twig', [
           'menus' => $menus,
        ]);
    }
    
    #[Route('/deleteMenu/{id}', name: 'app_deleteMenu')]
    public function deleteMenu(MenuRepository $menuRepository, EntityManagerInterface $entityManager, $id)
    {
        // Récupération de l'entité
        $menu = $menuRepository->find($id);
    
        // Vérifiez que le menu existe
        if (!$menu) {
            throw $this->createNotFoundException('Le repas avec cet ID n\'existe pas.');
        }
    
        // Suppression de l'entité
        $entityManager->remove($menu);
        $entityManager->flush();
    
        // Redirection vers la liste des menu
        return $this->redirectToRoute('app_getAllMenu');
    }
}
