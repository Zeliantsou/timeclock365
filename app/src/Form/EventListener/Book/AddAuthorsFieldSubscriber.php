<?php

namespace App\Form\EventListener\Book;

use App\Entity\Author;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Count;

class AddAuthorsFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSubmit(FormEvent $event): void
    {
        $bookData = $event->getData();
        assert(is_array($bookData));
        $form = $event->getForm();

        if (array_key_exists('authors', $bookData)) {
            $this->addAuthorsField($form);
        }
    }

    private function addAuthorsField(FormInterface $form): void
    {
        $form
            ->add('authors', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Author::class,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'constraints' => [
                    new Count(min: 1),
                ],
            ])
        ;
    }
}
