<?php

namespace App\Form;

use App\Entity\Traduction;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class AddWordType extends AbstractType
{
    private $requestStack;
    private $security;

    public function __construct(RequestStack $requestStack, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Always present fields
        $builder
            ->add('singular', TextType::class, [
                'label' => 'post.rif_singular',
            ])
            ->add('plural', TextType::class, [
                'label' => 'post.rif_plural',
            ])
            ->add('phoneticSingular', TextType::class, [
                'label' => 'post.phonetic_singular',
            ])
            ->add('phoneticPlural', TextType::class, [
                'label' => 'post.phonetic_plural',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.validate',
                'validate' => false,
                'attr' => [
                    'class' => "d-block mt-4 mx-auto btn btn-outline-light col-5"
                ]
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'traduction',
            ]);
    
        // Conditionally add or remove fields based on user role and locale
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $locale = $this->requestStack->getCurrentRequest()->getLocale();
            $user = $this->security->getUser();
            $roles = $user ? $user->getRoles() : [];
    
            if (in_array('ROLE_ADMIN', $roles) || in_array('ROLE_MODERATOR', $roles)) {
                // Admins and Moderators see all fields and they are required
                $form->add('wordFR', TextType::class, [
                    'label' => 'Français *',
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez ajouter un mot en français',
                        ])
                    ]
                ]);
                $form->add('grammarFR', TextType::class, [
                    'label' => 'Classe gramaticale',
                    'attr' => [
                        'placeholder' => "ex: verbe",
                    ],
                ]);
                $form->add('wordEN', TextType::class, [
                    'label' => 'English *',
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please add a word in English',
                        ])
                    ]
                ]);
                $form->add('grammarEN', TextType::class, [
                    'label' => 'Content',
                    'attr' => [
                        'placeholder' => "ex: verb",
                    ],
                ]);
    
                // Make 'singular' field required for Admins and Moderators
                $form->add('singular', TextType::class, [
                    'label' => 'Rifain',
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le champ "Rifain" est obligatoire pour les administrateurs ou modérateurs.',
                        ]),
                    ],
                ]);
    
            } else {
                // Regular users see fields based on locale
                if ($locale === 'fr') {
                    $form->add('wordFR', TextType::class, [
                        'label' => 'Français *',
                        'required' => true,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Veuillez ajouter un mot en français',
                            ])
                        ]
                    ]);
                    $form->add('grammarFR', TextType::class, [
                        'label' => 'Classe grammaticale',
                        'attr' => [
                            'placeholder' => "ex: verbe",
                        ],
                    ]);
    
                    // Remove fields not relevant to the locale
                    $form->remove('wordEN');
                    $form->remove('grammarEN');
                } elseif ($locale === 'en') {
                    $form->add('wordEN', TextType::class, [
                        'label' => 'English *',
                        'required' => true,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Please add a word in English',
                            ])
                        ]
                    ]);
                    $form->add('grammarEN', TextType::class, [
                        'label' => 'Grammar class',
                        'attr' => [
                            'placeholder' => "ex: verb",
                        ],
                    ]);
    
                    // Remove fields not relevant to the locale
                    $form->remove('wordFR');
                    $form->remove('grammarFR');
                }
    
                // 'singular' field remains not required for regular users
            }
        });
    }    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Traduction::class,
        ]);
    }
}
