<?php
/**
 * @author Andreas Dziemba <dziemba@men-at-work.de>
 */

namespace MenAtWork\EfgBundle\Contao\Table;


class EfgModule extends \Backend
{

    private $arrFormdataTables = null;
    private $arrFormdataFields = null;

    public function onloadModuleType($varValue, \DataContainer $dc)
    {
        if ($varValue == 'formdatalisting')
        {
            $GLOBALS['TL_LANG']['tl_module']['list_fields'] = $GLOBALS['TL_LANG']['tl_module']['efg_list_fields'];
            $GLOBALS['TL_LANG']['tl_module']['list_search'] = $GLOBALS['TL_LANG']['tl_module']['efg_list_search'];
            $GLOBALS['TL_LANG']['tl_module']['list_info'] = $GLOBALS['TL_LANG']['tl_module']['efg_list_info'];

            $GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['inputType'] = 'checkboxWizard';
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['eval']['mandatory'] = false;
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['options_callback'] = array('tl_module_efg', 'optionsListFields');
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['load_callback'][] = array('tl_module_efg', 'onloadListFields');
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['save_callback'][] = array('tl_module_efg', 'onsaveFieldList');

            $GLOBALS['TL_DCA']['tl_module']['fields']['list_search']['inputType'] = 'checkboxWizard';
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_search']['options_callback'] = array('tl_module_efg', 'optionsSearchFields');
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_search']['load_callback'][] = array('tl_module_efg', 'onloadSearchFields');
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_search']['save_callback'][] = array('tl_module_efg', 'onsaveFieldList');

            $GLOBALS['TL_DCA']['tl_module']['fields']['list_info']['inputType'] = 'checkboxWizard';
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_info']['options_callback'] = array('tl_module_efg', 'optionsInfoFields');
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_info']['load_callback'][] = array('tl_module_efg', 'onloadInfoFields');
            $GLOBALS['TL_DCA']['tl_module']['fields']['list_info']['save_callback'][] = array('tl_module_efg', 'onsaveFieldList');
        }
        return $varValue;
    }

    /**
     * Return all formdata tables as array
     * @return array
     */
    public function getFormdataTables(\DataContainer $dc)
    {
        if (null === $this->arrFormdataTables || null === $this->arrFormdataFields)
        {
            $this->arrFormdataTables = array();
            $this->arrFormdataTables['fd_feedback'] = $GLOBALS['TL_LANG']['MOD']['feedback'][0];

            // all forms marked to store data
            $objFields = \Database::getInstance()->prepare("SELECT f.id,f.title,f.alias,f.formID,ff.type,ff.name,ff.label FROM tl_form f, tl_form_field ff WHERE (f.id=ff.pid) AND storeFormdata=? ORDER BY title")
                ->execute('1');

            while ($objFields->next())
            {
                $arrField = $objFields->row();
                $varKey = 'fd_' . (strlen($arrField['alias']) ? $arrField['alias'] : str_replace('-', '_', standardize($arrField['title'])));
                $this->arrFormdataTables[$varKey] = $arrField['title'];
                $this->arrFormdataFields['fd_feedback'][$arrField['name']] = $arrField['label'];
                $this->arrFormdataFields[$varKey][$arrField['name']] = $arrField['label'];
            }
        }
        \System::loadLanguageFile('tl_formdata', null, true);
        if (strlen($dc->value))
        {
            $this->loadDataContainer($dc->value, true);
        }
        return $this->arrFormdataTables;
    }

    public function optionsListFields(\DataContainer $dc)
    {
        return $this->getFieldsOptionsArray('list_fields');
    }

    public function optionsSearchFields(\DataContainer $dc)
    {
        return $this->getFieldsOptionsArray('list_search');
    }

    public function optionsInfoFields(\DataContainer $dc)
    {
        return $this->getFieldsOptionsArray('list_info');
    }

    public function getFieldsOptionsArray($strField)
    {
        $arrReturn = array();
        if (count($GLOBALS['TL_DCA']['tl_formdata']['fields']))
        {
            $GLOBALS['TL_DCA']['tl_module']['fields'][$strField]['inputType'] = 'CheckboxWizard';
            $GLOBALS['TL_DCA']['tl_module']['fields'][$strField]['eval']['multiple'] = true;
            $GLOBALS['TL_DCA']['tl_module']['fields'][$strField]['eval']['mandatory'] = false;
            foreach ($GLOBALS['TL_DCA']['tl_formdata']['fields'] as $k => $v)
            {
                if (in_array($k, array('import_source')) )
                {
                    continue;
                }
                $arrReturn[$k] = (strlen($GLOBALS['TL_DCA']['tl_formdata']['fields'][$k]['label'][0]) ? $GLOBALS['TL_DCA']['tl_formdata']['fields'][$k]['label'][0] . ' [' . $k . ']' : $k);
            }
        }
        return $arrReturn;
    }

    public function onloadListFields($varValue, \DataContainer $dc)
    {
        return $this->onloadFieldList('list_fields', $varValue);
    }

    public function onloadSearchFields($varValue, \DataContainer $dc)
    {
        return $this->onloadFieldList('list_search', $varValue);
    }

    public function onloadInfoFields($varValue, \DataContainer $dc)
    {
        return $this->onloadFieldList('list_info', $varValue);
    }

    public function onsaveFieldList($varValue)
    {
        if (strlen($varValue))
        {
            return implode(',', deserialize($varValue));
        }
        return $varValue;
    }

    public function onloadFieldList($strField, $varValue)
    {
        if (isset($GLOBALS['TL_DCA']['tl_module']['fields'][$strField]))
        {
            $GLOBALS['TL_DCA']['tl_module']['fields'][$strField]['eval']['multiple'] = true;
            if (is_string($varValue))
            {
                $varValue = explode(',', $varValue);
            }
        }
        return $varValue;
    }
}