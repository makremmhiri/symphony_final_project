<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit')]
    public function index(Request $request, ProduitRepository $produitRepository): Response
    {
        $searchTerm = $request->query->get('search') ?? '';
        $selectedMarque = $request->query->get('marque') ?? '';
        $selectedStock = $request->query->get('stock') ?? '';

        $produits = $produitRepository->findByFilters($searchTerm, $selectedMarque, $selectedStock);
        $marques = $produitRepository->findUniqueMarques();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'marques' => $marques,
            'searchTerm' => $searchTerm,
            'selectedMarque' => $selectedMarque,
            'selectedStock' => $selectedStock
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload - SIMPLE VERSION WITHOUT VALIDATION
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                // Get original filename
                $originalFilename = $imageFile->getClientOriginalName();
                
                // Sanitize filename (remove special characters)
                $safeFilename = preg_replace('/[^A-Za-z0-9\-\.]/', '', $originalFilename);
                
                // If you want unique names, use this:
                // $newFilename = uniqid().'.'.$imageFile->guessExtension();
                
                // Or keep original name (what you asked for)
                $newFilename = $safeFilename;
                
                // Ensure directory exists
                $uploadDir = $this->getParameter('kernel.project_dir').'/public/images/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                try {
                    // Move file to public directory
                    $imageFile->move($uploadDir, $newFilename);
                    // Save ONLY the filename (not path) in database
                    $produit->setImg($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image: '.$e->getMessage());
                }
            } else {
                // If no image uploaded, set default or empty
                $produit->setImg('default.jpg');
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Produit créé avec succès!');
            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                // Get original filename
                $originalFilename = $imageFile->getClientOriginalName();
                $safeFilename = preg_replace('/[^A-Za-z0-9\-\.]/', '', $originalFilename);
                $newFilename = $safeFilename;
                
                // Delete old image if exists and not default
                $oldImage = $produit->getImg();
                if ($oldImage && $oldImage !== 'default.jpg') {
                    $oldImagePath = $this->getParameter('kernel.project_dir').'/public/images/products/'.$oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                // Move new file
                $uploadDir = $this->getParameter('kernel.project_dir').'/public/images/products/';
                try {
                    $imageFile->move($uploadDir, $newFilename);
                    $produit->setImg($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image: '.$e->getMessage());
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Produit modifié avec succès!');
            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            // Delete image file if exists and not default
            $imageFile = $produit->getImg();
            if ($imageFile && $imageFile !== 'default.jpg') {
                $imagePath = $this->getParameter('kernel.project_dir').'/public/images/products/'.$imageFile;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $entityManager->remove($produit);
            $entityManager->flush();
            
            $this->addFlash('success', 'Produit supprimé avec succès!');
        }

        return $this->redirectToRoute('app_produit');
    }
}