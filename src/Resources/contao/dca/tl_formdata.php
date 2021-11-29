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
     * Table tl_formdata
     */

    $GLOBALS['TL_DCA']['tl_formdata'] = array
    (
        // Config
        'config'   => array
        (
            'dataContainer'      => 'Formdata',
            'enableVersioning'   => false,
            'closed'             => true,
            'notCopyable'        => true,
            'notEditable'        => false,
            'ctable'             => array('tl_formdata_details'),
            'doNotDeleteRecords' => true,
            'switchToEdit'       => false,
            'onload_callback'    => array
            (
                array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'loadDCA'),
                array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'checkPermission')
            ),
            'sql'                => array
            (
                'keys' => array
                (
                    'id'   => 'primary',
                    'form' => 'index'
                )
            )
        ),
        // List
        'list'     => array
        (
            'sorting'           => array
            (
                'mode'        => 2,
                'fields'      => array('date DESC'),
                'flag'        => 8,
                'panelLayout' => 'sort,filter;search,limit',

            ),
            'label'             => array
            (
                'fields'         => array('form', 'date', 'ip', 'alias'),
                'label_callback' => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getRowLabel')
            ),
            'global_operations' => array
            (
                'all' => array
                (
                    'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                    'href'       => 'act=select',
                    'class'      => 'header_edit_all',
                    'attributes' => 'onclick="Backend.getScrollOffset();"'
                )
            ),
            'operations'        => array
            (
                'edit'   => array
                (
                    'label' => &$GLOBALS['TL_LANG']['tl_formdata']['edit'],
                    'href'  => 'act=edit',
                    'icon'  => 'edit.gif'
                ),
                'delete' => array
                (
                    'label'      => &$GLOBALS['TL_LANG']['tl_formdata']['delete'],
                    'href'       => 'act=delete',
                    'icon'       => 'delete.gif',
                    'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
                ),
                'show'   => array
                (
                    'label' => &$GLOBALS['TL_LANG']['tl_formdata']['show'],
                    'href'  => 'act=show',
                    'icon'  => 'show.gif'
                ),
                'mail'   => array
                (
                    'label' => &$GLOBALS['TL_LANG']['tl_formdata']['mail'],
                    'href'  => 'act=mail',
                    'icon'  => 'bundles/efg/mail.gif'
                )
            )
        ),

        // Palettes
        'palettes' => array
        (
            'default' => 'form,ip,date,alias;published,confirmationSent,confirmationDate;be_notes;fd_member,fd_user,fd_member_group,fd_user_group'
        ),

        // Fields
        'fields'   => array
        (
            'id'               => array
            (
                'sql' => "int(10) unsigned NOT NULL auto_increment"
            ),
            'sorting'          => array
            (
                'sql' => "int(10) unsigned NOT NULL default '0'"
            ),
            'tstamp'           => array
            (
                'sql' => "int(10) unsigned NOT NULL default '0'"
            ),
            'form'             => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_formdata']['form'],
                'inputType' => 'text',
                'exclude'   => true,
                'search'    => true,
                'filter'    => true,
                'sorting'   => true,
                'sql'       => "varchar(255) NOT NULL default ''"
            ),
            'date'             => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_formdata']['date'],
                'inputType' => 'text',
                'exclude'   => true,
                'search'    => true,
                'sorting'   => true,
                'filter'    => true,
                'flag'      => 8,
                'eval'      => array('rgxp' => 'datim', 'tl_class' => 'w50'),
                'sql'       => "int(10) unsigned NOT NULL default '0'"
            ),
            'ip'               => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_formdata']['ip'],
                'inputType' => 'text',
                'exclude'   => true,
                'search'    => false,
                'sorting'   => false,
                'filter'    => false,
                'eval'      => array('tl_class' => 'w50'),
                'sql'       => "varchar(64) NOT NULL default ''"
            ),
            'fd_member'        => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_formdata']['fd_member'],
                'exclude'          => true,
                'inputType'        => 'select',
                'eval'             => array('mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'),
                'options_callback' => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getMembersSelect'),
                'sql'              => "int(10) unsigned NOT NULL default '0'"
            ),
            'fd_user'          => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_formdata']['fd_user'],
                'exclude'          => true,
                'inputType'        => 'select',
                'eval'             => array('mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'),
                'options_callback' => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getUsersSelect'),
                'sql'              => "int(10) unsigned NOT NULL default '0'"
            ),
            'fd_member_group'  => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_formdata']['fd_member_group'],
                'exclude'          => true,
                'inputType'        => 'select',
                'eval'             => array('mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'),
                'options_callback' => array(
                    'MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata',
                    'getMemberGroupsSelect'
                ),
                'sql'              => "int(10) unsigned NOT NULL default '0'"
            ),
            'fd_user_group'    => array
            (
                'label'            => &$GLOBALS['TL_LANG']['tl_formdata']['fd_user_group'],
                'exclude'          => true,
                'inputType'        => 'select',
                'eval'             => array('mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'),
                'options_callback' => array('MenAtWork\\EfgBundle\\Contao\\Table\\EfgFormdata', 'getUserGroupsSelect'),
                'sql'              => "int(10) unsigned NOT NULL default '0'"
            ),
            'published'        => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_formdata']['published'],
                'exclude'   => true,
                'filter'    => true,
                'inputType' => 'checkbox',
                'eval'      => array('tl_class' => 'clr'),
                // 'default'                 => '1',
                'sql'       => "char(1) NOT NULL default ''"
            ),
            'alias'            => array
            (
                'label'         => &$GLOBALS['TL_LANG']['tl_formdata']['alias'],
                'exclude'       => true,
                'search'        => true,
                'inputType'     => 'text',
                'eval'          => array(
                    'rgxp'              => 'alnum',
                    'unique'            => true,
                    'spaceToUnderscore' => true,
                    'maxlength'         => 64
                ),
                'save_callback' => array
                (
                    array('MenAtWork\\EfgBundle\\Contao\\Formdata', 'generateAlias')
                ),
                'sql'           => "varchar(64) NOT NULL default ''"
            ),
            'confirmationSent' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_formdata']['confirmationSent'],
                'exclude'   => true,
                'filter'    => true,
                'inputType' => 'checkbox',
                'eval'      => array('tl_class' => 'w50', 'doNotCopy' => true, 'isBoolean' => true),
                'sql'       => "char(1) NOT NULL default ''"
            ),
            'confirmationDate' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_formdata']['confirmationDate'],
                'exclude'   => true,
                'filter'    => true,
                'flag'      => 8,
                'inputType' => 'text',
                'eval'      => array('tl_class' => 'w50', 'rgxp' => 'datim'),
                'sql'       => "varchar(10) NOT NULL default ''"
            ),
            'be_notes'         => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_formdata']['be_notes'],
                'inputType' => 'textarea',
                'exclude'   => true,
                'search'    => true,
                'sorting'   => false,
                'filter'    => false,
                'eval'      => array('rows' => 5),
                'class'     => 'fd_notes',
                'sql'       => "text NULL"
            ),
        )
    );
