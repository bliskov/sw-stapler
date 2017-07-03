<?php

namespace KjBeDataIntegration\Business\Writer;

class CsvFileWriter extends AbstractFileWriter
{

    const DELIMITER = ';';

    /**
     * @param string $filePath
     * @param array $data
     * @param bool $append
     *
     * @return void
     */
    public function write($filePath, $data, $append = false)
    {
        $this->createDirectory($filePath);

        if ($append) {
            $filePointer = fopen($filePath, 'a');
        } else {
            $filePointer = fopen($filePath, 'w');
            fputs($filePointer, "\xEF\xBB\xBF"); // UTF-8 BOM
        }

        foreach ($data as $row) {
            fputcsv($filePointer, $row, self::DELIMITER);
            //fputs($filePointer, implode(self::DELIMITER, array_map(array($this, 'encodeFnc'), $row)) . "\r\n");
        }

        fclose($filePointer);
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function encodeFnc($value)
    {
        $value = str_replace('\\"', '"', $value);
        $value = str_replace('"', '\"', $value);

        return '"' . $value . '"';
    }
}

