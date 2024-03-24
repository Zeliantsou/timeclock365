<?php

namespace App\Form\EventListener\Book;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTitleFieldSubscriber implements EventSubscriberInterface
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

        if (array_key_exists('title', $bookData)) {
            $this->addTitleField($form);
        }
    }

    private function addTitleField(FormInterface $form): void
    {
        $form
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255,
                    ]),
                ],
            ])
        ;
    }
}
