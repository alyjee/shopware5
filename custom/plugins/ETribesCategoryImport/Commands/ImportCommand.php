<?php

namespace ETribesCategoryImport\Commands;

use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ETribesCategoryImport\Components\ETribesCategoryImport\Utils\ImportCommandHelper;

class ImportCommand extends ShopwareCommand
{
    // TODO: Scaffolding category name should come from configuration
    // Validations on category insertion
    
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('et:import:category')
            ->setDescription('Import categories.')
            ->setHelp(<<<EOF
The <info>%command.name%</info> imports data from a file.
EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('models');

        $categoriesData = $this->getData();

        $helper = new ImportCommandHelper(
            [
                'categories' => $categoriesData,
                'username' => 'Commandline',
            ]
        );
        $helper->prepareImport();
        $helper->importAction();
    }

    public function getData()
    {
        return $json = '[{"title":"Introduction into Computer Science","product_line_area":"Microsoft"},{"title":"Introduction into Computer Science","product_line_area":"Microsoft"},{"title":"Introduction into Networks","product_line_area":"Cisco"},{"title":"Einführung in die OOP","lang":"de","product_line_area":"Coding Academy"},{"title":"Introduction into Engineering","product_line_area":"Microsoft"},{"title":"Introduction into Computer Science","product_line_area":"Cisco"},{"title":"Introduction into Databases","product_line_area":"Netapp"}]';
    }

}