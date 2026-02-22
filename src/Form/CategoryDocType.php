<?php
namespace App\Form;

use App\Entity\CategoryDoc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryDocType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
        ->add('name', TextType::class, [
            'label' => $this->translator->trans('form.name_category'),
            'constraints' => [
                new NotBlank([
                    'message' => $this->translator->trans('form.not_blank'),
                ])
            ]
        ])
            ->add('submit', SubmitType::class, [
                'label' => "form.validate",
                'attr' => [
                    'class' => 'submit-category-btn'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryDoc::class,
        ]);
    }
}
