<?php

namespace App\Form;

use App\Entity\Repat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomRepat', TextType::class, [
                'label' => 'Nom du repas',
                'attr' => ['class' => 'form-control'], // Classe CSS ajoutée
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du repas',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5, // Exemple d'attribut supplémentaire
                ],
            ])
            ->add('estDisponible', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,  // Ensures it is rendered as radio buttons
                'multiple' => false, // Ensures only one option can be selected
                'attr' => ['class' => 'form-check-inline'], // Display them inline
            ])
            ->add('prixRepas', MoneyType::class, [
                'label' => 'Prix du repas',
                'currency' => 'TND',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'prixRepas', // ID pour ciblage en JS
                ],
            ])
                       
            ->add('image', FileType::class, [
                'label' => 'Image du repas (JPEG, PNG)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '5000k']),
                ],
                'attr' => ['class' => 'form-control-file'], // Classe pour les inputs de type fichier
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repat::class,
            'attr' => ['class' => 'custom-form'], // Classe CSS globale pour le formulaire
        ]);
    }
}
