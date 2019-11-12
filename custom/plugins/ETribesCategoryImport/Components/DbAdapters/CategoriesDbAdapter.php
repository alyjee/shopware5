<?php

namespace ETribesCategoryImport\Components\DbAdapters;

use Shopware\Components\SwagImportExport\DataManagers\CategoriesDataManager;
use Shopware\Components\SwagImportExport\Service\UnderscoreToCamelCaseServiceInterface;
use Shopware\Components\SwagImportExport\Validators\CategoryValidator;
use Shopware\Models\Category\Category;

class CategoriesDbAdapter implements DataDbAdapter
{
    /**
     * @var \Shopware\Components\Model\ModelManager
     */
    protected $modelManager;

    /**
     * @var \Enlight_Components_Db_Adapter_Pdo_Mysql
     */
    protected $db;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $unprocessedData;

    /**
     * @var array
     */
    protected $logMessages;

    /**
     * @var string
     */
    protected $logState;

    /**
     * @var CategoryValidator
     */
    protected $validator;

    /**
     * @var CategoriesDataManager
     */
    protected $dataManager;

    /**
     * @var array
     */
    protected $defaultValues;

    private $categoryAvoidCustomerGroups;

    /**
     * @var UnderscoreToCamelCaseServiceInterface
     */
    private $underscoreToCamelCaseService;

    public function __construct()
    {
        $this->modelManager = Shopware()->Container()->get('models');
        $this->repository = $this->modelManager->getRepository(Category::class);
        $this->db = Shopware()->Db();
    }
}
