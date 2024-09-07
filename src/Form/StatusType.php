<?php

namespace App\Form;

use App\Entity\Status;
use App\Entity\Traduction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StatusType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'libelle', // Remplacez 'libelle' par le champ approprié de votre entité Status
                'label' => 'Status',
                'required' => true,
            ])
            ->add('wordFR', TextType::class, [
                'label' => 'Word (FR)',
                'required' => false,
            ])
            ->add('wordEN', TextType::class, [
                'label' => 'Word (EN)',
                'required' => false,
            ])
            ->add('singular', TextType::class, [
                'label' => 'Singular',
                'required' => false,
            ])
            ->add('plural', TextType::class, [
                'label' => 'Plural',
                'required' => false,
            ])
            ->add('phoneticSingular', TextType::class, [
                'label' => 'Phonetic Singular',
                'required' => false,
            ])
            ->add('phoneticPlural', TextType::class, [
                'label' => 'Phonetic Plural',
                'required' => false,
            ])
            ->add('justification', TextType::class, [
                'label' => 'Justification',
                'required' => false,
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Traduction::class,
        ]);
    }
}
