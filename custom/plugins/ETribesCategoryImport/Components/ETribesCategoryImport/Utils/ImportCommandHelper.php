<?php

namespace ETribesCategoryImport\Components\ETribesCategoryImport\Utils;

use ETribesCategoryImport\Components\DbAdapters\CategoriesDbAdapter;

class ImportCommandHelper
{
    protected $categoriesData;

    public function __construct(array $data)
    {
        if (!isset($data['categories'])) {
            throw new \RuntimeException('No categories given!');
        }

        $this->categoriesData = $data['categories'];

        if (isset($data['username'])) {
            $this->username = $data['username'];
        }

    }

    public function getPreparedRecord($key, $val, $record)
    {
        if(!isset($record['lang'])){
            // TODO : get category default lang from CategoriesDbAdapter
            $record['lang'] = 'en';
        }

        $_preparared_model = [
            'categoryName' => null,
            'parentName' => null
        ];

        $_preparared_model['categoryName'] = $val;

        switch ($key) {
            case 'lang':
                // TODO: add from constant
                $_preparared_model['parentName'] = 'Root';
                break;
            case 'product_line_area':
                $_preparared_model['parentName'] = $record['lang'];
                break;
            case 'title':
                $_preparared_model['parentName'] = $record['product_line_area'];
                break;
            
            default:
                # code...
                break;
        }

        return $_preparared_model;
    }

    public function prepareImport()
    {
        $records = json_decode($this->categoriesData, true);
        $this->categoriesData = [];
        foreach($records as $record) {
            if(!isset($record['lang'])){
                $record['lang'] = 'en';
            }
            foreach ($record as $key => $val) {
                array_push($this->categoriesData, $this->getPreparedRecord($key, $val, $record));
            }
        }
    }

    public function importAction()
    {
        $dbAdapter = new CategoriesDbAdapter();
        foreach ($this->categoriesData as $category) {
            $dbAdapter->write($category);
        }
    }
}
