<?php

/**
 *
 *    Copyright (C) 2017 onOffice GmbH
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace onOffice\WPlugin\Model\FormModelBuilder;

use onOffice\WPlugin\Model\InputModelBase;
use onOffice\WPlugin\Model\InputModel\InputModelDBFactory;
use onOffice\WPlugin\DataFormConfiguration\DataFormConfiguration;

/**
 *
 * @url http://www.onoffice.de
 * @copyright 2003-2017, onOffice(R) GmbH
 *
 */

abstract class FormModelBuilder
{
	/** @var string */
	private $_pageSlug = null;

	/** @var array */
	private $_values = array();


	/**
	 *
	 * @param string $pageSlug
	 *
	 */

	public function __construct($pageSlug)
	{
		$this->_pageSlug = $pageSlug;
	}


	/**
	 *
	 */

	abstract public function generate();


	/**
	 *
	 * @param string $key
	 * @return mixed
	 *
	 */

	protected function getValue($key)
	{
		if (isset($this->_values[$key]))
		{
			return $this->_values[$key];
		}

		return null;
	}


	/**
	 *
	 * @return array
	 *
	 */

	protected function readFieldnames($module)
	{
		$pFieldnames = new \onOffice\WPlugin\Fieldnames();
		$pFieldnames->loadLanguage();

		$fieldnames = $pFieldnames->getFieldList($module, true, true);
		$result = array();

		foreach ($fieldnames as $key => $properties)
		{
			$result[$key] = $properties['label'];
		}

		return $result;
	}


	/**
	 *
	 * @param string $directory
	 * @param string $pattern
	 * @return array
	 *
	 */

	protected function readTemplatePaths($directory, $pattern = '*')
	{
		$templateGlobFiles = glob(plugin_dir_path(ONOFFICE_PLUGIN_DIR.'/index.php')
			.'templates.dist/'.$directory.'/'.$pattern.'.php');
		$templateLocalFiles = glob(plugin_dir_path(ONOFFICE_PLUGIN_DIR)
			.'onoffice-personalized/templates/'.$directory.'/'.$pattern.'.php');
		$templatesAll = array_merge($templateGlobFiles, $templateLocalFiles);
		$templates = array();

		foreach ($templatesAll as $value)
		{
			$value = str_replace(plugin_dir_path(ONOFFICE_PLUGIN_DIR), '', $value);
			$templates[$value] = $value;
		}

		return $templates;
	}


	/**
	 *
	 * @param string $module
	 * @param string $htmlType
	 * @return Model\InputModelDB
	 *
	 */

	public function createSortableFieldList($module, $htmlType)
	{
		$pInputModelFieldsConfig = $this->getInputModelDBFactory()->create(
			InputModelDBFactory::INPUT_FIELD_CONFIG, null, true);

		$pInputModelFieldsConfig->setHtmlType($htmlType);

		$pFieldnames = new \onOffice\WPlugin\Fieldnames();
		$pFieldnames->loadLanguage();

		$fieldNames = $pFieldnames->getFieldList($module, true, true);
		$pInputModelFieldsConfig->setValuesAvailable($fieldNames);

		$fields = $this->getValue(DataFormConfiguration::FIELDS);

		if (null == $fields)
		{
			$fields = array();
		}

		$pInputModelFieldsConfig->setValue($fields);
		return $pInputModelFieldsConfig;
	}


	/** @return string */
	public function getPageSlug()
		{ return $this->_pageSlug; }

	/** @param array $values */
	protected function setValues(array $values)
		{ $this->_values = $values; }
}
