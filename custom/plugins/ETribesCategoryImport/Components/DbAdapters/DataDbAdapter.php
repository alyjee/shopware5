<?php

namespace ETribesCategoryImport\Components\DbAdapters;

interface DataDbAdapter
{
    const CATEGORIES_ADAPTER = 'categories';

    /**
     * Creates, updates and validates the imported records.
     *
     * @param array $records
     */
    public function write($records);
}
