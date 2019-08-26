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

use onOffice\SDK\onOfficeSDK;
use onOffice\WPlugin\API\APIClientActionGeneric;
use onOffice\WPlugin\DataView\DataDetailView;
use onOffice\WPlugin\DataView\DataDetailViewHandler;
use onOffice\WPlugin\DataView\DataListView;
use onOffice\WPlugin\DataView\DataListViewFactory;
use onOffice\WPlugin\DataView\UnknownViewException;
use onOffice\WPlugin\PDF\PdfDocumentModel;
use onOffice\WPlugin\PDF\PdfDocumentModelValidator;
use onOffice\WPlugin\SDKWrapper;
use onOffice\WPlugin\WP\WPOptionWrapperTest;
use WP_UnitTestCase;

/**
 *
 */

class TestClassPdfDocumentModelValidator
	extends WP_UnitTestCase
{
	/** @var APIClientActionGeneric */
	private $_pAPIClientAction = null;

	/** @var DataListViewFactory */
	private $_pDataListviewFactory = null;

	/** @var PdfDocumentModelValidator */
	private $_pSubject = null;


	/**
	 *
	 * @before
	 *
	 */

	public function prepare()
	{
		$this->_pAPIClientAction = $this->getMockBuilder(APIClientActionGeneric::class)
			->setConstructorArgs([new SDKWrapper(), '', ''])
			->setMethods(['getResultStatus', 'getResultRecords', 'withActionIdAndResourceType', 'sendRequests'])
			->getMock();
		$pDetailView = new DataDetailView();
		$pDetailView->setExpose('testdetailexpose');
		$pWPOptionWrapper = new WPOptionWrapperTest();
		$pWPOptionWrapper->addOption('onoffice-default-view', $pDetailView);
		$pDataDetailViewHandler = new DataDetailViewHandler($pWPOptionWrapper);

		$this->_pDataListviewFactory = $this->getMockBuilder(DataListViewFactory::class)
			->getMock();

		$this->_pSubject = new PdfDocumentModelValidator
			($this->_pAPIClientAction, $pDataDetailViewHandler, $this->_pDataListviewFactory);
	}


	/**
	 *
	 */

	public function testValidateForDetailView()
	{
		$this->_pAPIClientAction->expects($this->once())->method('withActionIdAndResourceType')
			->with(onOfficeSDK::ACTION_ID_READ, 'estate')
			->will($this->returnSelf());
		$this->_pAPIClientAction->expects($this->once())->method('getResultStatus')->will($this->returnValue(true));
		$this->_pAPIClientAction->expects($this->once())->method('getResultRecords')->will($this->returnValue([
			0 => [
				'Id' => '13',
			],
		]));
		$pPdfDocumentModel = new PdfDocumentModel(13, 'detail');
		$pResult = $this->_pSubject->validate($pPdfDocumentModel);
		$this->assertInstanceOf(PdfDocumentModel::class, $pResult);
		$this->assertEquals(13, $pResult->getEstateId());
		$this->assertEquals('ENG', $pResult->getLanguage());
		$this->assertEquals('testdetailexpose', $pResult->getTemplate());
		$this->assertEquals('detail', $pResult->getViewName());
	}


	/**
	 *
	 */

	public function testValidateForListView()
	{
		$this->_pAPIClientAction->expects($this->once())->method('withActionIdAndResourceType')
			->with(onOfficeSDK::ACTION_ID_READ, 'estate')
			->will($this->returnSelf());
		$this->_pAPIClientAction->expects($this->once())->method('getResultStatus')->will($this->returnValue(true));
		$this->_pAPIClientAction->expects($this->once())->method('getResultRecords')->will($this->returnValue([
			0 => [
				'Id' => '13',
			],
		]));

		$pDataListview = new DataListView(13, 'list');
		$pDataListview->setExpose('testexpose');
		$this->_pDataListviewFactory->expects($this->once())->method('getListViewByName')->with('list')
			->will($this->returnValue($pDataListview));
		$pPdfDocumentModel = new PdfDocumentModel(13, 'list');
		$pResult = $this->_pSubject->validate($pPdfDocumentModel);
		$this->assertInstanceOf(PdfDocumentModel::class, $pResult);
		$this->assertEquals(13, $pResult->getEstateId());
		$this->assertEquals('ENG', $pResult->getLanguage());
		$this->assertEquals('testexpose', $pResult->getTemplate());
		$this->assertEquals('list', $pResult->getViewName());
	}


	/**
	 *
	 * @expectedException \onOffice\WPlugin\PDF\PdfDocumentModelValidationException
	 *
	 */

	public function testValidateForUnknownListView()
	{
		$pException = new UnknownViewException();
		$this->_pDataListviewFactory->expects($this->once())->method('getListViewByName')->with('list')
			->will($this->throwException($pException));
		$pPdfDocumentModel = new PdfDocumentModel(13, 'list');
		$this->_pSubject->validate($pPdfDocumentModel);
	}


	/**
	 *
	 * @expectedException \onOffice\WPlugin\PDF\PdfDocumentModelValidationException
	 *
	 */

	public function testValidateForUnknownExpose()
	{
		$pDataListview = new DataListView(13, 'list');
		$this->_pDataListviewFactory->expects($this->once())->method('getListViewByName')->with('list')
			->will($this->returnValue($pDataListview));
		$pPdfDocumentModel = new PdfDocumentModel(13, 'list');
		$this->_pSubject->validate($pPdfDocumentModel);
	}
}
