<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\HttpFoundation\RequestStack; // Add this import

class CommentType extends AbstractType
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
        // Get the current locale from the request
        $locale = $this->requestStack->getCurrentRequest()->getLocale();

        $builder
            ->add('opinion', TextareaType::class, [
                'attr' => [
                    'style' => 'width: 100%; padding: 10px; font-size: 16px; color: #fff; border: none; border-bottom: 1px solid #fff; background: transparent; box-shadow: none;'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('form.not_blank'),
                    ]),
                    new Regex([
                        'pattern' => '/<\/?[a-z][\s\S]*>/i',
                        'message' =>  $this->translator->trans('form.regex_commentaire'),
                        'match' => false,
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 200,
                        'minMessage' => $this->translator->trans('form.min_comment'),
                        'maxMessage' => $this->translator->trans('form.max_comment'),
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('home.comment'),
                'label_html' => true,  // Permet d'interpréter le HTML dans le label
                'attr' => [
                    'class' => 'd-block mt-4 mx-auto btn btn-light col-6'
                ]
            ])
          ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
