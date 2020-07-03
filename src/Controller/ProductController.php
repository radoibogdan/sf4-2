<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController {

    /**
     * @Route("/product", name="product_list")
     */
    public function index(ProductRepository $repository, CategoryRepository $categoryRepository): Response
    {
        // Récupération de tous les produits
        $product_list = $repository->findAll();

        // Récupération de toutes les catégories
        $categories = $categoryRepository->findAll();

        return $this->render('product/index.html.twig', [
            'product_list' => $product_list,
            'categories' => $categories
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

    /** 
    * @Route("/product/{id}/category_show", name="category_show")
    */
    public function show_products_from_category($id, CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([
            'category' => $id
        ]);

        $categories = $categoryRepository->findAll();

        return $this->render('product/show_category.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

}

// Récupérer une entité par son ID
// $result3 = $repository->find(1); // produit ou null

// Récupérer une entité par son ID
// $result3 = $repository->findOneBy(['id'=>1]); // produit ou null 