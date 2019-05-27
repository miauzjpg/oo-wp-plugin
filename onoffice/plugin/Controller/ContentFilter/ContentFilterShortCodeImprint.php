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

namespace onOffice\WPlugin\Controller\ContentFilter;

use Exception;
use onOffice\WPlugin\Impressum;
use onOffice\WPlugin\Utility\Logger;


/**
 *
 */

class ContentFilterShortCodeImprint
	implements ContentFilterShortCode
{
	/** @var Impressum */
	private $_pImpressum = null;

	/** @var Logger */
	private $_pLogger = null;


	/**
	 *
	 * @param Impressum $pImpressum
	 *
	 */

	public function __construct(Impressum $pImpressum = null, Logger $pLogger = null)
	{
		$this->_pImpressum = $pImpressum ?? new Impressum;
		$this->_pLogger = $pLogger ?? new Logger;
	}


	/**
	 *
	 * @param array $attributesInput
	 * @return string
	 *
	 */

	public function replaceShortCodes(array $attributesInput): string
	{
		$value = '';

		try {
			if (count($attributesInput) === 1) {
				$attribute = $attributesInput[0];
				$value = $this->_pImpressum->getDataByKey($attribute);
			}
		} catch (Exception $pException) {
			$value = $this->_pLogger->logErrorAndDisplayMessage($pException);
		}
		return $value;
	}
}