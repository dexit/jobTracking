<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PluralFilter extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('plural', [$this, 'plural']),
        ];
    }

    public function plural($count, $singular, $plural)
    {
        return $count > 1 ? $plural : $singular;
    }
}
