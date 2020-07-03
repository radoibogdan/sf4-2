<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Page d'accueil, affichage des nouveaux produits de moins d'un mois
     * @Route("/", name="home")
     */
    public function index(ProductRepository $repository, CategoryRepository $categoryRepository)
    {
        // throw new \Exception('Salut, je suis le message de l exception !');

        /**
         * findAll() / findBy() / findOneBy() / find()
         * va effectuer une comparaison d'égalité, alors que nous souhaitons effectuer une comparaison de supériorité
         * solution : créer notre propre méthode dans le ProductRepository
        */

        /* 
         *  $repository->findBy([
         *      'createdAt' => new \DateTime('-1 month')
         *  ]); 
        */
        
        // On utilise notre propre méthode pour  récupérer les nouveautés
        $result = $repository->findNews();

        // Récupération de toutes les catégories
        $categories = $categoryRepository->findAll();


        return $this->render('home/index.html.twig',[
            'new_products' => $result,
            'categories' => $categories
        ]);
    }
}
