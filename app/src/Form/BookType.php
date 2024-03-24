<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

class BookType extends AbstractType
{
    public const GROUP_CREATE_BOOK = 'create_book';
    public const GROUP_UPDATE_BOOK = 'update_book';
    public const VALIDATION_GROUPS = [
        self::GROUP_CREATE_BOOK,
        self::GROUP_UPDATE_BOOK,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('authors', CollectionType::class, [
                'entry_type' => AuthorType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'constraints' => [
                    new Count(min: 1),
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 500,
                    ]),
                ],
            ])
            ->add('publishedYear', IntegerType::class, [
                'constraints' => [
                    new PositiveOrZero(),
                    new Type('integer'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Book::class,
//            'validation_groups' => self::VALIDATION_GROUPS,
        ]);
    }
}
