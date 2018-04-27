
<?php

/**
* Contao Open Source CMS formdata
*
* Copyright (c) 2005-2014 Leo Feyer
*
* @package   Efg
* @author    Thomas Kuhn <mail@th-kuhn.de>
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 * @copyright Thomas Kuhn 2007-2014
 */

// This file is created when saving a form in form generator

// last created on 2018-04-04 12:45:08 by saving form ""



/**
 * Table tl_formdata defined by form ""
 */
$GLOBALS['TL_DCA']['tl_formdata'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => 'Formdata',
		'ctable'                      => array('tl_formdata_details'),
		'closed'                      => true,
		'notEditable'                 => false,
		'enableVersioning'            => false,
		'doNotCopyRecords'            => true,
		'doNotDeleteRecords'          => true,
		'switchToEdit'                => true
	),
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('date DESC'),
			'flag'                    => 8,
			'panelLayout'             => 'filter;search,sort,limit',
		),
		'label' => array
		(
			'fields'                  => array('date', 'form', 'alias', 'be_notes' , 'salutation', 'name', 'email', 'message'),
			'label_callback'          => array('tl_fd_feedback','getRowLabel')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_formdata']['edit'],
				'href'                => 'act=edit',
				'button_callback'     => array('MenAtWork\\EfgBundle\\Contao\\FormdataBackend', 'callbackEditButton'),
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_formdata']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_formdata']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
		'default'                     => 'form,alias,date,ip,published,sorting;{confirmation_legend},confirmationSent,confirmationDate;{fdNotes_legend:hide},be_notes;{fdOwner_legend:hide},fd_member,fd_user,fd_member_group,fd_user_group;{fdDetails_legend},salutation,name,email,message'
	),

	// Base fields in table tl_formdata
	'fields' => array
	(
		'form' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['form'],
			'inputType'               => 'select',
			'exclude'                 => false,
			'search'                  => true,
			'filter'                  => true,
			'sorting'                 => true,
			'options_callback'        => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getFormsSelect'),
			'eval'                    => array('chosen' => true, 'tl_class'=> 'w50')
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['date'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 8,
			'eval'                    => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
		),
		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['ip'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => false,
			'filter'                  => false,
			'eval'                    => array('tl_class'=>'w50'),
		),
		'fd_member' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['fd_member'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'eval'                    => array('chosen' => true, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class'=>'w50'),
			'options_callback'        => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getMembersSelect'),
		),
		'fd_user' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['fd_user'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'eval'                    => array('chosen' => true, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class'=>'w50'),
			'options_callback'        => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getUsersSelect'),
		),
		'fd_member_group' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['fd_member_group'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'eval'                    => array('chosen' => true, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class'=>'w50'),
			'options_callback'        => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getMemberGroupsSelect'),
		),
		'fd_user_group' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['fd_user_group'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'eval'                    => array('chosen' => true, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class'=>'w50'),
			'options_callback'        => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getUserGroupsSelect'),
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12 cbx clr'),
			// 'default'                 => '1'
		),
		'sorting' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['sorting'],
			'exclude'                 => true,
			'filter'                  => false,
			'inputType'               => 'text',
			'eval'                    => array('rgxp' => 'digit', 'maxlength' => 10, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_formdata', 'generateAlias')
			)
		),
		'confirmationSent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['confirmationSent'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50', 'doNotCopy'=>true, 'isBoolean'=>true)
		),
		'confirmationDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['confirmationDate'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker' => true, 'tl_class'=>'w50 wizard')
		),
		'be_notes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['be_notes'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => false,
			'filter'                  => false,
			'eval'                    => array('rte' => 'tinyMCE', 'cols' => 80,'rows' => 5, 'style' => 'height: 80px'),
		),
		'import_source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_formdata']['import_source'],
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'csv', 'class'=>'mandatory')
		)
	),
	'tl_formdata' => array
	(
		'baseFields'                 => array('id','sorting','tstamp','form','ip','date','fd_member','fd_user','fd_member_group','fd_user_group','published','alias','be_notes','confirmationSent','confirmationDate'),
		'detailFields'               => array('salutation','name','email','message'),
	)
);

