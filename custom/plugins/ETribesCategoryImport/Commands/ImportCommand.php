<?php

namespace ETribesCategoryImport;

use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ShopwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('et:import:category')
            ->setDescription('Import categories.')
            ->addArgument(
                'filepath',
                InputArgument::REQUIRED,
                'Path to file to read data from.'
            )
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
        $filePath = $input->getArgument('filepath');

        $em = $this->container->get('models');

        $output->writeln('<info>'.sprintf("Got filepath: %s.", $filePath).'</info>');
    }
}