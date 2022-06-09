<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    
        /**
         * @isGranted("ROLE_USER)
         * @Route("/liste-produits",name="app_liste-produits")
         */
        public function liste(ProduitRepository $pr):Response{
            return $this->render("produit/liste-produits.html.twig",
            ["produits"=>$pr->findAll()]);
        }

    /**
     * @isGranted("ROLE_USER)
     * @Route("/ajouter-produit",name="app_ajouter-produit")
     */
    public function nouveauSouhait(Request $request, ProduitRepository $pr): Response
    {
        if($this->getUser()==null){
            return $this->redirectToRoute("app_login");
        }
        else{
            $produit = new Produit();
            $produitForm = $this->createForm(ProduitType::class, $produit);
            $produitForm->handleRequest($request);

            if ($produitForm->isSubmitted() && $produitForm->isValid()) {
                $pr->add($produit, true);
                $this->addFlash('success', 'Nouveau article ajouté.');
                return $this->redirectToRoute("app_home");
            }

            return $this->render(
                "produit/ajouter-produit.html.twig",
                ["produitForm" => $produitForm->createView()]
            );
        }
        
    }

    /**
     * @isGranted("ROLE_USER)
     * @Route("/{id}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @isGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->add($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @isGranted("ROLE_ADMIN")
     * @Route("/{id}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {

        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
