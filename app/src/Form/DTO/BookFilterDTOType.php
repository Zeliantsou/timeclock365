<?php

namespace App\Form\DTO;

use App\DTO\BookFilterDTO;
use App\Form\EventListener\Book\AddPublishedYearFieldSubscriber;
use App\Form\EventListener\Book\AddTitleFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFilterDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddTitleFieldSubscriber())
            ->addEventSubscriber(new AddAuthorNameFieldSubscriber())
            ->addEventSubscriber(new AddPublishedYearFieldSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => BookFilterDTO::class,
        ]);
    }
}
