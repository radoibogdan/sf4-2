<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController {

    /**
     * @Route("/product", name="product_list")
     */
    public function index(ProductRepository $repository): Response
    {
        // Récupération de tous les produits
        $product_list = $repository->findAll();
        return $this->render('product/index.html.twig', [
            'product_list' => $product_list
        ]);
    }

    /**
     * Grace au ParamConverter (installé par frameworkExtraBundle / Annotation)
     * Symfony va récupérer l'entité Product qui correspond à l'identifiant dans l'URI
     * 
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig',[
            'product' => $product
        ]);
    }


}

// Récupérer une entité par son ID
// $result3 = $repository->find(1); // produit ou null

// Récupérer une entité par son ID
// $result3 = $repository->findOneBy(['id'=>1]); // produit ou null 