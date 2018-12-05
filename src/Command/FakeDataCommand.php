<?php

namespace App\Command;

use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Entity\GauntletType;
use App\Entity\User;
use App\Service\DeckService;
use App\Service\GameService;
use App\Service\GauntletService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FakeDataCommand extends Command
{
    protected static $defaultName = 'app:fake-data';

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var DeckService
     */
    private $deckService;

    /**
     * @var GauntletService
     */
    private $gauntletService;

    /**
     * @var GameService
     */
    private $gameService;

    /**
     * FakeDataCommand constructor.
     * @param string|null $name
     * @param EntityManagerInterface $manager
     * @param DeckService $deckService
     * @param GauntletService $gauntletService
     * @param GameService $gameService
     */
    public function __construct(?string $name = null, EntityManagerInterface $manager, DeckService $deckService, GauntletService $gauntletService, GameService $gameService)
    {
        $this->manager = $manager;
        $this->deckService = $deckService;
        $this->gauntletService = $gauntletService;
        $this->gameService = $gameService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $deckCodes = [
            'https://www.playartifact.com/d/ADCJRIS430Ec7sBF+QBBYUuuwIBQgaBTgkEFAIEDwMFWYpBZAE3AUZqASQBCwoMUmVkIEJsdWUgRmxhc2ggVi4x',
            'https://www.playartifact.com/d/ADCJdcKJ7kCyQSGSckFe12BgSHdAUKLpgGjAYEpARCBS1q+AUpgAgdHQ3JhenkgUmljaA__',
            'https://www.playartifact.com/d/ADCJbMPJLkCyQXDBFiNOF1+3QGCm6UBgSIBAkEBQx+1AohkAbcBRy9SIEFnZ3JvIFJhbXAg',
            'https://www.playartifact.com/d/ADCJRQUJLkCQ88FidYEOF223QFKm6IBqAGBEyMBjWEBkIahA0JSIFNtYXNoIENvbnRyb2wgMy4y',
            'https://www.playartifact.com/d/ADCJTkMPrgCUYHLBc0EcLsCBm8BlEaGmEFEX6IChESoATkBR1UgVG9rZW4gMi4w',
            'https://www.playartifact.com/d/ADCJU0HNrgCTorNBdYEeF273gGRSXABQaMBuQFJSKwBoAFUTVVSIExvY2s_',
            'https://www.playartifact.com/d/ADCJekONrgCUc8Eic8FOrsCswGIn52QUwOKagGHX7kBjiQ1MCBibHVlIGJsYWNr',
            'https://www.playartifact.com/d/ADCJVQZcbgC0AUPjMkEuF1Ctt0Bj2YBlIyLjgGoA6wBeAFHVSBDcmVlcCBPdmVyd2hlbG1pbmcgMi4w',
            'https://www.playartifact.com/d/ADCJY8QPrgCT5nCBM0FuF0CfN0BQkdUQVdNQQVSCwITHAosAUVXOwJidWRnZXQgcmVkIGdyZWVu',
            'https://www.playartifact.com/d/ADCJTYH-rgCBZEBVcMEeV1n3QGQvQGMhoaZRAoEswKwAXgBWkVSRyBCRw__',
            'https://www.playartifact.com/d/ADCJZ8Q4bkCBAjLBUKUPboCQwWHT0RSpAFpARGMUQS3AYsGWkIvRyBsYW5lIGNvbnRyb2w_',
            'https://www.playartifact.com/d/ADCJR4tdrgCyAUT0QSGOF1CI90BWaICgUaJlQIREmECbwFGawFJBkJsdWUgYmxhY2sgZ3JlZW4gcmFtcCByZW1vdmFsIGJ1ZGdldCAxIC0gcXVhZA__',
            'https://www.playartifact.com/d/ADCJbsf5bkCBMMFBphPZ7sCkVeEQXMBE4MDT1IBYAJUUoVVUmVkIGRlY2sgd2lucyAxIGJ1ZGdldCAxIC0gcXVhZA__',
            'https://www.playartifact.com/d/ADCJbAFJLkCSpHLBMsFeF1u3QGGQoqbQWwBRlVDUV0qAlwQXRpkZWNrMQ__',
            'https://www.playartifact.com/d/ADCJX8p8bgCBcUEpQFEDHhdZt0BCK8CSp4aSgy2ARWaQUVfVUZCbHVlIGJsYWNrIHBhc3NpdmUgZGFtYWdlIDEgYnVkZ2V0IC0gcXVhZA__',
            'https://www.playartifact.com/d/ADCJcEKMbgCzQSRwQVVqrsCRkp-AUyLDUFLRwsZbwGEG5FlAlVHIE1pbmlvbnM_',
            'https://www.playartifact.com/d/ADCJdse+rgCBA5FxwWUPboCQ1iHUAcTXAQUAQYBBBIICQFsAQlFCxZiAgdbUmVkLCBHcmVlbiwgQmx1ZV0gV3l2ZXJuIGRlY2s_',
            'https://www.playartifact.com/d/ADCJY0SNrgC4AEESc8Fg3hdZd0BcAKIKAFBUxwHA2wBiEefQgVyAY5CbHVlL0JsYWNrIENvbnRyb2w_',
            'https://www.playartifact.com/d/ADCJdUQY30rvAHRBMMFiHhdgmzdAUZaCUIDdAEaCgQcUkMhARAEAloQaAFCbGFjay9SZWQgQnVkZ2V0',
            'https://www.playartifact.com/d/ADCJbIoJLkCywRQywWLfroCTkKKm3MBFYKBBAF+ATUBQpsPLwEIW0JSdV0gVmluS2Vsc2llcidzIEJsb29keSBLYW5uYSBMYWRkZXJzLg__',
            'https://www.playartifact.com/d/ADCJREQsbgCHMIF0QRFs7gCcQJDUEFcBjUBTwJbB0sJKwFHV1QeBhRSZWQtQmx1ZSBNaW5pb25z',
            'https://www.playartifact.com/d/ADCJT4fNrgCyARdisMFdrsCowFBnoGGhVCJghFRogKEiKQBhZ+UQYZCdWRnZXQgYmx1ZSBncmVlbiBkcmF3IDEgLSBxdWFk',
            'https://www.playartifact.com/d/ADCJUsvL7kCxQRHygWDqrsCEIGeRaEBsgGGhKgCRGQBYAERSSBXYW50IFRvIFBsYXkgVG9rZW5zIEJ1dCBJIEFtIEJhZCBBdCBUaGlzIEdhbWU_',
            'https://www.playartifact.com/d/ADCJdUmJLkCSsEEwQWlAa67AoqbAaUBV42BBEEzA51PaQEGCFtSdWddIFZpbktlbHNpZXIncyBBbGwtU3RhcnMgVHJpLWNvbG9y',
            'https://www.playartifact.com/d/ADCJXogJ7kCzQRLyQWEvroCjb8Bjk1YQUFDH7YBm0ysAltCbGFjayxHcmVlbl0gTWFuYSBHb2xkICBDb250cm9s',
            'https://www.playartifact.com/d/ADCJbYVNrgCWcwEygWMeV2x3QFvASYBnEMFF64CqAEBggWOkUtNb25vVS1Ub2tlblRodW5kZXJWLjI_',
            'https://www.playartifact.com/d/ADCJbAGJLkCSpHLBMsFeF1u3QGGQoqbQWwBRlVDUV0qAlwQXRpEZWNrIDI_',
            'https://www.playartifact.com/d/ADCJfQGI31xvAHLBMMFhrhdQiPdAUOWQg8xAQEFCQkOBA4BMQIFUQQGRgoTJQFkcmFmdDI_',
            'https://www.playartifact.com/d/ADCJRsntrgC0QVPxQQEtb0CXwqTkBMDSqoBh4ZZeQGBTVVCIFBheWRheSBEZWNrIFRlY2ggW3R3aXRjaC50di9hcnRzZWxfXQ__',
            'https://www.playartifact.com/d/ADCJUAGJX35uwEESYnjAQW4XYIk3QECAwIBBQEEBglICmcBAigBKwEGdAErAQ5LVAtoZXloZXk_',
            'https://www.playartifact.com/d/ADCJeYMNrgCTssE4gEFhLhdoN4Bm0FGZQGfElFhAkF1AUakAUpSZWQgQmx1ZSBGdW4_',
            'https://www.playartifact.com/d/ADCJWogJX3xuwEE5QEFUYS+ugJcsAFzAUFBQx+2AZGoAUKGvgFbQmxhY2ssQmx1ZV0gTWFuYSBDcmVlcCAgQ29udHJvbA__',
            'https://www.playartifact.com/d/ADCJWMF9rgCBRhNi9AEOl1w3QGOTmEBHRkCQQQLDBgpAU2qAQVGAQOVFUl6emV0',
            'https://www.playartifact.com/d/ADCJZQKJLkCzATSBUaNcLsCSJulAUGkAYEEX2ABvQGUULcBQkdSIE1vZ3dhaQ__',
            'https://www.playartifact.com/d/ADCJYcWJ7kCwgXSBESSrb0CiIqJgYudowGDrAGPH1UvQiBlY29uIHRvd2VyIGRlZmVuY2U_',
            'https://www.playartifact.com/d/ADCJVMOJ7kCgsgFygRafroCrwKRX4ETB1WUagGHoAFUcgFBVG93ZXIgRGVmZmVuY2U_',
            'https://www.playartifact.com/d/ADCJY8HJLkCSoPKBdYEeF273gGaEl6BBQ6xAoFIoAOGR0FVUiBjdHJs',
            'https://www.playartifact.com/d/ADCJVsGJLkCSsIExAWIq7sCS5GMpgGXTYFLGhGnAbMBawJiYXR0bGU_',
            'https://www.playartifact.com/d/ADCJcgJ8bgCBB5BhNEFuF0CJd0BAQsFS0QaAZ8Miw0BJAEKAbYBhFiBAomIZAGLbWluaW9uIHYx',
            'https://www.playartifact.com/d/ADCJXQIfrgCBswEmM0FYLsCkIhbkpOBpAGBRCEBuwIzAkhXQVIhISEgMg__',
            'https://www.playartifact.com/d/ADCJY8NJX39uwEERZjDBbpdo90Bj6kCKQFRDkGgAYOxAUYEFrgBR2hldHRvIFNuaXBlcg__',
            'https://www.playartifact.com/d/ADCJZ4PNrgCUc8EidIFeF293wFfSUETIwEDiiIBiIeGWVWkAVVCIEVjb24vQ29udHJvbA__',
        ];

        $gauntletsResult = [
            '0 1 1 0',
            '1 1 1 0 1 1',
            '0 1 1 1 1 0',
            '1 1 1 1 1',
            '0 0',
            '1 0 1 1 1 1',
            '1 1 1 0 d',
            '1 0 c',
            '1 1 0 1 1 0',
            '0 1 1 1 1 1'
        ];

        $gauntletTypes = [
            'Expert - Constructed',
            'Expert - Phantom Draft',
            'Expert - Keeper Draft',
            'Casual - Constructed',
            'Casual - Phantom Draft'
        ];

        $user = $this->manager->getRepository(User::class)
            ->findOneBy([
                'email' => 'jerho@live.fr'
            ]);

        for ($i = 0; $i < 100; $i++) {

            $playedAt = new \DateTime();
            $int = new \DateInterval("P".rand(0,6)."D");
            $playedAt->sub($int);

            $gauntletTypeIndex = rand(0, count($gauntletTypes) - 1);
            $gauntletTypeName = $gauntletTypes[$gauntletTypeIndex];

            $gauntletType = $this->manager->getRepository(GauntletType::class)
                ->findOneBy(['name' => $gauntletTypeName]);

            $gauntlet = new Gauntlet();
            $gauntlet->setUser($user)
                ->setPlayedAt($playedAt)
                ->setType($gauntletType);

            $deckCodeIndex = rand(0, count($deckCodes) - 1);
            $deckCode = $deckCodes[$deckCodeIndex];
            $deck = $this->deckService->createDeckFromCode($deckCode);
            $gauntlet->setDeck($deck);
            $this->gauntletService->create($gauntlet);

            $gauntletResultIndex = rand (0, count($gauntletsResult) - 1);
            $gauntletResult = $gauntletsResult[$gauntletResultIndex];
            $gamesResult = explode(' ', $gauntletResult);

            $playedAt = $gauntlet->getPlayedAt();
            foreach ($gamesResult as $gameResult) {
                $status = Game::STATUS_WIN;

                if ($gameResult === '0') {
                    $status = Game::STATUS_LOSE;
                } else if ($gameResult === 'c') {
                    $this->gauntletService->concede($gauntlet);
                    break;
                } else if ($gameResult === 'd') {
                    $status = Game::STATUS_DRAW;
                }


                $int = new \DateInterval('PT' . rand(5, 35) . 'M');
                $playedAt->add($int);

                $game = new Game();
                $game->setGauntlet($gauntlet)
                    ->setPlayedAt($playedAt)
                    ->setStatus($status);

                $this->gameService->create($game);
            }

        }

        $io->success('Fake data imported!');
    }
}
