<?php

namespace App\Form\EventListener\Author;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddFullNameFieldSubscriber implements EventSubscriberInterface
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

        if (array_key_exists('fullName', $authorData)) {
            $this->addFullNameField($form);
        }
    }

    private function addFullNameField(FormInterface $form): void
    {
        $form
            ->add('fullName', TextType::class, [
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
