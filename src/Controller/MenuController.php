<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Repat;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use App\Repository\RepatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;  // Corriger ici l'importation
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function addMenu(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();
        $prixRepasTotal = 0;
    
        // Création du formulaire avec MenuType et ajout d'un bouton Submit
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);
        $menu = $form->getData();
    
        // Récupérer les repas sélectionnés depuis la session
        $selectedRepasIds = $session->get('selected_repas', []);
        if (!empty($selectedRepasIds)) {
            // Récupérer les repas dans la base de données
            $repas = $entityManager->getRepository(Repat::class)->findBy(['id' => $selectedRepasIds]);
    
            foreach ($repas as $repasItem) {
    
                // Ajouter le repas au menu s'il est disponible
                if ($repasItem->isEstDisponible()) {
                    $prixRepasTotal += $repasItem->getPrixRepas();
                    $menu->addRepat($repasItem);
                }
            }
    
            // Définir le prix du menu total
            $menu->setMenuPrix($prixRepasTotal);
        }
        // Vérification et mise à jour de la disponibilité du menu
        $currentDate = new \DateTime(); // Date actuelle
        if ($menu->getMenuDateExpiration() < $currentDate) {
            // Si la date d'expiration est passée, le menu n'est plus disponible
            $menu->setMenuDisponible(false); // Suppose que `setEstDisponible()` existe sur le Menu
        }
    
        // Validation et soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            
            if($menu->getMenuDateCreation() > $menu->getMenuDateExpiration()){
                $this->addFlash('danger', 'La date de création du menu doit être inférieure à la date d\'expiration.');
                return $this->render('menu/addMenu.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
                // Enregistrer le menu dans la base de données
                $entityManager->persist($menu);
                $entityManager->flush();
    
                // Redirection vers la page d'administration des menus
                return $this->redirectToRoute('app_page_admin_menu');
            
        }
    
        // Retourner le formulaire pour l'affichage
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
        $form->handleRequest($request);
    
        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menu);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_page_admin_menu');
        }
    
        // Rendu du formulaire
        return $this->render('menu/editMenu.html.twig', [
            'form' => $form->createView(),
        ]);
    }    
    
    #[Route('/getAllMenu', name: 'app_getAllMenu')]
    public function getAllMenu(MenuRepository $menuRepository): Response
    {
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
        return $this->redirectToRoute('app_page_admin_menu');
    }
    #[Route('/menu/select-repas', name: 'menu_select_repas')]
     public function selectRepas(Request $request, SessionInterface $session, RepatRepository $repatRepository): Response
{
    // Récupération des repas
    $repas = $repatRepository->findAll();

    // Si des repas sont sélectionnés, les stocker dans la session
    if ($request->isMethod('POST')) {
        $selectedRepas = $request->request->all('repas');
        $session->set('selected_repas', $selectedRepas);
        
        return $this->redirectToRoute('app_addMenu');
    }

    return $this->render('menu/listeOfRepas.html.twig', [
        'repas' => $repas,
    ]);
}
    #[Route('/menu/getRepasMenu/{id}', name:"menu_getRepasMenu")]
    public function listeOfRepasAction(Menu $menu ){
        $listeOfRepas = $menu->getRepas();
        return $this->render('menu/listeRepasOfMenuSelect.html.twig', [
            'listeOfRepas' => $listeOfRepas,
        ]);
    }    
    #[Route('/menu/adminShowRepas/{id}', name:"menu_adminShowRepas")]
    public function adminShowRepas(Menu $menu ){
        $listeOfRepas = $menu->getRepas();
        return $this->render('menu/adminShowRepas.html.twig', [
            'listeOfRepas' => $listeOfRepas,
            'menu'=>$menu,
        ]);
    }
    


    #[Route("menu/deleteRepasOfMenu/{idRepas}/{idMenu}", name:"menu_deleteRepasOfMenu")]   
public function deleteRepasOfMenu($idRepas, $idMenu, EntityManagerInterface $entityManager, MenuRepository $menuRepository, RepatRepository $repasRepository)
{
    // Récupérer le repas et le menu
    $repas = $repasRepository->find($idRepas);
    $menu = $menuRepository->find($idMenu);
    $prixRepasDeleted = $repas->getPrixRepas();

    if (!$repas || !$menu) {
        throw $this->createNotFoundException('Repas ou Menu non trouvé');
    }

    // Supprimer le repas de la liste du menu
    $menu->getRepas()->removeElement($repas);
    $menu->setMenuPrix($menu->getMenuPrix() - $prixRepasDeleted);

    // Si la relation est bidirectionnelle, supprimer également le menu du repas
    // $repas->removeMenu($menu); // Décommentez si nécessaire

    // Persister les modifications
    $entityManager->persist($menu);
    $entityManager->flush();

    // Rediriger vers la page d'affichage des repas du menu
    return $this->redirectToRoute('menu_adminShowRepas', ['id' => $idMenu]);
}

//hethi lista ta3 ili bch izedeha ba3ed ma chouf lista
#[Route('menu/showRepasOfMenu/{id}', name:"menu_showRepasOfMenu")]
public function showRepas (RepatRepository $repatRepository , MenuRepository $menuRepository,$id){
    $menu = $menuRepository->find($id);
    $listeOfRepas = $repatRepository->findAll();
    return $this->render('menu/listeOfRepasAddToMenu.html.twig', [
        'listeOfRepas' => $listeOfRepas,
        'menu'=> $menu ,
        
    ]);
}

#[Route('menu/addRepasToMenu/{id}', name:"menu_addRepasToMenu", methods:["POST"])]
public function addRepasToMenu(
    Request $request, 
    Menu $menu, 
    RepatRepository $repasRepository, 
    EntityManagerInterface $entityManager,
    SessionInterface $session

): Response {
    

    $prixRepas = 0 ;
    // Si des repas sont sélectionnés, les stocker dans la session
    if ($request->isMethod('POST')) {
        $selectedRepas = $request->request->all('repas');
        }

    foreach ($selectedRepas as $repasId) {
        // Trouver chaque repas sélectionné
        $repas = $repasRepository->find($repasId);

        // Ajouter le repas au menu si ce n'est pas déjà fait
        if ($repas && !$menu->getRepas()->contains($repas)) {
            $prixRepas += $repas->getPrixRepas();
            $menu->addRepat($repas);
            $menu->setMenuPrix($menu->getMenuPrix() + $prixRepas);
        }
    }

    // Enregistrer les modifications en base de données
    $entityManager->persist($menu);
    $entityManager->flush();

    // Rediriger vers la page d'affichage des repas du menu
    return $this->redirectToRoute('menu_adminShowRepas', ['id' => $menu->getId()]);
}

#[Route('/comsomationMenu', name:"app_comsomationMenu")]
public function consommationMenu(MenuRepository $menuRepository)
{
    // Exemple de récupération de données (remplacer par une vraie logique)
    // Supposons que tu as une table "menu_consommation" avec un champ "consommation" et "date"
    
    // Exemple de données récupérées par semaine
    $data = [
        'semaines' => ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4'],
        'consommation' => [50, 75, 100, 60]  // Nombre de repas consommés chaque semaine
    ];

    return $this->render('menu/consomationMenu.html.twig', [
        'semaines' => $data['semaines'],
        'consommation' => $data['consommation'],
    ]);
}





}


