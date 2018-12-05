<?php
/**
 * Created by PhpStorm.
 * User: prestasic10
 * Date: 30/11/2018
 * Time: 16:30
 */

namespace App\Twig;


use App\Entity\Gauntlet;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GauntletStatusExtension extends AbstractExtension
{
    /**
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('gauntletStatusBadge', [$this, 'transform'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        ];
    }

    public function transform(\Twig_Environment $environment, $status)
    {
        if ($status === Gauntlet::STATUS_FINISH) {
            $cssClass = 'success';
            $label = 'label.gauntlet_finish';
        } else if($status === Gauntlet::STATUS_CURRENT) {
            $cssClass = 'warning';
            $label = 'label.gauntlet_current';
        } else {
            $cssClass = 'secondary';
            $label = 'label.gauntlet_conceded';
        }

        return $environment->render('extensions/gauntlet/status_badge.html.twig', [
            'label' => $label,
            'cssClass' => $cssClass
        ]);
    }
}