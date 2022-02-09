<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
            new TwigFunction('percentage', [$this, 'percentage']),
        ];
    }

    public function pluralize($count, string $singular, ?string $plural = null)
    {   
        
        if($plural == null){
            $plural = $singular . 's';
        }
        $str = $count > 1 ? $plural : $singular;
        return $count . ' ' . $str;
    }

    public function percentage(int $restTime)
    {
        $percent = floor($restTime * 100 / 7);
       
        if($percent > 100){
            $percent = 100;
        }

        return $percent . '%';
    }
}
