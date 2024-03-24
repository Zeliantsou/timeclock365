<?php

namespace App\Form\EventListener\Author;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class AddBookAmountFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSubmit(FormEvent $event): void
    {
        $authorData = $event->getData();
        assert(is_array($authorData));
        $form = $event->getForm();

        if (array_key_exists('bookAmount', $authorData)) {
            $this->addBookAmountField($form);
        }
    }

    private function addBookAmountField(FormInterface $form): void
    {
        $form
            ->add('bookAmount', IntegerType::class, [
                'constraints' => [
                    new PositiveOrZero(),
                ],
            ])
        ;
    }
}
