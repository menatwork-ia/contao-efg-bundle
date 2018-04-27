<?php
/**
 * @copyright  MEN AT WORK 2018
 * @package    MenAtWork\EfgBundle
 * @license    GNU/LGPL
 */

namespace MenAtWork\EfgBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use MenAtWork\EfgBundle\EfgBundle;

/**
 * Class Plugin
 *
 * @package MenAtWork\EfgBundle\ContaoManager
 */
class Plugin implements BundlePluginInterface
{

    /**
     * @param ParserInterface $parser
     *
     * @return array|ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(EfgBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
                ->setReplace(['contao-legacy/efg'])
                ->setReplace(['byteworks/efg']),
        ];
    }
}