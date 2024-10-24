<?php

/**
 * OperatorToFieldPrefix view helper
 *
 * PHP version 7
 *
 * Copyright (C) Villanova University 2023.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  View_Helpers
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development Wiki
 */

namespace VuFind\View\Helper\Root;

/**
 * OperatorToFieldPrefix view helper
 *
 * @category VuFind
 * @package  View_Helpers
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development Wiki
 */
class OperatorToFieldPrefix extends \Laminas\View\Helper\AbstractHelper
{
    /**
     * Given a Boolean operator from a filter, convert it into a VuFind URL field prefix.
     *
     * @param string $operator Boolean operator (AND/OR/NOT)
     *
     * @return string
     */
    public function __invoke(string $operator): string
    {
        switch (strtoupper(trim($operator))) {
            case 'OR':
                return '~';
            case 'NOT':
                return '-';
        }
        // Default case -- no prefix:
        return '';
    }
}
