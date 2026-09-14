<?php

return array (
  'vufind' =>
  array (
    'plugin_managers' =>
    array (
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