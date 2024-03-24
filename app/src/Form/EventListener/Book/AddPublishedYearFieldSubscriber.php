<?php

namespace App\Form\EventListener\Book;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Positive;

class AddPublishedYearFieldSubscriber implements EventSubscriberInterface
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

        if (array_key_exists('publishedYear', $bookData)) {
            $this->addPublishedYearField($form);
        }
    }

    private function addPublishedYearField(FormInterface $form): void
    {
        $form
            ->add('publishedYear', IntegerType::class, [
                'constraints' => [
                    new Positive(),
                ],
            ])
        ;
    }
}
