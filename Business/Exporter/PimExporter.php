<?php

namespace KjBeDataIntegration\Business\Exporter;

use KjBeDataIntegration\Business\Writer\CsvFileWriter;
use KjBeDataIntegration\Exception\MissingArrayKeyException;
use KjBeDataIntegration\Business\Model\Adapter\PdoAdapter;
use KjBeDataIntegration\Persistence\PimQueryContainer;
use Shopware\Components\DependencyInjection\Bridge\Db;

class PimExporter
{

    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * @var CsvFileWriter
     */
    protected $fileWriter;

    /**
     * @var PimQueryContainer
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $header;

    public function __construct()
    {
        $this->connection = $this->createPdoAdapter()->getConnection();
        $this->queryContainer = $this->createQueryContainer();
        $this->fileWriter = new CsvFileWriter();
        $this->header = [];
    }

    /**
     * @return PdoAdapter
     */
    protected function createPdoAdapter()
    {
        return new PdoAdapter();
    }

    /**
     * @param $entity
     * @param $filePath
     */
    public function createCsvFile($entity, $filePath)
    {
        $this->header = $this->getHeaderColumns($entity);

        $this->getPreDataHook($entity);
        $data = $this->getPreparedData($entity);
        $data = $this->getPostDataHook($data, $entity);

        $this->saveFileHeader($filePath, $this->header);

        do {
            $this->fileWriter
                ->write($filePath, $data, true);
        } while (!empty($result));
    }

    /**
     * @param $entity
     */
    protected function getPreDataHook($entity)
    {
        if ('manufacturer' === mb_strtolower($entity) || 'vehicle' === mb_strtolower($entity)) {
            $this->connection->exec("SET @rank=0");
        }
    }

    /**
     * @param array $data
     * @param $entity
     *
     * @throws MissingArrayKeyException
     *
     * @return array
     */
    protected function getPostDataHook(array $data, $entity)
    {
        if ('battery' === mb_strtolower($entity)) {

            $this->addAvaiabilityHeaderColumns();
            $newData = [];

            foreach ($data as $key => $val) {

                if (!isset($val['articleID']) or !isset($val['name'])) {
                    throw new MissingArrayKeyException("articleID aka battery.id is undefined");
                }

                // additional values
                $query = $this->connection->query(
                    $this->queryContainer->getAvailabilityQueryByBatteryName(
                        $val['name'],
                        $val['voltage'],
                        $val['capacity']
                    )
                );

                $availabilityValues = $query->fetchAll();
                if (!count($availabilityValues)) {
                    // @todo: Log data as not completed set
                    unset($data[$key]);
                    continue;
                }

                // relational values product 2 category
                $vehicleQuery = $this->connection->query(
                    $this->queryContainer->getVehiclesByBatteryId($val['articleID'])
                );

                $vehicleIdValues = $vehicleQuery->fetchAll();

                if (!count($vehicleIdValues)) {
                    foreach ($availabilityValues as $aKey => $aVal) {
                        $tmp = array_merge($val, $aVal);
                        $tmp['categories'] = "";
                        $tmp['ordernumber'] = $tmp['mainnumber'];
                        $data[$key] = $tmp;
                    }
                    continue;
                }

                /*
                $first = true;
                foreach ($vehicleIdValues as $vehicleIds) {

                    foreach ($availabilityValues as $aKey => $aVal) {
                        $tmp = array_merge($val, $aVal);
                    }

                    $tmp['categories'] = $vehicleIds['categories'];
                    $tmp['ordernumber'] = $tmp['mainnumber'];

                    ($first) ? $data[$key] = $tmp : $newData[] = $tmp;
                    $first = false;
                }
                */

                    $vehicleIds = "";
                    $firstVehicle = true;

                    if(!count($vehicleIdValues)) $vehicleIds = "1";
                    foreach ($vehicleIdValues as $array) {
                        ($firstVehicle) ? $firstVehicle = false : $vehicleIds .= '|';
                        $vehicleIds .= $array['categories'];
                    }

                    foreach ($availabilityValues as $aKey => $aVal) {
                        $tmp = array_merge($val, $aVal);
                        $tmp['categories'] = $vehicleIds;
                        $tmp['ordernumber'] = $tmp['mainnumber'];
                        $data[$key] = $tmp;
                    }

            }

            $data = array_merge($data, $newData);
        }

        return $data;
    }

    protected function addAvaiabilityHeaderColumns()
    {
        $headerColumns = $this->queryContainer->getAdditionalBatteryHeaderColumns();
        $this->header = array_merge($this->header, $headerColumns);
    }

    /**
     * @param $entity
     *
     * @return array
     */
    protected function getPreparedData($entity)
    {
        $queryFnc = $this->getQueryMethodName($entity);

        $query = $this->connection->query(
            $this->queryContainer->$queryFnc()
        );

        return $query->fetchAll();
    }

    /**
     * @param $entity
     * @param string $postfix
     *
     * @return string
     */
    protected function getQueryMethodName($entity, $postfix = "Query")
    {
        return $queryFnc = "get" . ucfirst($entity) . $postfix;
    }

    /**
     * @param $filePath
     * @param array $header
     */
    protected function saveFileHeader($filePath, array $header)
    {
        $this->fileWriter->write($filePath, [$header]);
    }

    /**
     * @return PimQueryContainer
     */
    protected function createQueryContainer()
    {
        return new PimQueryContainer();
    }

    /**
     * @param PimQueryContainer $queryContainer
     * @param $entity
     *
     * @return array
     */
    protected function getHeaderColumns($entity)
    {
        $getter = $this->getQueryMethodName($entity, "Columns");

        return array_values($this->queryContainer->$getter());
    }

}
