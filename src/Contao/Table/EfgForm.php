<?php
/**
 * @author Andreas Dziemba <dziemba@men-at-work.de>
 */

namespace MenAtWork\EfgBundle\Contao\Table;

/**
 * Class EfgForm
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Thomas Kuhn 2007-2014
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class EfgForm extends \Backend
{

    /**
     * Return all possible Email fields  as array
     * @return array
     */
    public function getEmailFormFields()
    {
        $fields = array();

        // Get all form fields which can be used to define recipient of confirmation mail
        $objFields = \Database::getInstance()->prepare("SELECT id,name,label FROM tl_form_field WHERE pid=? AND `type`!=? AND `type`!=? AND `type`!=? AND `type`!=? AND `type`!=? AND `type`!=? AND `type`!=? AND `type`!=? AND `type`!=? ORDER BY name ASC")
            ->execute(\Input::get('id'), 'calendar', 'captcha', 'condition', 'efgFormPaginator', 'explanation', 'headline', 'submit', 'upload', 'xdependentcalendarfields');

        $fields[] = '-';
        while ($objFields->next())
        {
            $k = $objFields->name;
            if (strlen($k))
            {
                $v = $objFields->label;
                $v = strlen($v) ? $v.' ['.$k.']' : $k;
                $fields[$k] =$v;
            }
        }

        return $fields;
    }

    /**
     * Return all possible Alias fields as array
     * @return array
     */
    public function getAliasFormFields()
    {
        $fields = array();

        // Get all form fields which can be used to build auto alias
        $objFields = \Database::getInstance()->prepare("SELECT id,name,label FROM tl_form_field WHERE pid=? AND (type=? OR type=?) ORDER BY name ASC")
            ->execute(\Input::get('id'), 'text', 'hidden');

        $fields[] = '-';
        while ($objFields->next())
        {
            $k = $objFields->name;
            $v = $objFields->label;
            $v = strlen($v) ? $v.' ['.$k.']' : $k;
            $fields[$k] = $v;
        }

        return $fields;
    }
}