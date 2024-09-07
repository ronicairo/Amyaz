<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordConfirmationType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('form.password'),
                    'autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Length([
                        'min' => 12,
                        'max' => 255,
                        'minMessage' => $this->translator->trans('form.min_password'),
                        'maxMessage' => $this->translator->trans('form.max_password'),
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                        'message' => $this->translator->trans('form.regex_password'),
                    ]),
                ],

            ],
            'second_options' => [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ])],
                'attr' => [
                    'placeholder' => $this->translator->trans('form.password_repeat'),
                    'autocomplete' => "new-password"
                ]

            ],
            'invalid_message' => $this->translator->trans('form.password_not_same'),
        ])
        ->add('submit', SubmitType::class, [
            'label' => "form.validate",
            'validate' => false,
            'attr' => [
                'class' => "d-block mt-4 mx-auto btn btn-outline-light col-7"
            ]
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
