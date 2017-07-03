<?php

namespace KjBeDataIntegration\Business\Writer;


abstract class AbstractFileWriter
{
    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param string $filePath
     * @param array $data
     * @param bool $append
     *
     * @return void
     */
    abstract public function write($filePath, $data, $append = false);

    /**
     * @param string $fileName
     *
     * @return void
     */
    protected function createDirectory($fileName)
    {
        $pathInfo = pathinfo($fileName);
        $directory = $pathInfo['dirname'];

        if (!file_exists($directory)) {
            mkdir($directory);
        }
    }

}
