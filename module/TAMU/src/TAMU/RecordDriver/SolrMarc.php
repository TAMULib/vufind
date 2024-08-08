<?php

namespace TAMU\RecordDriver;

class SolrMarc extends \VuFind\RecordDriver\SolrMarc
{
    /**
    * Get the uniform title from the 130a field
    */
    public function getUniformTitle()
    {
        return $this->fields['uniform_title_str_mv'] ?? [];
    }

    /**
     * Get an array of all the language notes associated with the record.
     *
     * @return array
     */
    public function getLanguageNotes()
    {
        return (array)($this->fields['language-notes'] ?? []);
    }
}
