<?php

namespace KjBeDataIntegration\Commands;

use KjBeDataIntegration\Business\Exporter\PimExporter;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ExtractCommand extends ShopwareCommand
{
    const CODE_SUCCESS = 0;
    const CODE_ERROR = 1;
    const COMMAND_NAME = 'kjbe:extract';
    const DESCRIPTION = 'Extract data from PIM database.';
    const EXTRACT_ENTITES = [
        'manufacturer',
        'vehicle',
        'battery'
    ];

    /**
     * @var \Psr\Log\LoggerInterface|\Symfony\Component\Console\Logger\ConsoleLogger
     */
    protected $messenger;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION)
            ->addArgument(
                'filepath',
                InputArgument::REQUIRED,
                'Path to file to export data to.'
            )
            ->addArgument(
                'entity',
                InputArgument::REQUIRED,
                'Entity to extract.'
            )
            ->setHelp(
                <<<EOF
                The <info>%command.name%</info> imports data from a file.
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $messenger = $this->getMessenger();

        $messenger->info(
            sprintf(
                'Start %s to extract data from database and write csv',
                static::COMMAND_NAME
            )
        );

        $filePath = $input->getArgument('filepath');
        $entity = mb_strtolower($input->getArgument('entity'));

        if (!in_array($entity, static::EXTRACT_ENTITES)) {
            $output->writeln('<error>' . sprintf("entity %s arg is unknown", $entity) . '</error>');

            return static::CODE_ERROR;
        }
        $this->createPimExporter()->createCsvFile($entity,$filePath);

        $output->writeln('<info>' . sprintf("Got filepath: %s.", $filePath) . '</info>');
        return static::CODE_SUCCESS;
    }

    /**
     * @return PimExporter
     */
    protected function createPimExporter()
    {
        return new PimExporter();
    }

    /**
     * @return \Psr\Log\LoggerInterface|\Symfony\Component\Console\Logger\ConsoleLogger
     */
    protected function getMessenger()
    {
        if ($this->messenger === null) {
            $this->messenger = new ConsoleLogger($this->output);
        }

        return $this->messenger;
    }

}
