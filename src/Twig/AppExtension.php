<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('notifications', [AppRuntime::class, 'sellerNotification']),
            new TwigFunction('nbClients', [AppRuntime::class, 'sellerNbClients']),
            new TwigFunction('lastWeekOfYear', [AppRuntime::class, 'lastWeekOfYear']),
            new TwigFunction('months', [AppRuntime::class, 'months']),
        ];
    }


}