<?php

/**
 *
 *    Copyright (C) 2018 onOffice GmbH
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

use onOffice\WPlugin\Types\Field;
use onOffice\WPlugin\Types\FieldTypes;

/**
 *
 * @url http://www.onoffice.de
 * @copyright 2003-2018, onOffice(R) GmbH
 *
 */

class TestClassField
	extends WP_UnitTestCase
{
	/**
	 *
	 */

	public function testDefaults()
	{
		$pField = new Field('testField123');
		$this->assertEquals('testField123', $pField->getName());
		$this->assertEquals('', $pField->getCategory());
		$this->assertEquals(null, $pField->getDefault());
		$this->assertEquals('', $pField->getLabel());
		$this->assertEquals(0, $pField->getLength());
		$this->assertEquals([], $pField->getPermittedvalues());
		$this->assertEquals(FieldTypes::FIELD_TYPE_VARCHAR, $pField->getType());
	}


	/**
	 *
	 */

	public function testSetter()
	{
		$pField = $this->getPrefilledField();
		$this->assertEquals('asdf', $pField->getCategory());
		$this->assertEquals('asd', $pField->getDefault());
		$this->assertEquals('A test', $pField->getLabel());
		$this->assertEquals(13, $pField->getLength());
		$this->assertEquals(['test', 'asdf', 13, 37], $pField->getPermittedvalues());
		$this->assertEquals(FieldTypes::FIELD_TYPE_DATE, $pField->getType());
	}


	/**
	 *
	 * This is pretty straightforward, but
	 *  - category is called 'content'
	 *  - length becomes null if it was set to 0
	 *
	 */

	public function testGetAsArray()
	{
		$pField = $this->getPrefilledField();
		$expectation = [
			'label' => 'A test',
			'type' => 'date',
			'default' => 'asd',
			'length' => 13,
			'permittedvalues' => ['test', 'asdf', 13, 37],
			'content' => 'asdf',
		];
		$this->assertEquals($expectation, $pField->getAsRow());

		$pField->setLength(0);
		$expectation['length'] = null;

		$this->assertEquals($expectation, $pField->getAsRow());
	}


	/**
	 *
	 * @return Field
	 *
	 */

	private function getPrefilledField(): Field
	{
		$pField = new Field('testField123', 'A test');
		$pField->setCategory('asdf');
		$pField->setDefault('asd');
		$pField->setLength(13);
		$pField->setPermittedvalues(['test', 'asdf', 13, 37]);
		$pField->setType(FieldTypes::FIELD_TYPE_DATE);
		return $pField;
	}
}
