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
 * @author    Andreas Dziemba <dziemba@men-at-work.de>
 */

/**
* to fix height of style class w50 in backend
*/
if (TL_MODE == 'BE')
{
	$GLOBALS['TL_CSS'][] = 'bundles/efg/w50_fix.css';
}


/**
 * Use class ExtendedForm
 */
$GLOBALS['FE_MOD']['application']['form'] = 'MenAtWork\\EfgBundle\\Contao\\Forms\\ExtendedForm';
$GLOBALS['TL_CTE']['includes']['form'] = 'MenAtWork\\EfgBundle\\Contao\\Forms\\ExtendedForm';


/**
 * -------------------------------------------------------------------------
 * BACK END MODULES
 * -------------------------------------------------------------------------
 */

$GLOBALS['BE_MOD']['content'] = array_merge($GLOBALS['BE_MOD']['content'], array(
	'formdata' => array(
		'icon'     => 'bundles/efg/formdata.gif',
		'callback' => 'MenAtWork\\EfgBundle\\Contao\\Modules\\ModuleFormListing',
		'tables'     => array('tl_formdata', 'tl_formdata_details', 'fd_feedback'),
	),
));

//array_insert($GLOBALS['BE_MOD'], 1, array('formdata' => array()));

$GLOBALS['BE_MOD']['formdata']['feedback'] = array
(
	'tables'     => array('tl_formdata', 'tl_formdata_details'),
	'stylesheet' => 'bundles/efg/style.css'
);

foreach (glob(__DIR__ . '/../dca/fd_*.php') as $file) {

    $name = basename($file, '.php');

	if ($name == 'fd_feedback') {
		continue;
	}

	$GLOBALS['BE_MOD']['formdata'][$name] = array
	(
		'tables'     => array('tl_formdata', 'tl_formdata_details'),
		'import'     => array('MenAtWork\\EfgBundle\\Contao\\FormdataBackend', 'importCsv'),
		'stylesheet' => 'bundles/efg/style.css'
	);
}

/**
 * -------------------------------------------------------------------------
 * FRONT END MODULES
 * -------------------------------------------------------------------------
 */

array_insert($GLOBALS['FE_MOD']['application'], count($GLOBALS['FE_MOD']['application']), array
(
	'formdatalisting' => 'MenAtWork\\EfgBundle\\Contao\\Modules\\ModuleFormdataListing'
));


/**
 * -------------------------------------------------------------------------
 * HOOKS
 * -------------------------------------------------------------------------
 */

$GLOBALS['TL_HOOKS']['processFormData'][]       = array('MenAtWork\\EfgBundle\\Contao\\FormdataProcessor', 'processSubmittedData');
$GLOBALS['TL_HOOKS']['outputFrontendTemplate'][] = array('MenAtWork\\EfgBundle\\Contao\\FormdataProcessor', 'processConfirmationContent');
$GLOBALS['TL_HOOKS']['listComments'][]          = array('MenAtWork\\EfgBundle\\Contao\\FormdataComments', 'listComments');
$GLOBALS['TL_HOOKS']['getSearchablePages'][]    = array('MenAtWork\\EfgBundle\\Contao\\Formdata', 'getSearchablePages');
$GLOBALS['TL_HOOKS']['executePostActions'][]    = array('MenAtWork\\EfgBundle\\Contao\\Formdata', 'executePostActions');
$GLOBALS['TL_HOOKS']['getUserNavigation'][]     = array('MenAtWork\\EfgBundle\\Contao\\Formdata', 'getUserNavigation');
