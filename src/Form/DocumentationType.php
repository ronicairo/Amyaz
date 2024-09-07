<?php
namespace App\Form;

use App\Entity\CategoryDoc;
use App\Entity\Documentation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DocumentationType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titleFr', TextType::class, [
                'label' => "Titre de l'article FR*",
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'Votre titre FR doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' => 'Votre titre FR doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('titleEn', TextType::class, [
                'label' => "Titre de l'article EN*",
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'Votre titre EN doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' => 'Votre titre EN doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('contentFr', TextareaType::class, [
                'label' => 'Contenu FR',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ])
                ]
            ])
            ->add('contentEn', TextareaType::class, [
                'label' => 'Contenu EN',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ])
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => CategoryDoc::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'placeholder' => 'Choisir une catégorie',
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier PDF *',
                'data_class' => null,
                'mapped' => false,
                'required' => false, 
                'attr' => [
                    'value' => $options['file'] !== null ? $options['file'] : ''
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                        'maxSize' => '40M',
                        'maxSizeMessage' => 'La taille du fichier ne doit pas dépasser 40 Mo.',
                    ]),

                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['file'] === null ? 'Créer' : 'Modifier',
                'validate' => false,
                'attr' => [
                    'class' => "d-block mt-4 mx-auto btn btn-outline-light col-5"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documentation::class,
            'allow_file_upload' => true,
            'file' => null
        ]);
    }
}
