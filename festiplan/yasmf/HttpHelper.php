<?php

/**
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2019   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace yasmf;

session_start();

/**
 * Helper class to get HTTP params
 */
class HttpHelper
{

    /**
     * @param string $name the name of the param
     * @return string|null the value of the param if defined, null otherwise
     */
    public static function getParam(string $name): ?string {
        if (isset($_GET[$name])) return htmlspecialchars($_GET[$name]);
        if (isset($_POST[$name])) return htmlspecialchars($_POST[$name]);
        if (isset($_FILES[$name])) return $_FILES[$name];
        if (isset($_SESSION[$name])) return $_SESSION[$name];
        return null;
    }

}