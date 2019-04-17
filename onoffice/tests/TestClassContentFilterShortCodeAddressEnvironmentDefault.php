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

namespace onOffice\tests;

use onOffice\WPlugin\Controller\ContentFilter\ContentFilterShortCodeAddressEnvironmentDefault;
use onOffice\WPlugin\DataView\DataListViewFactoryAddress;
use onOffice\WPlugin\DataView\DataListViewAddress;
use onOffice\WPlugin\AddressList;
use onOffice\WPlugin\Template;
use onOffice\WPlugin\Impressum;

/**
 *
 * test class for ContentFilterShortCodeAddressEnvironmentDefault
 *
 */

class TestClassContentFilterShortCodeAddressEnvironmentDefault
	extends \WP_UnitTestCase
{

	/** @var ContentFilterShortCodeAddressEnvironmentDefault */
	private $_pEnvironment = null;


	/**
	 *
	 * @before
	 *
	 */

	public function prepare()
	{
		$this->_pEnvironment = new ContentFilterShortCodeAddressEnvironmentDefault();
	}


	/**
	 *
	 */

	public function testGetDataListFactory()
	{
		$this->assertInstanceOf(DataListViewFactoryAddress::class, $this->_pEnvironment->getDataListFactory());
	}


	/**
	 *
	 */

	public function testCreateAddressList()
	{
		$pAddressListView = $this->getMockBuilder(DataListViewAddress::class)
				->setConstructorArgs([1, 'test'])
				->getMock();
		$pResult = $this->_pEnvironment->createAddressList($pAddressListView);

		$this->assertInstanceOf(AddressList::class, $pResult);
	}


	/**
	 *
	 */

	public function testGetPage()
	{
		$this->assertEquals(1, $this->_pEnvironment->getPage());
	}


	/**
	 *
	 */

	public function testGetTemplate()
	{
		$pTemplateResult = $this->_pEnvironment->getTemplate('templateName');

		$this->assertInstanceOf(Template::class, $pTemplateResult);
	}


	/**
	 *
	 */

	public function testGetImpressum()
	{
		$pImpressumResult = $this->_pEnvironment->getImpressum();

		$this->assertInstanceOf(Impressum::class, $pImpressumResult);
	}
}