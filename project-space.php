<?php

namespace Grav\Theme;

use Grav\Common\Grav;
use Grav\Common\Theme;
use Grav\Plugin\Taxonomylist;
use Grav\Theme\ProjectSpace\InAnyArrayTwigExtension;
use Grav\Theme\ProjectSpace\TruncateTwigExtension;
use Grav\Theme\ProjectSpace\UrlDecoderTwigExtension;

/**
 * Project Space Theme
 *
 * Class ProjectSpace
 *
 * @category Extensions
 * @package  Grav\Theme
 * @author   Ole Vik <git@olevik.net>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/OleVik/grav-theme-project-space
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
     * Get list of allowed categories from site.yaml
     *
     * @return array
     */
    public static function colors()
    {
        $options = Grav::instance()['config']->get('themes.project-space.colors');
        $colors = array();
        foreach ($options as $color) {
            $colors[$color['name']] = $color['name'];
        }
        return $colors;
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
        $colors = '';
        foreach (Grav::instance()['config']->get('themes.project-space.colors') as $color) {
            if (!array_key_exists('name', $color) || !array_key_exists('value', $color)) {
                continue;
            }
            $colors .= 'select[name="data[header][color]"] + div.selectize-control.single .option[data-value="' . $color['name'] . '"]:after,';
            $colors .= 'select[name="data[header][color]"] + div.selectize-control.single .item[data-value="' . $color['name'] . '"]:after {';
            $colors .= 'color:' . $color['value'] . '!important;';
            $colors .= '}';
        }
        $this->grav['assets']->addInlineCss($colors);
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
        $location = rtrim($this->grav['page']->url(true), '/') . $params;
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
        $this->grav['twig']->twig->addExtension(new UrlDecoderTwigExtension());
        $this->grav['twig']->twig->addExtension(new TruncateTwigExtension());
        $this->grav['twig']->twig->addExtension(new InAnyArrayTwigExtension());
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
