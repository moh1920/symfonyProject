<?php

namespace App\Controller;

use App\Entity\Repat;
use App\Form\RepasType; // Assurez-vous que le type de formulaire est importé
use App\Repository\RepatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Import de SubmitType
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/sayari")]
class RepasController extends AbstractController
{
    #[Route('/repas', name: 'app_repas')]
    public function index(): Response
    {
        return $this->render('repas/index.html.twig', [
            'controller_name' => 'RepasController',
        ]);
    }

        #[Route('/addRepas', name: 'app_addRepas')]    
    public function addRepas(
        Request $request, 
        EntityManagerInterface $entityManager, 
        #[Autowire('%images_dir%')] string $imageDir
    ): Response {
        $repas = new Repat();

        // Création du formulaire avec RepatType et ajout d'un bouton Submit
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repas = $form->getData();
            

            // Gestion de l'image
            if ($image = $form['image']->getData()) {
                $fileName = uniqid() . '.' . $image->guessExtension(); // Génère un nom unique
                $image->move($imageDir, $fileName);
                $repas->setImage($fileName);
            }
            
            if($repas->getPrixRepas() > 1){
                 // Sauvegarde de l'entité
                 $entityManager->persist($repas);
                 $entityManager->flush();
                 $this->addFlash('success', 'Le repas a été ajouté avec succès.');

                 // Redirection vers une autre page (par exemple, la liste des repas)
                 return $this->redirectToRoute('app_page_admin_repas');

            }
           
            
        }

        // Retourner le formulaire pour l'affichage
        return $this->render('repas/addRepas.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/getAllRapas", name:"app_getAllRapas")]
    public function getAllRapas(RepatRepository $repatRepository): Response
    {
        $repas = $repatRepository->findAll();

        return $this->render('repas/getAllRapas.html.twig', [
           'repas' => $repas,
        ]);
    }
    #[Route("/deleteRepas/{id}", name:"app_deleteRepas")]
    public function deleteRepas(int $id, RepatRepository $repatRepository, EntityManagerInterface $entityManager): Response
    {
        $repas = $repatRepository->find($id);

        if ($repas) {
            $entityManager->remove($repas);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_page_admin_repas');
    }
    #[Route("/editRepas/{id}", name:"app_editRepas")]
    public function edit(RepatRepository $repatRepository, 
    EntityManagerInterface $entityManager,
     $id, Request $request,
     #[Autowire('%images_dir%')] string $imageDir)
    {
        // Récupération de l'entité
        $repas = $repatRepository->find($id);
    
        // Vérifiez que le repas existe
        if (!$repas) {
            throw $this->createNotFoundException('Le repas avec cet ID n\'existe pas.');
        }
    
        // Création du formulaire
        $form = $this->createForm(RepasType::class, $repas);
        $form->handleRequest($request);
    
        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $repas = $form->getData();

            // Gestion de l'image
            if ($image = $form['image']->getData()) {
                $fileName = uniqid() . '.' . $image->guessExtension(); // Génère un nom unique
                $image->move($imageDir, $fileName);
                $repas->setImage($fileName);
            }
            $entityManager->persist($repas);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_page_admin_repas');
        }
    
        // Rendu du formulaire
        return $this->render('repas/editRepas.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
