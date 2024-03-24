<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Unique;

class AuthorType extends AbstractType
{
    public const GROUP_CREATE_AUTHOR = 'create_author';
    public const GROUP_UPDATE_AUTHOR = 'update_author';
    public const VALIDATION_GROUPS = [
        self::GROUP_CREATE_AUTHOR,
        self::GROUP_UPDATE_AUTHOR,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255,
                    ]),
                    new Unique(),
                ],
            ])
            ->add('bookAmount', IntegerType::class, [
                'constraints' => [
                    new PositiveOrZero(),
//                    new Type('integer'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Author::class,
//            'validation_groups' => self::VALIDATION_GROUPS,
        ]);
    }
}
