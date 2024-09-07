<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ChangeEmailFormType extends AbstractType
{

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainEmail', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('form.new_mail'),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 180,
                        'minMessage' => $this->translator->trans('form.min_message'),
                        'maxMessage' => $this->translator->trans('form.max_message'),
                    ]),
                    new Email([
                        'message' => $this->translator->trans('form.valid_email'),
                    ]),
                ],
            ])
            ->add('repeatEmail', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('form.repeat_mail'),
                    'autocomplete' => "new-email"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 180,
                        'minMessage' => $this->translator->trans('form.min_message'),
                        'maxMessage' => $this->translator->trans('form.max_message'),
                    ]),
                    new Email([
                        'message' => $this->translator->trans('form.valid_email'),
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.validate',
                'validate' => false,
                'attr' => [
                    'class' => "d-block mt-4 mx-auto btn btn-outline-light col-7"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
