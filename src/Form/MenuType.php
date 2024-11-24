<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Repat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('menuNom', null, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('menuDescription', null, [
                'attr' => ['class' => 'form-control'],
            ])
           
            ->add('menuDisponible', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,  // Ensures it is rendered as radio buttons
                'multiple' => false, // Ensures only one option can be selected
                'attr' => ['class' => 'form-check-inline'], // Display them inline
            ])
            ->add('menuDateCreation', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('menuDateExpiration', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
