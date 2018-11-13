<?php

namespace Grav\Theme;

class UrlDecoderTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'UrlDecoder';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('url_decode', [$this, 'UrlDecoderFilter'])
        ];
    }

    public function UrlDecoderFilter($url)
    {
        return urldecode($url);
    }
}
