<?php

namespace ETribesCategoryImport\Components\DbAdapters;

use ETribesCategoryImport\Components\DataManagers\CategoriesDataManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Shopware\Components\SwagImportExport\Service\UnderscoreToCamelCaseServiceInterface;
use Shopware\Components\SwagImportExport\Validators\CategoryValidator;
use Shopware\Models\Category\Category;
use ETribesCategoryImport\Components\ETribesCategoryImport\Utils\SnippetsHelper;

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

    /**
     * @var const
     */
    const SCAFFOLDING_CATEGORY_ID = 1;

    public function __construct()
    {
        $this->modelManager = Shopware()->Container()->get('models');
        $this->repository = $this->modelManager->getRepository(Category::class);
        $this->db = Shopware()->Db();
        $this->dataManager = new CategoriesDataManager();
    }

    public function createCategoryWithParent($categoryName, $parentName)
    {
        $parentCategory = $this->findCategoryByName($parentName);
        if(!$parentCategory instanceof Category){
            throw Exception("Parent not found");
        }
        $category = $this->findCategoryByName($categoryName);
        if(!$category instanceof Category){
            # create category
            $record['name'] = $categoryName;
            $record = $this->dataManager->setDefaultFieldsForCreate($record, $this->defaultValues);
            $categoryModel = $this->createCategory($record);
            $categoryModel->setParent($parentCategory);
            $this->modelManager->persist($categoryModel);
            $this->modelManager->flush();
        }
        $category = $this->findCategoryByName($categoryName);
        $currentParentId = $category->getParentId();
        $expectedParentId = $parentCategory->getId();

        echo "currentParentId => $currentParentId, expectedParentId => $expectedParentId".PHP_EOL;

        if(
            $currentParentId == CategoriesDbAdapter::SCAFFOLDING_CATEGORY_ID 
            && CategoriesDbAdapter::SCAFFOLDING_CATEGORY_ID != $expectedParentId
        ){
            # update the parent
            $category->setParent($parentCategory);
            $this->modelManager->flush();
        }
        
        return $category;
    }

    /**
     * Insert/Update data into db
     *
     * @param array $category
     */
    public function write($category)
    {
        echo "start".PHP_EOL;
        print_r($category);

        $this->validateCategoryShouldNotBeEmpty($category);

        $this->createCategoryWithParent($category['parentName'], 'Root');
        $this->createCategoryWithParent($category['categoryName'], $category['parentName']);

        return;
    }

    /**
     * @param array $categoryData
     *
     * @return Category
     */
    private function createCategory($categoryData)
    {
        print_r($categoryData);
        $category = new Category();
        foreach($categoryData as $key=>$val){
            // Todo use a service to convert under_scored attributes to cameCase
            $setter = 'set'.ucfirst($key);
            $category->{$setter}($val);
        }

        return $category;
    }

    /**
     * Create the Category by hand. The method ->fromArray do not work
     *
     * @param mixed $catName
     *
     * @return Category|null
     */
    private function findCategoryByName($catName)
    {
        if ($catName === null) {
            return null;
        }

        return $this->repository->findOneByName($catName);
    }

    /**
     * Get the Category by id.
     *
     * @param int $id
     *
     * @return Category|null
     */
    private function findCategoryById($id)
    {
        if ($id === null) {
            return null;
        }

        return $this->repository->find($id);
    }

    /**
     * Get the Category by name and parent id.
     *
     * @param int $id
     *
     * @return Category|null
     */
    private function findCategoryByIdWithParent($categoryName, $parentId)
    {
        if ($categoryName === null || $parentId === null) {
            return null;
        }

        return $this->repository->findOneBy(["name"=>$categoryName, "parentId"=>$parentId]);
    }
    
    /**
     * @param array $category
     *
     * @throws \Exception
     */
    private function validateCategoryShouldNotBeEmpty($category)
    {
        if (empty($category)) {
            $message = SnippetsHelper::getNamespace()
                ->get('adapters/categories/no_records', 'No category record found.');
            throw new \Exception($message);
        }
    }
}
