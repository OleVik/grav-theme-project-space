<?php
namespace Grav\Theme;

class InAnyArrayTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'inArrayAny';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('in_array_any', [$this, 'inArrayAny'])
        ];
    }

    public function inArrayAny($needles, $haystack)
    {
        return !!array_intersect($needles, $haystack);
    }
}
