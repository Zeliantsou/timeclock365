<?php

namespace App\Form;

use App\Entity\Author;
use App\Form\EventListener\Author\AddBookAmountFieldSubscriber;
use App\Form\EventListener\Author\AddFullNameFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddFullNameFieldSubscriber())
            ->addEventSubscriber(new AddBookAmountFieldSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Author::class,
        ]);
    }
}