// Detail fields in table tl_formdata_details

// 'salutation'
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['label'] = array('Salutation', '[salutation] Salutation');
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['inputType'] = 'select';
    $GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['formfieldType'] = 'select';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['exclude'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['search'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['sorting'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['filter'] = true;
	
	$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['options']['mrs'] = 'Mrs.';
	
	$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['options']['mr'] = 'Mr.';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['eval']['chosen'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['ff_id'] = 15;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['f_id'] = 5;

// 'name'
$GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['label'] = array('Name', '[name] Name');
$GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['inputType'] = 'text';
    $GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['formfieldType'] = 'text';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['exclude'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['search'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['sorting'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['filter'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['ff_id'] = 16;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['f_id'] = 5;

// 'email'
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['label'] = array('E-Mail', '[email] E-Mail');
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['inputType'] = 'text';
    $GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['formfieldType'] = 'text';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['exclude'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['search'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['sorting'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['filter'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['eval']['rgxp'] = 'email';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['ff_id'] = 18;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['f_id'] = 5;

// 'message'
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['label'] = array('Your Message', '[message] Your Message');
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['inputType'] = 'textarea';
    $GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['formfieldType'] = 'textarea';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['exclude'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['search'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['sorting'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['filter'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['eval']['cols'] = 40;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['eval']['rows'] = 4;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['ff_id'] = 19;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['f_id'] = 5;

/**
 * Class tl_fd_
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Thomas Kuhn 2007-2014
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    Efg
 */
class tl_fd_feedback extends \Backend
{

	/**
	 * Database result
	 * @var array
	 */
	protected $arrData = null;

	public function __construct()
	{
		parent::__construct();
	}


	/*
	* Create list label for formdata item
	* This can be used to customize the backend list view for formdata
	*/
	public function getRowLabel($arrRow)
	{
		$strRowLabel = '';

		$strKey = 'unpublished';

		$strRowLabel .= '<div class="fd_wrap">';
		$strRowLabel .= '<div class="fd_head">' . date($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']) . '<span>[' . $arrRow['form'] . ']</span><span>' . $arrRow['alias'] . '</span></div>';
		$strRowLabel .= '<div class="fd_notes">' . $arrRow['be_notes'] . '</div>';
		$strRowLabel .= '<div class="mark_links">';
			if (strlen($arrRow['salutation']))
		{
			$strRowLabel .= '<div class="fd_row field_salutation">';
			$strRowLabel .= '<div class="fd_label">' . $GLOBALS['TL_DCA']['tl_formdata']['fields']['salutation']['label'][0] . ': </div>';
			$strRowLabel .= '<div class="fd_value">' . $arrRow['salutation'] . ' </div>';
			$strRowLabel .= '</div>';
		}
			if (strlen($arrRow['name']))
		{
			$strRowLabel .= '<div class="fd_row field_name">';
			$strRowLabel .= '<div class="fd_label">' . $GLOBALS['TL_DCA']['tl_formdata']['fields']['name']['label'][0] . ': </div>';
			$strRowLabel .= '<div class="fd_value">' . $arrRow['name'] . ' </div>';
			$strRowLabel .= '</div>';
		}
			if (strlen($arrRow['email']))
		{
			$strRowLabel .= '<div class="fd_row field_email">';
			$strRowLabel .= '<div class="fd_label">' . $GLOBALS['TL_DCA']['tl_formdata']['fields']['email']['label'][0] . ': </div>';
			$strRowLabel .= '<div class="fd_value">' . $arrRow['email'] . ' </div>';
			$strRowLabel .= '</div>';
		}
			if (strlen($arrRow['message']))
		{
			$strRowLabel .= '<div class="fd_row field_message">';
			$strRowLabel .= '<div class="fd_label">' . $GLOBALS['TL_DCA']['tl_formdata']['fields']['message']['label'][0] . ': </div>';
			$strRowLabel .= '<div class="fd_value">' . $arrRow['message'] . ' </div>';
			$strRowLabel .= '</div>';
		}
		$strRowLabel .= '</div></div>';

		return $strRowLabel;

	}
}
