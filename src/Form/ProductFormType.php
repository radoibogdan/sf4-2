<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' =>[
                    new NotBlank(['message' => 'Veuillez indiquer un nom.']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le nom ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' =>false
            ])
            ->add('description', TextareaType::class,[
                'required' => false
            ])
            // MoneyType = conversion automatique des INTEGERS en répresentation commune (1700 => 17.00)
            ->add('price', MoneyType::class, [
                'constraints' =>[
                new Positive(['message' => 'Le prix doit être positif']),
                new NotBlank(['message' => 'Le prix est manquant'])
                ],
                'divisor' => 100,
                'required' =>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
