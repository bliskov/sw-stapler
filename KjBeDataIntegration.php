<?php

namespace KjBeDataIntegration;

use KjBeDataIntegration\Commands\ExtractCommand;
use Shopware\Components\Console\Application;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KjBeDataIntegration extends Plugin
{

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        parent::install($context);
    }

    /**
     * @param Application $application
     */
    public function registerCommands(Application $application)
    {
        $application->add(new ExtractCommand());
    }

}
