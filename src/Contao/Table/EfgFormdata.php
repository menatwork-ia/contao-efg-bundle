<?php
/**
 * @author Andreas Dziemba <dziemba@men-at-work.de>
 */

namespace MenAtWork\EfgBundle\Contao\Table;

/**
 * Class EfgFormdata
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Thomas Kuhn 2007-2014
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class EfgFormdata extends \Backend
{

    /**
     * Database result
     *
     * @var array
     */
    protected $arrData = null;


    private function checkFolders()
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

    /**
     * Loads $GLOBALS['TL_DCA']['tl_formdata'] or overwrites with form specific DCA config
     *
     * @param object $dca        DataContainer
     * @param string $varFormKey specific form if called from
     *                           MenAtWork\\EfgBundle\\Contao\\Module\\ModuleFormdataListing
     */
    function loadDCA(\DataContainer $dca, $varFormKey = '')
    {
        $this->checkFolders();

        //$strModule = 'efg';
        //$strName = 'feedback';
        //$strFileName = 'tl_formdata';

        // TODO: remove this fix if xdependentcalendarfield is available for Contao 3 and config is fixed
        // Fix config for xdependentcalendarfields
        // xdependentcalendarfields/config/config.php registers 'FormTextField', which is a front end widget)
        if (!empty($GLOBALS['BE_FFL']['xdependentcalendarfields']) && $GLOBALS['BE_FFL']['xdependentcalendarfields'] == 'FormTextField') {
            $GLOBALS['BE_FFL']['xdependentcalendarfields'] = 'TextField';
        }

        // Register backend widget for 'cm_alernativeforms'
        $GLOBALS['BE_FFL']['cm_alternative'] = 'SelectMenu';

        if ($varFormKey != '' && is_string($varFormKey)) {
            $strFileName = $varFormKey;
        } else {
            $strFileName = (\Input::get('do') == 'feedback' ? 'fd_feedback' : \Input::get('do'));
        }

        if ($varFormKey != '' && is_string($varFormKey)) {
            if ($varFormKey != 'tl_formdata') {
                if (array_key_exists($varFormKey, $GLOBALS['BE_MOD']['formdata'])) {

                    $strFile = sprintf('%s/system/modules/contao-efg-bundle/dca/%s.php', TL_ROOT, $strFileName);

                    if (file_exists($strFile)) {
                        $strName = $varFormKey;
                        include_once $strFile;

                        // Replace standard dca tl_formdata by form-dependent dca
                        if (is_array($GLOBALS['TL_DCA'][$strName]) && count($GLOBALS['TL_DCA'][$strName]) > 0) {
                            $GLOBALS['TL_DCA']['tl_formdata'] = $GLOBALS['TL_DCA'][$strName];
                            unset($GLOBALS['TL_DCA'][$strName]);
                        }

                        // HOOK: allow to load custom settings
                        if (isset($GLOBALS['TL_HOOKS']['loadDataContainer']) && is_array($GLOBALS['TL_HOOKS']['loadDataContainer'])) {
                            foreach ($GLOBALS['TL_HOOKS']['loadDataContainer'] as $callback) {
                                $this->import($callback[0]);
                                $this->{$callback[0]}->{$callback[1]}('tl_formdata');
                            }
                        }


                    }
                }
            }
        } else {
//            if (array_key_exists(\Input::get('do'), $GLOBALS['BE_MOD']['formdata']))
//            {
            $strFile = sprintf('%s/system/modules/contao-efg-bundle/dca/%s.php', TL_ROOT, $strFileName);

            if (file_exists($strFile)) {
                $strName = \Input::get('do');
                include_once $strFile;

                // Replace standard dca tl_formdata by form-dependent dca
                if (is_array($GLOBALS['TL_DCA'][$strName]) && count($GLOBALS['TL_DCA'][$strName]) > 0) {
                    $GLOBALS['TL_DCA']['tl_formdata'] = $GLOBALS['TL_DCA'][$strName];
                    unset($GLOBALS['TL_DCA'][$strName]);
                }

                // HOOK: allow to load custom settings
                if (isset($GLOBALS['TL_HOOKS']['loadDataContainer']) && is_array($GLOBALS['TL_HOOKS']['loadDataContainer'])) {
                    foreach ($GLOBALS['TL_HOOKS']['loadDataContainer'] as $callback) {
                        $this->import($callback[0]);
                        $this->{$callback[0]}->{$callback[1]}('tl_formdata');
                    }
                }

            }
        }
//        }

        @include(TL_ROOT . '/system/config/dcaconfig.php');

        // Rebuild internal cache
        if (!$GLOBALS['TL_CONFIG']['bypassCache']) {
            // $this->import('Automator');
            // $this->Automator->generateInternalCache();
        }
    }


    /**
     * Check permissions to edit table tl_formdata
     */
    public function checkPermission()
    {
        $this->import('BackendUser', 'User');

        $arrFields = array_keys($GLOBALS['TL_DCA']['tl_formdata']['fields']);
        // check/set restrictions
        foreach ($arrFields as $strField) {
            if (!empty($GLOBALS['TL_DCA']['tl_formdata']['fields'][$strField]['exclude']) && $GLOBALS['TL_DCA']['tl_formdata']['fields'][$strField]['exclude'] == true) {
                if ($this->User->isAdmin || $this->User->hasAccess('tl_formdata::' . $strField, 'alexf') == true) {
                    $GLOBALS['TL_DCA']['tl_formdata']['fields'][$strField]['exclude'] = false;
                }
            }
        }
    }


    /**
     * Autogenerate an alias if it has not been set yet
     * alias is created from Formdata content related to first form field of type text not using
     * rgxp=email/date/datim/time
     *
     * @param mixed
     * @param object
     *
     * @return string
     */
    public function generateAlias($varValue, \DataContainer $dc)
    {
        $strFormTitle = '';
        if (strlen($dc->strFormFilterValue)) {
            $strFormTitle = $dc->strFormFilterValue;
        }
        $this->import('MenAtWork\\EfgBundle\\Contao\\Formdata', 'Formdata');

        return $this->Formdata->generateAlias($varValue, $strFormTitle, $dc->id);
    }


    /*
    * Create List Label for formdata item
    */
    public function getRowLabel($arrRow)
    {
        $strRet = '';

        // Titles of all forms
        if (is_null($this->arrData)) {
            $strSql   = "SELECT id,title FROM tl_form";
            $objForms = \Database::getInstance()->prepare($strSql)->execute();

            while ($objForms->next()) {
                $this->arrData[$objForms->id]['title'] = $objForms->title;
            }
        }


        $strRet .= '<div class="fd_wrap"><div class="fd_head"><div class="cte_type unpublished">';
        $strRet .= '<strong>' . date($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']) . '</strong>';
        $strRet .= '<span style="color:#b3b3b3; padding-left:3px;">[' . $this->arrData[$arrRow['pid']]['title'] . ']</span>';
        $strRet .= '<span style="border:solid 1px blue">#' . $this->arrData[$arrRow['alias']] . '#</span>';
        $strRet .= '</div><p class="fd_notes">' . $arrRow['be_notes'] . '</p></div>';

        $strRet .= '<div class="mark_links">';
        // Details from table tl_formdata_details
        $strSql     = "SELECT ff_name,value FROM tl_formdata_details WHERE pid=? ORDER BY sorting ASC";
        $objDetails = \Database::getInstance()->prepare($strSql)->execute($arrRow['id']);

        while ($objDetails->next()) {
            $strRet .= '<div class="fd_row"><div class="fd_label">' . $objDetails->ff_name . ':&nbsp;</div><div class="fd_value">' . $objDetails->value . '&nbsp;</div></div>';
        }

        $strRet .= '</div></div>';

        return $strRet;

    }


    /**
     * Return all forms as array for dropdown
     *
     * @return array
     */
    public function getFormsSelect()
    {
        $forms = array();

        // Get all forms
        $objForms = \Database::getInstance()->prepare("SELECT id,title,formID FROM tl_form WHERE storeFormdata=? ORDER BY title ASC")
            ->execute("1");
        $forms[]  = '-';
        if ($objForms->numRows) {
            while ($objForms->next()) {
                $k         = $objForms->title;
                $v         = $objForms->title;
                $forms[$k] = $v;
            }
        }

        return $forms;
    }


    /**
     * Return all members as array for dropdown
     *
     * @return array
     */
    public function getMembersSelect()
    {
        $items = array();

        // Get all members
        $objItems = \Database::getInstance()->prepare("SELECT id, CONCAT(firstname,' ',lastname) AS fullname FROM tl_member ORDER BY fullname ASC")
            ->execute("1");
        //$items[0] = '-';
        if ($objItems->numRows) {
            while ($objItems->next()) {
                $k         = $objItems->id;
                $v         = $objItems->fullname;
                $items[$k] = $v;
            }
        }

        return $items;
    }


    /**
     * Return all users as array for dropdown
     *
     * @return array
     */
    public function getUsersSelect()
    {
        $items = array();

        // Get all users
        $objItems = \Database::getInstance()->prepare("SELECT id, name FROM tl_user ORDER BY name ASC")
            ->execute("1");
        //$items[0] = '-';
        if ($objItems->numRows) {
            while ($objItems->next()) {
                $k         = $objItems->id;
                $v         = $objItems->name;
                $items[$k] = $v;
            }
        }

        return $items;
    }

    /**
     * Return all member groups as array for dropdown
     *
     * @return array
     */
    public function getMemberGroupsSelect()
    {
        $items = array();

        // Get all member groups
        $objItems = \Database::getInstance()->prepare("SELECT id,`name` FROM tl_member_group ORDER BY `name` ASC")
            ->execute("1");
        //$items[0] = '-';
        if ($objItems->numRows) {
            while ($objItems->next()) {
                $k         = $objItems->id;
                $v         = $objItems->name;
                $items[$k] = $v;
            }
        }

        return $items;
    }

    /**
     * Return all user groups as array for dropdown
     *
     * @return array
     */
    public function getUserGroupsSelect()
    {
        $items = array();

        // Get all user groups
        $objItems = \Database::getInstance()->prepare("SELECT id,`name` FROM tl_user_group ORDER BY `name` ASC")
            ->execute("1");
        //$items[0] = '-';
        if ($objItems->numRows) {
            while ($objItems->next()) {
                $k         = $objItems->id;
                $v         = $objItems->name;
                $items[$k] = $v;
            }
        }

        return $items;
    }

}
