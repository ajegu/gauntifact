<?php

namespace App\Form;

use App\Entity\Gauntlet;
use App\Validator\Deck;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GauntletType extends AbstractType
{
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
            ->add('submit', SubmitType::class, [
                'label' => 'btn.submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gauntlet::class,
        ]);
    }
}
