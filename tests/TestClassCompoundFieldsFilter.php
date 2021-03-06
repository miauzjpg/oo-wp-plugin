<?php

/**
 *
 *    Copyright (C) 2019 onOffice GmbH
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

declare (strict_types=1);

namespace onOffice\tests;

use onOffice\WPlugin\Field\CompoundFieldsFilter;
use onOffice\WPlugin\Types\FieldsCollection;
use onOffice\WPlugin\Types\Field;
use WP_UnitTestCase;


/**
 *
 * Test of class CompoundFieldsFilter
 *
 */

class TestClassCompoundFieldsFilter
	extends WP_UnitTestCase
{
	/** @var FieldsCollection */
	private $_pFieldsCollection = null;

	/** @var CompoundFieldsFilter */
	private $_pCompoundFields = null;

	/** @var array */
	private $_expectedResultMerge = [
		'Name',
		'Anrede',
		'Titel',
	];

	/** @var array */
	private $_expectedResultAssocMerge = [
		'Anrede' => 'address',
		'Titel' => 'address',
		'Name' => 'address',
	];

	/** @var array */
	private $_fields = [
		'Anrede-Titel' => 'address',
		'Name' => 'address',
	];


	/**
	 *
	 * @before
	 *
	 */

	public function prepare()
	{
		$this->_pCompoundFields = new CompoundFieldsFilter();
		$this->_pFieldsCollection = new FieldsCollection();
		$pFieldAnredeTitel = new Field('Anrede-Titel', 'address', 'Anrede-Titel');
		$pFieldAnredeTitel->setCompoundFields(['Anrede', 'Titel']);
		$pFieldName = new Field('Name', 'address', 'Name');
		$pFieldName->setCompoundFields([]);
		$this->_pFieldsCollection->addField($pFieldAnredeTitel);
		$this->_pFieldsCollection->addField($pFieldName);
	}


	/**
	 *
	 * @covers onOffice\WPlugin\Field\CompoundFieldsFilter::buildCompoundFields
	 *
	 */

	public function testBuildCompoundFields()
	{
		$expectedResult = ['Anrede-Titel' => ['Anrede', 'Titel']];
		$result = $this->_pCompoundFields->buildCompoundFields($this->_pFieldsCollection);

		$this->assertEquals($expectedResult, $result);
	}


	/**
	 *
	 * @covers onOffice\WPlugin\Field\CompoundFieldsFilter::mergeFields
	 * @covers onOffice\WPlugin\Field\CompoundFieldsFilter::createNew
	 *
	 */

	public function testMergeFields()
	{
		$result = $this->_pCompoundFields->mergeFields($this->_pFieldsCollection, array_keys($this->_fields));

		$this->assertEquals($this->_expectedResultMerge, $result);
	}


	/**
	 *
	 * @covers onOffice\WPlugin\Field\CompoundFieldsFilter::mergeAssocFields
	 * @covers onOffice\WPlugin\Field\CompoundFieldsFilter::createNew
	 *
	 */

	public function testMergeAssocFields()
	{
		$result = $this->_pCompoundFields->mergeAssocFields($this->_pFieldsCollection, $this->_fields);

		$this->assertEquals($this->_expectedResultAssocMerge, $result);
	}


	/**
	 *
	 * @covers onOffice\WPlugin\Field\CompoundFieldsFilter::mergeListFilterableFields
	 *
	 */

	public function testMergeListFilterableFields()
	{
		$testdata = ['Anrede-Titel' => null];

		$expectedData = ['Anrede' => null, 'Titel' => null];
		$result = $this->_pCompoundFields->mergeListFilterableFields($this->_pFieldsCollection, $testdata);

		$this->assertEquals($expectedData, $result);
	}
}