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
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['formdatalisting'] = '{title_legend},name,headline,type;{config_legend},list_formdata,list_where,list_sort,perPage,list_fields,list_info;{efgSearch_legend},list_search,efg_list_searchtype;{protected_legend:hide},efg_list_access,efg_fe_edit_access,efg_fe_delete_access,efg_fe_export_access;{comments_legend:hide},efg_com_allow_comments;{template_legend:hide},list_layout,list_info_layout;{expert_legend:hide},efg_DetailsKey,efg_iconfolder,efg_fe_keep_id,efg_fe_no_formatted_mail,efg_fe_no_confirmation_mail,align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['fields']['type']['load_callback'][] = array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgModule', 'onloadModuleType');

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'efg_com_allow_comments';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['efg_com_allow_comments'] = 'com_moderate,com_bbcode,com_requireLogin,com_disableCaptcha,efg_com_per_page,com_order,com_template,efg_com_notify';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['list_formdata'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_formdata'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgModule', 'getFormdataTables'),
	'eval'                    => array('mandatory' => true, 'maxlength' => 64, 'includeBlankOption' => true, 'submitOnChange' => true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_where'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_where'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('preserveTags'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_sort'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_sort'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_fields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_fields'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50" style="height:auto'),
	'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_list_searchtype'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_list_searchtype'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('dropdown', 'singlefield', 'multiplefields'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_list_searchtype'],
	'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true, 'helpwizard'=>true,  'tl_class'=>'w50" style="height:auto'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_search'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_search'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50" style="height:auto'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_info'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_info'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50" style="height:auto'),
	'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_list_access'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_list_access'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('public','groupmembers','member'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_list_access'],
	'eval'                    => array('mandatory'=>true, 'includeBlankOption' => true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_edit_access'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_edit_access'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('none','public','groupmembers','member'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_fe_edit_access'],
	'eval'                    => array('mandatory'=>true, 'includeBlankOption' => true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_delete_access'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_delete_access'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('none','public','groupmembers','member'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_fe_delete_access'],
	'eval'                    => array('mandatory'=>true, 'includeBlankOption' => true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_export_access'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_export_access'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('none','public','groupmembers','member'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_fe_export_access'],
	'eval'                    => array('mandatory'=>true, 'includeBlankOption' => true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_DetailsKey'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_DetailsKey'],
	'exclude'                 => false,
	'filter'                  => false,
	'inputType'               => 'text',
	'eval'                    => array('default' => 'details', 'maxlength'=>64, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_iconfolder'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_iconfolder'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'trailingSlash'=>false, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_keep_id'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_keep_id'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'clr cbx'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_no_formatted_mail'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_no_formatted_mail'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'clr cbx'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_no_confirmation_mail'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_no_confirmation_mail'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'clr cbx'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_com_allow_comments'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_com_allow_comments'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_moderate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_moderate'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_bbcode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_bbcode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_requireLogin'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_requireLogin'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_disableCaptcha'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_disableCaptcha'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_com_per_page'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_com_per_page'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
	'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_order'],
	'default'                 => 'ascending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_template'],
	'default'                 => 'com_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgModule', 'getCommentTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_com_notify'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_com_notify'],
	'default'                 => 'notify_admin',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('notify_admin', 'notify_author', 'notify_both'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class' => 'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);



/**
 * Class tl_module_efg
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Thomas Kuhn 2007-2014
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */

