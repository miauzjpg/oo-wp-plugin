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

namespace onOffice\WPlugin\ScriptLoader;

use onOffice\WPlugin\Favorites;
use const ONOFFICE_PLUGIN_DIR;
use function plugins_url;

/**
 *
 */

class ScriptLoaderGenericConfigurationDefault
	implements ScriptLoaderGenericConfiguration
{
	/**
	 *
	 * @return array
	 *
	 */

	public function getScriptLoaderGenericConfiguration(): array
	{
		$pluginPath = ONOFFICE_PLUGIN_DIR.'/index.php';
		$script = IncludeFileModel::TYPE_SCRIPT;
		$style = IncludeFileModel::TYPE_STYLE;

		$values = [
			new IncludeFileModel($script, 'jquery-latest', 'https://code.jquery.com/jquery-latest.js'),

			(new IncludeFileModel($script, 'onoffice-multiselect', plugins_url('/js/onoffice-multiselect.js', $pluginPath)))
				->setLoadInFooter(true),
			(new IncludeFileModel($script, 'onoffice-leadform', plugins_url('/js/onoffice-leadform.js', $pluginPath)))
				->setDependencies(['jquery'])
				->setLoadInFooter(true),

			new IncludeFileModel($style, 'onoffice-default', plugins_url('/css/onoffice-default.css', $pluginPath)),
			new IncludeFileModel($style, 'onoffice-multiselect', plugins_url('/css/onoffice-multiselect.css', $pluginPath)),
			new IncludeFileModel($style, 'onoffice-forms', plugins_url('/css/onoffice-forms.css', $pluginPath)),
		];

		if (Favorites::isFavorizationEnabled()) {
			$values []= new IncludeFileModel($script, 'onoffice-favorites', plugins_url('/js/favorites.js', $pluginPath));
		}

		return $values;
	}
}