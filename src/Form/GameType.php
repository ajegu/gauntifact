<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Validator\Deck;
use App\Validator\GameStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GameType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Gauntlet
     */
    private $gauntlet;

    /**
     * GameType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->gauntlet = $options['gauntlet'];

        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'label.game_status',
                'choices' => [
                    $this->translator->trans('label.game_win') => Game::STATUS_WIN,
                    $this->translator->trans('label.game_lose') => Game::STATUS_LOSE,
                    $this->translator->trans('label.game_draw') => Game::STATUS_DRAW,
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('deckCode', TextType::class, [
                'mapped' => false,
                'label' => 'label.deck_code',
                'constraints' => [
                    new Deck()
                ],
                'attr' => [
                    'autofocus' => 'autofocus'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'btn.submit'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'validateStatus'])
        ;
    }

    public function validateStatus(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        // On compte le nombre de game perdu ou nulle
        if ($data['status'] === Game::STATUS_LOSE || $data['status'] === Game::STATUS_DRAW) {
            $count = 0;
            foreach ($this->gauntlet->getGames() as $game) {
                if (in_array($game->getStatus(), [Game::STATUS_LOSE, Game::STATUS_DRAW])) {
                    $count++;
                }
            }

            if ($count === 2) {
                $form->addError(new FormError(
                    $this->translator->trans('error.max_game_lose')
                ));
            }
        } else {
            // On compte le nombre de game gagnée
            $count = 0;
            foreach ($this->gauntlet->getGames() as $game) {
                if ($game->getStatus() === Game::STATUS_WIN) {
                    $count++;
                }
            }

            if ($count === 5) {
                $form->addError(new FormError(
                    $this->translator->trans('error.max_game_won')
                ));
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class
        ]);

        $resolver->setRequired([
            'gauntlet'
        ]);
    }
}
