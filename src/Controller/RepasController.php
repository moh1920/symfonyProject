<?php

namespace App\Controller;

use App\Entity\Repat;
use App\Form\RepasType; // Assurez-vous que le type de formulaire est importé
use App\Repository\RepatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Import de SubmitType
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
    public function addRepas(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repas = new Repat();

        // Création du formulaire avec RepatType et ajout d'un bouton Submit
        $form = $this->createForm(RepasType::class, $repas);
        $form->add('Ajouter', SubmitType::class, ['label' => 'Ajouter un repas']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($repas);
            $entityManager->flush();

            return $this->redirectToRoute('app_getAllRapas');
        }

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

        return $this->redirectToRoute('app_getAllRapas');
    }
    #[Route("/editRepas/{id}", name:"app_editRepas")]
    public function edit(RepatRepository $repatRepository, EntityManagerInterface $entityManager, $id, Request $request)
    {
        // Récupération de l'entité
        $repas = $repatRepository->find($id);
    
        // Vérifiez que le repas existe
        if (!$repas) {
            throw $this->createNotFoundException('Le repas avec cet ID n\'existe pas.');
        }
    
        // Création du formulaire
        $form = $this->createForm(RepasType::class, $repas);
        $form->add('update', SubmitType::class);
        $form->handleRequest($request);
    
        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($repas);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_getAllRapas');
        }
    
        // Rendu du formulaire
        return $this->render('repas/editRepas.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
