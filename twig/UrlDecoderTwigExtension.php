<?php

namespace Grav\Theme\ProjectSpace;

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

    /**
     * Decode an URL-encoded string
     *
     * @param string $url String to be decoded
     *
     * @return string
     */
    public function UrlDecoderFilter(string $url)
    {
        return urldecode($url);
    }
}
