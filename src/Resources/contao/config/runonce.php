<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   Efg
 * @author    Thomas Kuhn <mail@th-kuhn.de>
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 * @copyright Thomas Kuhn 2007-2014
 *
 * @author    Stefan Heimes <heimes@men-at-work.de>
 */

class ContaoEfgBundleRunonce
{
    public function run()
    {
        if (!\is_dir(\TL_ROOT . '/system/modules/contao-efg-bundle/dca')) {
            $folder = new \Folder('system/modules/contao-efg-bundle/dca');
            $folder->protect();
        }

        if (!\is_dir(\TL_ROOT . '/system/modules/contao-efg-bundle/languages')) {
            $folder = new \Folder('system/modules/contao-efg-bundle/languages');
            $folder->protect();
        }
    }
}

$runner = new \ContaoEfgBundleRunonce();
$runner->run();
