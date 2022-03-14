<?php

namespace Grav\Theme;

use Grav\Common\Grav;
use Grav\Common\Theme;
use Grav\Plugin\Taxonomylist;

/**
 * Project Space Class
 */
class ProjectSpace extends Theme
{
    /**
     * Page parameter-keys
     *
     * @var array
     */
    public $params = [
        'display',
        'start',
        'end',
        'sort-by',
        'sort-dir',
        'limit',
        'category',
        'color',
        'tag'
    ];

    /**
     * Initialize plugin and subsequent events
     * 
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onThemeInitialized' => ['onThemeInitialized', 0],
            'onTwigExtensions' => ['onTwigExtensions', 0]
        ];
    }

    /**
     * Get list of allowed categories from site.yaml
     *
     * @return array
     */
    public static function categories()
    {
        $options = Grav::instance()['config']->get('themes.project-space.categories');
        $categories = array();
        foreach ($options as $category) {
            $categories[$category] = $category;
        }
        return $categories;
    }

    /**
     * Get list of tags
     *
     * @return array
     */
    public static function tags()
    {
        $taxonomylist = new Taxonomylist();
        $tags = array();
        if (!empty($taxonomylist->get()['tag'])) {
            foreach (array_keys($taxonomylist->get()['tag']) as $tag) {
                array_push($tags, array('text' => $tag, 'value' => $tag));
            }
        }
        return $tags;
    }

    /**
     * Register events and route with Grav
     * 
     * @return void
     */
    public function onThemeInitialized()
    {
        /* Check if Admin-interface */
        if ($this->isAdmin()) {
            $this->enable(
                [
                    'onPageInitialized' => ['onAdminPageInitialized', 0]
                ]
            );
        } else {
            $this->enable(
                [
                    'onPageInitialized' => ['onPageInitialized', 0]
                ]
            );
        }
    }

    /**
     * Push styles to Admin-plugin via Assets Manager
     * 
     * @return void
     */
    public function onAdminPageInitialized()
    {
        $this->grav['assets']->addCss('theme://src/admin.css', 1);
    }

    /**
     * Logic for handling tools-menu
     * 
     * @return void
     */
    public function onPageInitialized()
    {
        $output = [];
        $sep = $this->grav['config']->get('system.param_sep');
        foreach ($this->params as $param) {
            if (!empty($_GET[$param])) {
                if (in_array($param, array_keys($_GET))) {
                    if (is_array($_GET[$param])) {
                        $items = [];
                        foreach ($_GET[$param] as $item) {
                            $items[] = urlencode($item);
                        }
                        $output[] = $param . $sep . implode(',', array_unique($items));
                    } else {
                        $output[] = $param . $sep . $_GET[$param];
                    }
                }
            }
        }
        $params = '/' . implode('/', $output);
        if ($this->grav['config']->get('system.home.alias') == '/' . $this->grav['page']->slug()) {
            $location = '/' . $this->grav['page']->slug() . $params;
        } else {
            $location = $this->grav['page']->url(true) . $params;
        }
        if (isset($_GET['redirect']) && !empty($location)) {
            header('Location: ' . $location, true, 307);
            exit();
        }
    }

    /**
     * Add Twig Extension
     *
     * @return void
     */
    public function onTwigExtensions()
    {
        include_once __DIR__ . '/twig/UrlDecoderTwigExtension.php';
        $this->grav['twig']->twig->addExtension(new UrlDecoderTwigExtension());
        include_once __DIR__ . '/twig/InAnyArrayTwigExtension.php';
        $this->grav['twig']->twig->addExtension(new InAnyArrayTwigExtension());
        include_once __DIR__ . '/twig/TruncateExtension.php';
        $this->grav['twig']->twig->addExtension(new TruncateExtension());
    }

    /**
     * Check if any needles exist in haystack
     *
     * @param array $needles  Values to search for
     * @param array $haystack Values to match against
     * 
     * @return boolean
     * 
     * @see https://stackoverflow.com/a/11040612/603387
     */
    public function inArrayAny($needles, $haystack)
    {
        return !!array_intersect($needles, $haystack);
    }

    /**
     * Composer autoload.
     *
     * @return \Composer\Autoload\ClassLoader
     */
    public function autoload(): \Composer\Autoload\ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }
}
