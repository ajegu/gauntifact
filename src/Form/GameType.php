<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Validator\Deck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

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
     * @var int|null
     */
    private $gameId;


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
        $this->gameId = $options['gameId'];

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
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit'])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])
        ;
    }

    public function onPreSetData(FormEvent $event)
    {
        /** @var Game $game */
        $game = $event->getData();
        $form = $event->getForm();

        if ($game->getOpposingDeck() !== null) {
            $form->remove('deckCode');

            $form->add('deckCode', TextType::class, [
                'mapped' => false,
                'label' => 'label.deck_code',
                'constraints' => [
                    new Deck()
                ],
                'attr' => [
                    'autofocus' => 'autofocus'
                ],
                'data' => $game->getOpposingDeck()->getCode()
            ]);
        }
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        // On compte le nombre de game perdu ou nulle
        if ($data['status'] === Game::STATUS_LOSE || $data['status'] === Game::STATUS_DRAW) {
            $count = 0;
            foreach ($this->gauntlet->getGames() as $game) {
                // On ignore la game en cours de modification
                if ($game->getId() === $this->gameId) {
                    continue;
                }

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
                // On ignore la game en cours de modification
                if ($game->getId() === $this->gameId) {
                    continue;
                }

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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
            'gameId' => false
        ]);

        $resolver->setRequired([
            'gauntlet'
        ]);
    }
}
