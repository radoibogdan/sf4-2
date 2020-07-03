<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\ConfirmDeletionFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * Autoriser l'accès qu'aux administrateurs sur toutes les routes de ce controlleur
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/category", name="admin_category_")
 */
class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin_category/index.html.twig', [
            'category_list' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(CategoryFormType::class);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            // parce que le formulaire est lié à l'entité category, le getData retourne une Category
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La catégorie vient d\'être rajoutée!');
            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin_category/add.html.twig',[
            'category_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $em)
    {
        // Le fait de mettre Category comme argument va récupérer la bonne catégorie de la base
        // Pas besoin de récupérer l'id dans la fonction et de le passer à la méthode find()
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            // pas besoin de getData. Les modifications sont faites automatiquement
            $em->flush();
            $this->addFlash('success', 'Les modifications apportées à la catégorie ont été enregistrées!');
        }

        return $this->render('admin_category/edit.html.twig',[
            'category' => $category, // pour rajouter des informations en plus du formulaire
            'category_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(Category $category, Request $request, EntityManagerInterface $entityMananger)
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //A l'inverse de persist(), remove() prépare à la suppression d'une entité
            $entityMananger->remove($category);
            $entityMananger->flush();

            $this->addFlash('success', 'Le produt a été supprimé.');
            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin_category/delete.html.twig',[
            'category' => $category,
            'deletion_form' => $form->createView()
        ]);
    }

    /**
     * @  Route("/{id}/show", name="show")
     */
    /* public function show_products_from_category($id, CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $categories = $productRepository->findBy([
            'category' => $id
        ]);

        return $this->render('admin_category/show_category.html.twig', [
            'category_list' => $categories,
        ]);
    } */
}
