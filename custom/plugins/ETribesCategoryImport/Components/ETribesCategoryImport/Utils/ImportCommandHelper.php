<?php

namespace ETribesCategoryImport\Utils;

class ImportCommandHelper
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $categories;

    /**
     * @var int
     */
    protected $sessionId;

    /**
     * @var \Shopware_Plugins_Backend_SwagImportExport_Bootstrap
     */
    protected $plugin;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param array $data
     *
     * @throws \RuntimeException
     */
    public function __construct(array $data)
    {
        $this->plugin = Shopware()->Plugins()->Backend()->ETribesCategoryImport();

        if (!isset($data['$categories'])) {
            throw new \RuntimeException('No categories given!');
        }

        if (isset($data['username'])) {
            $this->username = $data['username'];
        }

    }

    /**
     * Prepares import
     *
     * @throws \Exception
     *
     * @return array
     */
    public function prepareImport()
    {
        /** @var DataFactory $dataFactory */
        $dataFactory = $this->plugin->getDataFactory();

        $dbAdapter = $dataFactory->createDbAdapter('category');
    }
}
