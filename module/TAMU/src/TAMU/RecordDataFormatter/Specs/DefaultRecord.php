<?php

/**
 * TAMU DefaultRecord RecordDataFormatter specs.
 *
 * PHP version 8
 *
 * @category VuFind
 * @package  RecordDataFormatter
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:architecture:record_data_formatter
 * Wiki
 */

namespace TAMU\RecordDataFormatter\Specs;

use VuFind\View\Helper\Root\RecordDataFormatter\SpecBuilder;

/**
 * TAMU DefaultRecord RecordDataFormatter specs.
 *
 * @category VuFind
 * @package  RecordDataFormatter
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:architecture:record_data_formatter
 * Wiki
 */
class DefaultRecord extends \VuFind\RecordDataFormatter\Specs\DefaultRecord
{
    /**
     * Get default specifications for displaying data in core metadata.
     *
     * @return array
     */
    protected function getDefaultCoreSpecs(): array
    {
        $spec = new SpecBuilder();
        $spec
            ->setTemplateLine(
                'Published in',
                'getContainerTitle',
                'data-containerTitle.phtml'
            )->setLine(
                'New Title',
                'getNewerTitles',
                null,
                ['recordLink' => 'title']
            )->setLine(
                'Previous Title',
                'getPreviousTitles',
                null,
                ['recordLink' => 'title']
            )->setLine(
                'Uniform Title',
                'getUniformTitle'
            )->setMultiLine(
                'Authors',
                'getDeduplicatedAuthors',
                $this->getAuthorFunction()
            )->setLine(
                'Format',
                'getFormats',
                'RecordHelper',
                ['helperMethod' => 'getFormatList']
            )->setLine(
                'Language',
                'getLanguages',
                null,
                $this->getLanguageLineSettings()
            )->setLine(
                'Language Notes',
                'getLanguageNotes',
                null,
                ['itemPrefix' => '<span property="notesLanguage">',
                 'itemSuffix' => '</span>']
            )->setTemplateLine(
                'Published',
                'getPublicationDetails',
                'data-publicationDetails.phtml'
            )->setLine(
                'Edition',
                'getEdition',
                null,
                [
                    'itemPrefix' => '<span property="bookEdition">',
                    'itemSuffix' => '</span>',
                ]
            )->setTemplateLine('Series', 'getSeries', 'data-series.phtml')
            ->setTemplateLine(
                'Subjects',
                'getAllSubjectHeadings',
                'data-allSubjectHeadings.phtml'
            )->setTemplateLine(
                'child_records',
                'getChildRecordCount',
                'data-childRecords.phtml',
                ['allowZero' => false]
            )->setTemplateLine('Online Access', true, 'data-onlineAccess.phtml')
            ->setTemplateLine(
                'Related Items',
                'getAllRecordLinks',
                'data-allRecordLinks.phtml'
            )->setTemplateLine('Tags', true, 'data-tags.phtml');
        return $spec->getArray();
    }

    /**
     * Get default specifications for displaying data in collection-info metadata.
     *
     * @return array
     */
    protected function getDefaultCollectionInfoSpecs(): array
    {
        $spec = new SpecBuilder();
        $spec
            ->setMultiLine(
                'Authors',
                'getDeduplicatedAuthors',
                $this->getAuthorFunction()
            )->setLine('Summary', 'getSummary')
            ->setLine(
                'Format',
                'getFormats',
                'RecordHelper',
                ['helperMethod' => 'getFormatList']
            )->setLine(
                'Language',
                'getLanguages',
                null,
                $this->getLanguageLineSettings()
            )->setLine(
                'Language Notes',
                'getLanguageNote',
                null,
                ['itemPrefix' => '<span property="notesLanguage">',
                 'itemSuffix' => '</span>']
            )->setTemplateLine(
                'Published',
                'getPublicationDetails',
                'data-publicationDetails.phtml'
            )->setLine(
                'Edition',
                'getEdition',
                null,
                [
                    'itemPrefix' => '<span property="bookEdition">',
                    'itemSuffix' => '</span>',
                ]
            )->setTemplateLine('Series', 'getSeries', 'data-series.phtml')
            ->setTemplateLine(
                'Subjects',
                'getAllSubjectHeadings',
                'data-allSubjectHeadings.phtml'
            )->setTemplateLine('Online Access', true, 'data-onlineAccess.phtml')
            ->setTemplateLine(
                'Related Items',
                'getAllRecordLinks',
                'data-allRecordLinks.phtml'
            )->setLine('Notes', 'getGeneralNotes')
            ->setLine('Production Credits', 'getProductionCredits')
            ->setLine(
                'ISBN',
                'getISBNs',
                null,
                ['itemPrefix' => '<span property="isbn">', 'itemSuffix' => '</span>']
            )->setLine(
                'ISSN',
                'getISSNs',
                null,
                ['itemPrefix' => '<span property="issn">', 'itemSuffix' => '</span>']
            );
        return $spec->getArray();
    }
}
