<?php

namespace Grav\Theme\ProjectSpace;

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

    /**
     * Look for needles in haystacks
     *
     * @param array $needles Array of items to look for
     * @param array $haystack Arrays of items to look in
     *
     * @return bool True if any items match, false otherwise
     */
    public function inArrayAny(array $needles, array $haystack)
    {
        return (bool) array_intersect($needles, $haystack);
    }
}
