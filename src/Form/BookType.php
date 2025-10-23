<?php

namespace App\Form;

use App\Entity\Book; 
use App\Entity\User; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref', TextType::class, [
                'label' => 'Référence',
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('publicationDate', DateType::class, [
                'widget' => 'choice',
                'years' => range(1990, date('Y')),
                'label' => 'Date de publication',
            ])

            ->add('published', CheckboxType::class, [
                'required' => false,
                'label' => 'Publié ?',
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ],
                'label' => 'Catégorie',
            ])
        ->add('user', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'username', 
            'label' => 'Auteur',
        ]);



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class, 
        ]);
    }
}
