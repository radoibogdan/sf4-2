<?php

namespace App\Controller;

use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AccountController extends AbstractController
{
    /**
     * @Route("/account/profile", name="account_profile")
     * Autoriser l'accèes uniquement aux utilisateurs connectés
     * Grace au framework extra bundle / sensio on a les annotations
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérer l'utilisateur actuel $this->getUser()
        // Associer le formulaire à l'utilisateur actuel: $this->createForm(Form::class, $this->getUser())
        $form = $this->createForm(UserProfileFormType::class,  $this->getUser());
        // handleRequest permet de 
        // garder les champs renseignés en cas d'erreur 
        // avoir le formulaire prérempli 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Il n'est pas nécessaire d'appeler persist() pour modifier des entités
            $entityManager->flush();
            // le flash est supprimé de la session après avoir été affiché 
            $this->addFlash('success', 'Votre profil a été mis à jour.');
        }
        return $this->render('account/profile.html.twig', [
            'profile_form' => $form->createView()
        ]);
    }
}
