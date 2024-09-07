<?php

namespace App\Form;

use App\Entity\GrammarSheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GrammarSheetType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // French Fields
            ->add('titleFr', TextareaType::class, [
                'label' => 'Titre (Français)',
                'attr' => ['rows' => 1],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre titre FR doit comporter au minimum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('subtitleFr', TextType::class, [
                'label' => 'Sous-titre (Français)',
                'attr' => ['rows' => 1],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre sous-titre FR doit comporter au minimum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('contentFr', TextareaType::class, [
                'label' => 'Contenu (Français)',
                'attr' => ['rows' => 6],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => 'Votre contenu FR doit comporter au minimum {{ limit }} caractères.',
                    ]),
                ],
            ])
            // English Fields
            ->add('titleEn', TextareaType::class, [
                'label' => 'Title (English)',
                'attr' => ['rows' => 1],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.'
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre titre EN doit comporter au minimum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('subtitleEn', TextType::class, [
                'label' => 'Subtitle (English)',
                'attr' => ['rows' => 1],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.'
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre sous-titre EN doit comporter au minimum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('contentEn', TextareaType::class, [
                'label' => 'Content (English)',
                'attr' => ['rows' => 6],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.'
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => 'Votre contenu EN doit comporter au minimum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => "d-block mt-4 mx-auto btn btn-outline-light col-5"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GrammarSheet::class,
        ]);
    }
}
