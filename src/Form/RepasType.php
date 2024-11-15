<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Repat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomRepat')
            ->add('menus', EntityType::class, [
                'class' => Menu::class,            // L'entité à utiliser
                'choice_label' => 'nomMenu',       // Le nom du champ à afficher pour chaque option
                'multiple' => true,                // Permet la sélection multiple
                'expanded' => true                 // Affiche sous forme de cases à cocher; utilise `false` pour une liste déroulante
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repat::class,
        ]);
    }
}
