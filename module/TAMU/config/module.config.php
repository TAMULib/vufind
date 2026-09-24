<?php

return array (
  'vufind' =>
  array (
    'plugin_managers' =>
    array (
      'recorddataformatter_specs' =>
      array (
        'factories' =>
        array (
          'TAMU\\RecordDataFormatter\\Specs\\DefaultRecord' => 'VuFind\\RecordDataFormatter\\Specs\\DefaultRecordFactory',
        ),
        'aliases' =>
        array (
          'VuFind\\RecordDataFormatter\\Specs\\DefaultRecord' => 'TAMU\\RecordDataFormatter\\Specs\\DefaultRecord',
        ),
      ),
      'ils_driver' =>
      array (
        'factories' =>
        array (
          'TAMU\\ILS\\Driver\\Folio' => 'VuFind\\ILS\\Driver\\FolioFactory',
        ),
        'aliases' =>
        array (
          'VuFind\\ILS\\Driver\\Folio' => 'TAMU\\ILS\\Driver\\Folio',
        ),
      ),
      'recorddriver' =>
      array (
        'factories' =>
        array (
          'TAMU\\RecordDriver\\SolrMarc' => 'TAMU\\RecordDriver\\SolrDefaultFactory',
        ),
        'aliases' =>
        array (
          'VuFind\\RecordDriver\\SolrMarc' => 'TAMU\\RecordDriver\\SolrMarc',
        ),
        'delegators' =>
        array (
          'TAMU\\RecordDriver\\SolrMarc' =>
          array (
            0 => 'TAMU\\RecordDriver\\IlsAwareDelegatorFactory',
          ),
        ),
      ),
    ),
  ),
);