<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ConfirmDeletionFormType;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_ADMIN")
 * 
 * Spécifier un préfixe d'URI et de nom de route:
 * @Route("/admin/product", name="admin_product_")
 */
class AdminProductController extends AbstractController
{
    /**
     * Liste des produits. On combine l'annotation Route de la classe avec celle de la méthode
     *      URI préfixé : /admin/product
     *      URI final   : /admin/products
     *      name préfixé : admin_product_
     *      name final   : admin_product_list
     * @Route("s", name="list")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        
        return $this->render('admin_product/index.html.twig',[
            'product_list' => $products
        ]);
    }

    /**
     * @Route("/new", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProductFormType::class);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            // parce que le formulaire est lié à l'entité produit, le getData retourne un Produit
            $product = $form->getData();
            // $product->setCreatedAt(new DateTime());
            // dd($product);
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Le produit vient d\'être rajouté!');
            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin_product/add.html.twig',[
            'product_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Product $product, Request $request, EntityManagerInterface $em)
    {
        // Le fait de mettre Product comme argument va récupérer le bon produit de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            // pas besoin de getData. Les modifications sont faites automatiquement
            $em->flush();
            $this->addFlash('success', 'Modifications enregistrées!');
        }

        return $this->render('admin_product/edit.html.twig',[
            'product' => $product, // pour rajouter des informations en plus du formulaire
            'product_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(Product $product, Request $request, EntityManagerInterface $entityMananger)
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //A l'inverse de persist(), remove() prépare à la suppression d'une entité
            $entityMananger->remove($product);
            $entityMananger->flush();

            $this->addFlash('success', 'Le produt a été supprimé.');
            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin_product/delete.html.twig',[
            'product' => $product,
            'deletion_form' => $form->createView()
        ]);
    }
}
