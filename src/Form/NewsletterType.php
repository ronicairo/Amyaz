<?php

namespace App\Form;

use App\Entity\NewsletterSubscription;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class NewsletterType extends AbstractType
{

    private $translator;
    private $requestStack;

    public function __construct(TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();

        $builder
            ->add('email', EmailType::class, [
                'label' => "Email",
                'attr' => [
                    'placeholder' => 'Email',
                    'autocomplete' => 'email'
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
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'comment',
                'locale' => $locale
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.abonner',
                'validate' => false,
                'attr' => [
                    'class' => "d-block mt-4 mx-auto btn btn-light col-6"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewsletterSubscription::class,
        ]);
    }
}
