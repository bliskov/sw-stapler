<?php

namespace KjBeDataIntegration\Business\Model\Adapter;

use \PDO;
use Shopware\Components\DependencyInjection\Bridge\Db;

class PdoAdapter
{

    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * PdoAdapter constructor.
     */
    public function __construct()
    {
        $config = $this->loadConfiguration($this->generateConfigFileName());
        $dbConn = $config[PdoAdapterConstants::PDO_ADAPTER_CONFIG_KEY];

        $this->connection = Db::createPDO($dbConn);
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    protected function generateConfigFileName()
    {
        return realpath(__DIR__ . '/../../../../../../') . '/config.php';
    }

    /**
     * @param $configPath
     *
     * @return bool|mixed
     */
    protected function loadConfiguration($configPath)
    {
        if (!is_file($configPath)) {
            return false;
        }

        $content = require $configPath;

        return $content;
    }

}
