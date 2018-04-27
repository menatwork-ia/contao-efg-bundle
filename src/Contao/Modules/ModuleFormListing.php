<?php

namespace MenAtWork\EfgBundle\Contao\Modules;


class ModuleFormListing extends \BackendModule
{

    protected $strTemplate = 'form_list';

    public function compile()
    {

        $result = $this->Database->prepare("SELECT * FROM tl_form WHERE storeFormdata = 1")->execute();

        // TODO Accessrights
        if ($this->User->isAdmin ){ //|| $this->User->hasAccess('feedback', 'modules')) {
            $forms = array(
                array(
                    'name'  => $GLOBALS['TL_LANG']['MOD']['feedback'][0],
                    'alias' => 'feedback'
                )
            );
        }

        while (($form = $result->fetchAssoc()) !== false) {
            if ($this->User->isAdmin) { //|| $this->User->hasAccess('fd_' . $form['alias'], 'modules')) {
                $forms[] = array(
                    'name'  => $form['title'],
                    'alias' => 'fd_' . $form['alias'],
                );
            }
        }

        $this->Template->forms = $forms;
    }

}
