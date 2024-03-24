<?php

namespace App\Form\EventListener\Book;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;

class AddDescriptionFieldSubscriber implements EventSubscriberInterface
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

        if (array_key_exists('description', $bookData)) {
            $this->addDescriptionField($form);
        }
    }

    private function addDescriptionField(FormInterface $form): void
    {
        $form
            ->add('description', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 500,
                    ]),
                ],
            ])
        ;
    }
}
