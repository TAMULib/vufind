<?php
/**
 * FOLIO REST API driver
 *
 * PHP version 7
 *
 * Copyright (C) Villanova University 2018.
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
 * @package  ILS_Drivers
 * @author   Chris Hallberg <challber@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:ils_drivers Wiki
 */
namespace TAMU\ILS\Driver;

/**
 * TAMU Customized FOLIO REST API driver
 *
 * @category VuFind
 * @package  ILS_Drivers
 * @author   Chris Hallberg <challber@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:ils_drivers Wiki
 */
class Folio extends \VuFind\ILS\Driver\Folio
{
    /**
     * Support method for getHoldings(): extract details from the holding record
     * that will be needed by formatHoldingItem() below.
     *
     * TAMU Customization - additionally expose the holdings HRID and shelving
     * title, which are consumed by the holdings and RTAC templates.
     *
     * @param object $holding FOLIO holding record (decoded from JSON)
     *
     * @return array
     */
    protected function getHoldingDetailsForItem($holding): array
    {
        return parent::getHoldingDetailsForItem($holding) + [
            'hrid' => $holding->hrid ?? '',
            'shelvingTitle' => $holding->shelvingTitle ?? '',
        ];
    }

    /**
     * Support method for getHoldings() -- return an array of item-level details
     * from other data: the location, the holdings record, and any current loan on
     * the item.
     *
     * TAMU Customization - holdings statements are exposed as "issues" rather than
     * "summary", because the TAMU holdings template renders them inline instead of
     * in the generic holdings text fields block.
     *
     * @param string     $locationId     Location identifier from FOLIO
     * @param array      $holdingDetails Holding details from getHoldingDetailsForItem()
     * @param ?\stdClass $currentLoan    Any current loan on this item
     *
     * @return array
     */
    protected function getItemFieldsFromNonItemData(
        string $locationId,
        array $holdingDetails,
        ?\stdClass $currentLoan = null,
    ): array {
        $fields = parent::getItemFieldsFromNonItemData(
            $locationId,
            $holdingDetails,
            $currentLoan
        );
        $fields['issues'] = $holdingDetails['holdingsStatements'];
        unset($fields['summary']);
        return $fields;
    }

    /**
     * Support method for getHoldings() -- given a few key details, format an item
     * for inclusion in the return value.
     *
     * TAMU Customization - expose the HRIDs used to build GIFM "Get It" element
     * ids, the raw enumeration/chronology values and a pre-formatted call number.
     *
     * @param string     $bibId            Current bibliographic ID
     * @param array      $holdingDetails   Holding details from getHoldingDetailsForItem()
     * @param object     $item             FOLIO item record (decoded from JSON)
     * @param int        $number           The current item number
     * @param string     $dueDateValue     The due date to display to the user
     * @param array      $boundWithRecords Any bib records this holding is bound with
     * @param ?\stdClass $currentLoan      Any current loan on this item
     *
     * @return array
     */
    protected function formatHoldingItem(
        string $bibId,
        array $holdingDetails,
        $item,
        $number,
        string $dueDateValue,
        $boundWithRecords,
        $currentLoan
    ): array {
        $result = parent::formatHoldingItem(
            $bibId,
            $holdingDetails,
            $item,
            $number,
            $dueDateValue,
            $boundWithRecords,
            $currentLoan
        );
        return $result + [
            'item_hrid' => $item->hrid ?? '',
            'holding_hrid' => $holdingDetails['hrid'],
            'enumeration' => $item->enumeration ?? '',
            'item_chronology' => $item->chronology ?? '',
            'call_number_formatted' => $this->_buildFormattedCallNumber(
                [
                    'callnumber_prefix' => $result['callnumber_prefix'] ?? '',
                    'callnumber' => $result['callnumber'] ?? '',
                ]
            ),
            'holding_shelving_title' => $holdingDetails['shelvingTitle'],
        ];
    }

    /**
     * Support method for getHoldings() -- create a fake item for holdings with no
     * items from FOLIO.
     *
     * TAMU Customization - purchase, bound-with and zero-item workarounds. Unlike
     * the parent implementation, the synthetic item is left visible in the holdings
     * display so that "Purchase It For Me" and bound-with records remain reachable.
     *
     * @param string $bibId          Bib-level id
     * @param array  $holdingDetails Holding details from getHoldingDetailsForItem()
     * @param object $holding        FOLIO holding record (decoded from JSON)
     *
     * @return array An associative array with the item keys
     */
    protected function buildHoldingsNoItemsData(
        string $bibId,
        array $holdingDetails,
        object $holding
    ): array {
        $fallbackLocationId = $holding->effectiveLocationId
            ?? $holding->permanentLocationId
            ?? null;

        // Without a location we cannot build a meaningful synthetic item, so
        // defer to the parent (non-visible) placeholder:
        if (!$fallbackLocationId) {
            return parent::buildHoldingsNoItemsData(
                $bibId,
                $holdingDetails,
                $holding
            );
        }

        $boundWithLocations = isset($this->config['TAMU']['bound_with_locations'])
            ? explode(":", $this->config['TAMU']['bound_with_locations']) : [];

        $locAndHoldings = $this->getItemFieldsFromNonItemData(
            $fallbackLocationId,
            $holdingDetails
        );

        $callNumberData = $this->chooseCallNumber(
            $holdingDetails['holdingCallNumberPrefix'],
            $holdingDetails['holdingCallNumber'],
            '',
            ''
        );

        $isPurchase = false;
        if (
            $locAndHoldings['location_code'] == 'POD'
            && $holdingDetails['hasHoldingNotes']
        ) {
            foreach ($holding->notes ?? [] as $note) {
                if (stripos($note->note ?? '', "Purchase It For Me") !== false) {
                    $isPurchase = true;
                    break;
                }
            }
        }

        if ($isPurchase) {
            $synthItemType = 'purchase-it';
        } elseif (in_array($locAndHoldings['location_code'], $boundWithLocations)) {
            $synthItemType = 'bound-with-item';
        } else {
            $synthItemType = 'itemless-holding';
        }

        return $callNumberData + $locAndHoldings + [
            'id' => $bibId,
            'item_id' => $synthItemType,
            'item_hrid' => $synthItemType,
            'holdings_id' => $holdingDetails['id'],
            'holding_hrid' => $holdingDetails['hrid'],
            'number' => 0,
            'barcode' => $synthItemType,
            'status' => null,
            'availability' => true,
            'item_notes' => null,
            'reserve' => 'TODO',
            'enumeration' => '',
            'item_chronology' => '',
            'addLink' => true,
            'call_number_formatted' => $this->_buildFormattedCallNumber(
                $callNumberData
            ),
            'holding_shelving_title' => $holdingDetails['shelvingTitle'],
        ];
    }

    /**
     * TAMU method to build the full call number from its component parts
     *
     * @param array $callNumberData The call number data
     *
     * @return array A string representng the full call number
     */
    private function _buildFormattedCallNumber($callNumberData)
    {
        return implode(" ", $callNumberData);
    }

    /**
     * Get Courses
     *
     * Obtain a list of courses for use in limiting the reserves list.
     *
     * @return array An associative array with key = ID, value = name.
     */
    public function getCourses()
    {
        //TAMU Customization
        return $this->getCourseResourceList('courses', null, 'courseNumber');
    }
}
