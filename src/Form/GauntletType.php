<?php

namespace App\Form;

use App\Entity\Gauntlet;
use App\Validator\Deck;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class GauntletType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'label' => 'label.gauntletType',
                'class' => \App\Entity\GauntletType::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank()
                ],
                'placeholder' => false
            ])
            ->add('deckCode', TextType::class, [
                'mapped' => false,
                'label' => 'label.deck_code',
                'constraints' => [
                    new NotBlank(),
                    new Deck()
                ],
                'attr' => [
                    'autofocus' => 'autofocus'
                ]
            ])
            ->add('playedAt', DateTimeType::class, [
                'label' => 'label.game_played_at',
                'widget' => 'single_text',
                'format' => "YYYY-MM-DD HH:mm",
                'constraints' => [
                    new NotBlank(),
                    new Range([
                        'max' => new \DateTime()
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'btn.submit'
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])
        ;
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {
        /** @var Gauntlet $gauntlet */
        $gauntlet = $event->getData();
        $form = $event->getForm();

        // On renseigne le deckCode en modification
        if ($gauntlet->getDeck() !== null) {
            $form->remove('deckCode');
            $form->add('deckCode', TextType::class, [
                'mapped' => false,
                'label' => 'label.deck_code',
                'constraints' => [
                    new NotBlank(),
                    new Deck()
                ],
                'attr' => [
                    'autofocus' => 'autofocus'
                ],
                'data' => $gauntlet->getDeck()->getCode()
            ]);
        }

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gauntlet::class,
        ]);
    }
}
