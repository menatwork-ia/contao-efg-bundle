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

namespace MenAtWork\EfgBundle;

class EfgRunonce extends \Controller
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		parent::__construct();

		// Fix potential Exception on line 0 because of __destruct method (see http://dev.contao.org/issues/2236)
		$this->import((TL_MODE == 'BE' ? 'BackendUser' : 'FrontendUser'), 'User');
		$this->import('Database');
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		// Nothing to do if EFG has not yet been installed
		if (!$this->Database->tableExists('tl_formdata'))
		{
			return;
		}

		$this->execute('clearCache');
		$this->execute('updateTables');
		$this->execute('updateListingModules');
		$this->execute('updateMailTemplates');
		$this->execute('updateFormPaginators');
		$this->execute('updateFormFieldEfgLookupOptions');
		$this->execute('updateDbafsUuid');
		$this->execute('updateConfig');
	}


	private function execute($method)
	{

		try
		{
			$this->$method();
		}
		catch(\Exception $e)
		{

			$strReturn = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Contao Open Source CMS</title>
<style media="screen">
div { width:520px; margin:64px auto 18px; padding:24px; background:#ffc; border:1px solid #fc0; font-family:Verdana,sans-serif; font-size:13px; }
h1 { font-size:18px; font-weight:normal; margin:0 0 18px; }
</style>
</head>
<body>
<div>
<h1>An error occurred when updating EFG</h1>
<pre style="white-space:normal">' . $e->getMessage() . '</pre>
</div>
</body>
</html>';

			echo $strReturn;
			exit;
		}

	}


	private function clearCache()
	{
		// Delete cached sql files
		if (is_dir(TL_ROOT . '/system/cache/sql'))
		{
			$arrFiles = scan(TL_ROOT . '/system/cache/sql', true);

			if (!empty($arrFiles))
			{
				foreach ($arrFiles as $strFile)
				{
					if (in_array($strFile, array('tl_form.php', 'tl_form_field.php', 'tl_formdata.php', 'tl_formdata_details.php', 'tl_module.php')))
					{
						$objFile = new \File('system/cache/sql/' . $strFile);
						$objFile->delete();
					}
				}
			}
		}

	}

	private function updateTables()
	{
		// Field tl_formdata_details.ff_type has been removed in r2.0.0
		if ($this->Database->fieldExists('ff_type', 'tl_formdata_details'))
		{
			$this->Database->query("ALTER TABLE tl_formdata_details DROP COLUMN `ff_type`");
		}

		// Field tl_formdata_details.ff_label has been removed in r2.0.0
		if ($this->Database->fieldExists('ff_label', 'tl_formdata_details'))
		{
			$this->Database->query("ALTER TABLE tl_formdata_details DROP COLUMN `ff_label`");
		}

	}


	private function updateListingModules()
	{
		// As of r2.0.0 formKey used for names of dca files and formdata listing modules is created from form alias instead of form title
		if (!$this->Database->fieldExists('storeFormdata', 'tl_form')
			|| !$this->Database->fieldExists('list_formdata', 'tl_module')
		)
		{
			return;
		}

		// Update formKey in formdata listing modules
		$objForms = $this->Database->prepare("SELECT * FROM tl_form WHERE `storeFormdata`=?")
			->execute('1');

		if ($objForms->numRows)
		{
			while ($objForms->next())
			{
				$strFormKeyOld = 'fd_' . ((!empty($objForms->formID)) ? str_replace('-', '_', standardize($objForms->formID)) : str_replace('-', '_', standardize($objForms->title)));
				$strFormKeyNew = 'fd_' . ((!empty($objForms->aliass)) ? $objForms->alias : str_replace('-', '_', standardize($objForms->title)));

				// Update formdata listing modules
				$objResult = $this->Database->prepare("UPDATE tl_module %s WHERE `type`=? AND `list_formdata`=?")
					->set(array('list_formdata' => $strFormKeyNew))
					->execute('formdatalisting', $strFormKeyOld);
			}
		}

	}


	private function updateMailTemplates()
	{
		// As of Contao 3 tl_form.confirmationMailTemplate and tl_form.formattedMailTemplate use database assisted fileTree
		// .. these fields normally should have been transformed already by the Contao install routine

		if (!$this->Database->fieldExists('confirmationMailTemplate', 'tl_form')
			&& !$this->Database->fieldExists('formattedMailTemplate', 'tl_form')
		)
		{
			return;
		}

		$blnTransform = false;
		$arrTransformFields = array();
		$arrFilesDone = array();

		$sql = "SELECT confirmationMailTemplate, formattedMailTemplate FROM tl_form WHERE (confirmationMailTemplate != '' AND confirmationMailTemplate != '0') OR (formattedMailTemplate != '' AND formattedMailTemplate != '0')";
		$objResult = $this->Database->prepare($sql)->execute();

		if ($objResult->numRows)
		{
			while ($objResult->next())
			{
				if ($objResult->confirmationMailTemplate != '' && !is_numeric($objResult->confirmationMailTemplate))
				{
					$arrTransformFields['confirmationMailTemplate'][$objResult->confirmationMailTemplate] = $objResult->confirmationMailTemplate;
					$blnTransform = true;
				}
				if ($objResult->formattedMailTemplate != '' && !is_numeric($objResult->formattedMailTemplate))
				{
					$arrTransformFields['formattedMailTemplate'][$objResult->formattedMailTemplate] = $objResult->formattedMailTemplate;
					$blnTransform = true;
				}
			}
		}

		if (!$blnTransform)
		{
			return;
		}

		if (!empty($arrTransformFields))
		{
			foreach ($arrTransformFields as $strField => $arrFiles)
			{
				if (!empty($arrFiles))
				{
					foreach ($arrFiles as $strFileOld => $strFile)
					{
						if (!isset($arrFilesDone[$strFileOld]))
						{
							$objFileModel = \FilesModel::findOneBy('path', $strFileOld);

							if ($objFileModel !== null)
							{
								$arrTransformFields[$strField][$strFileOld] = $objFileModel->id;
							}
						}
						else
						{
							$arrTransformFields[$strField][$strFileOld] = $arrFilesDone[$strFileOld];
						}
						$arrFilesDone[$strFileOld] = $arrTransformFields[$strField][$strFileOld];
					}
				}
			}

			// Update tl_form
			foreach ($arrTransformFields as $strField => $arrFiles)
			{
				if (!empty($arrFiles))
				{
					foreach ($arrFiles as $strFileOld => $strFile)
					{
						if (is_numeric($strFile))
						{
							$objResult = $this->Database->prepare("UPDATE tl_form %s WHERE ".$strField."=?")
								->set(array($strField => (string) $strFile))
								->execute((string)$strFileOld);
						}
					}
				}
			}
		}

	}


	private function updateFormPaginators()
	{
		// As of Contao 3 tl_form_field.singleSRC and tl_form_field.efgBackSingleSRC use database assisted fileTree
		// .. these fields normally should have been transformed already by the Contao install routine

		if (!$this->Database->fieldExists('efgBackSingleSRC', 'tl_form_field'))
		{
			return;
		}

		$blnTransform = false;
		$arrTransformFields = array();
		$arrFilesDone = array();

		$sql = "SELECT singleSRC, efgBackSingleSRC FROM tl_form_field WHERE `type`='efgFormPaginator' AND ((singleSRC != '' AND singleSRC != '0') OR (efgBackSingleSRC != '' AND efgBackSingleSRC != '0'))";
		$objResult = $this->Database->prepare($sql)->execute();

		if ($objResult->numRows)
		{
			while ($objResult->next())
			{
				if ($objResult->singleSRC != '' && !is_numeric($objResult->singleSRC))
				{
					$arrTransformFields['singleSRC'][$objResult->singleSRC] = $objResult->singleSRC;
					$blnTransform = true;
				}
				if ($objResult->efgBackSingleSRC != '' && !is_numeric($objResult->efgBackSingleSRC))
				{
					$arrTransformFields['efgBackSingleSRC'][$objResult->efgBackSingleSRC] = $objResult->efgBackSingleSRC;
					$blnTransform = true;
				}
			}
		}

		if (!$blnTransform)
		{
			return;
		}

		if (!empty($arrTransformFields))
		{
			foreach ($arrTransformFields as $strField => $arrFiles)
			{
				if (!empty($arrFiles))
				{
					foreach ($arrFiles as $strFileOld => $strFile)
					{
						if (!isset($arrFilesDone[$strFileOld]))
						{
							$objFileModel = \FilesModel::findOneBy('path', $strFileOld);

							if ($objFileModel !== null)
							{
								$arrTransformFields[$strField][$strFileOld] = $objFileModel->id;
							}
						}
						else
						{
							$arrTransformFields[$strField][$strFileOld] = $arrFilesDone[$strFileOld];
						}
						$arrFilesDone[$strFileOld] = $arrTransformFields[$strField][$strFileOld];
					}
				}
			}

			// Update tl_form_field
			foreach ($arrTransformFields as $strField => $arrFiles)
			{
				if (!empty($arrFiles))
				{
					foreach ($arrFiles as $strFileOld => $strFile)
					{
						if (is_numeric($strFile))
						{
							$objResult = $this->Database->prepare("UPDATE tl_form_field %s WHERE `type`='efgFormPaginator' AND ".$strField."=?")
								->set(array($strField => (string) $strFile))
								->execute((string)$strFileOld);
						}
					}
				}
			}
		}

	}


	private function updateFormFieldEfgLookupOptions()
	{

		// Update form data 'table name' in form fields of type EfgFormLookupCheckbox, EfgFormLookupRadio, EfgFormLookupSelectMenu
		// .. as of version 2.0.0 EFG creates dca file names and form keys based on form alias,
		// .. like fd_term-paper-submission (former fd_term_paper_submission)

		$this->import('MenAtWork\\EfgBundle\\Contao\\Formdata', 'Formdata');

		// Get all form data storing forms
		$arrStoringForms = $this->Formdata->arrStoringForms;

		// Get all form fields having efgLookupOptions
		$arrFormFields = $this->Database->prepare("SELECT id, efgLookupOptions FROM tl_form_field WHERE efgLookupOptions != ''")->execute()->fetchAllAssoc();

		if (count($arrFormFields) >= 1 && count($arrStoringForms) >= 1)
		{
			$arrMapFormDcaKey = array();

			// Build mapping, old form key => new form key
			foreach ($arrStoringForms as $strFormKey => $arrFormConfig)
			{
				$strOldKey = 'fd_' . ( (!empty($arrFormConfig['formID'])) ? $arrFormConfig['formID'] : str_replace('-', '_', standardize($arrFormConfig['title'])));
				$strNewKey = 'fd_' . ( (!empty($arrFormConfig['alias'])) ? $arrFormConfig['alias'] : str_replace('-', '_', standardize($arrFormConfig['title'])));
				$arrMapFormDcaKey[$strOldKey] = $strNewKey;
			}

			foreach ($arrFormFields as $arrFormField)
			{
				$arrLookupOptions = deserialize($arrFormField['efgLookupOptions']);

				if (!empty($arrLookupOptions['lookup_field']))
				{

					// Replace old form data 'table name' by new form 'table name' if lookup table is form data 'table'
					if (substr($arrLookupOptions['lookup_field'], 0, 3) == 'fd_')
					{
						$arrLookupFieldParams = explode('.', $arrLookupOptions['lookup_field']);

						if (in_array($arrLookupFieldParams[0], array_keys($arrMapFormDcaKey)))
						{
							$arrLookupOptions['lookup_field'] = $arrMapFormDcaKey[$arrLookupFieldParams[0]] . '.' . $arrLookupFieldParams[1];

							$objFormFieldModel = \FormFieldModel::findBy('id', $arrFormField['id']);
							$objFormFieldModel->efgLookupOptions = serialize($arrLookupOptions);
							$objFormFieldModel->save();
						}
					}
				}
			}
		}
	}

	private function updateDbafsUuid()
	{

		// As of Contao 3.2 assisted fileTree widgets store tl_files.uuid instead of tl_files.id

		$arrConvertFields = array
		(
			'single' => array
			(
				'tl_form' => array
				(
					'confirmationMailTemplate',
					'formattedMailTemplate'
				),
				'tl_form_field' => array
				(
					'efgBackSingleSRC'
				)
			),
			'multiple' => array
			(
				'tl_form' => array
				(
					'confirmationMailAttachments',
					'formattedMailAttachments'
				),
				'tl_form_field' => array
				(
					'efgMultiSRC'
				)
			)
		);

		foreach ($arrConvertFields['single'] as $strTable => $arrFields)
		{
			foreach ($arrFields as $strField)
			{
				\Contao\Database\Updater::convertSingleField($strTable, $strField);
			}
		}

		foreach ($arrConvertFields['multiple'] as $strTable => $arrFields)
		{
			foreach ($arrFields as $strField)
			{
				\Contao\Database\Updater::convertMultiField($strTable, $strField);
			}
		}

	}

	private function updateConfig()
	{
		$this->import('MenAtWork\\EfgBundle\\Contao\\FormdataBackend', 'FormdataBackend');
		$this->FormdataBackend->updateConfig();
	}

}