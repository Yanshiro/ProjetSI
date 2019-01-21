<?php
date_default_timezone_set('UTC');

class ImportController
{
    public function importExcel($paramGet, $paramPost)
    {
        require_once 'lib/Classes/PHPExcel/IOFactory.php';
        if (isset($_FILES['excel'])) {
            $tmpfname = $_FILES['excel']['tmp_name'];
            $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        //$excelObj = PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);
            $excelObj = $excelReader->load($tmpfname);
            $getSheet = $excelObj->getActiveSheet()->toArray(null);
            $excelObj = $excelReader->load($tmpfname);
            $worksheet = $excelObj->getSheet(0);
            $lastRow = $worksheet->getHighestRow();
            $lastCol = $worksheet->getHighestColumn();
            $colNumber = PHPExcel_Cell::columnIndexFromString($lastCol);
            echo "<table>";
            for ($row = 1; $row < $lastRow; $row++) {
                for ($cell = 1; $cell < $colNumber; $cell++) {
                    echo "<tr><td>";
                    echo $worksheet->getCellByColumnAndRow($row, $cell)->getValue();
                    echo "</td><td>";
                }
            }
            echo "</table>";
        }
    }

    public function show($paramGet, $paramPost)
    {
        return include("V/ImportView.php");
    }
}