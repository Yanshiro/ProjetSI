<?php

date_default_timezone_set('UTC');
require_once 'Classes/PHPExcel/IOFactory.php';
if(isset($_FILES['excel']))
{
    $tmpfname=$_FILES['excel']['tmp_name'];
    $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
    //$excelObj = PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);
    $excelObj = $excelReader->load($tmpfname);
    $getSheet=$excelObj->getActiveSheet()->toArray(null);
    $excelObj = $excelReader->load($tmpfname);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $lastCol = $worksheet->getHighestColumn();
    $colNumber = PHPExcel_Cell::columnIndexFromString($lastCol);
    echo "<table>";
    for ($row = 1; $row < $lastRow; $row++) {
        for($cell = 1;$cell<$colNumber;$cell++)
        {
        echo "<tr><td>";
        echo $worksheet->getCellByColumnAndRow($row, $cell)->getValue();  
        echo "</td><td>";
        }
    }
    echo "</table>";	
}
?>
<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<!-- entête du document -->
<head>
	<meta charset="UTF-8"/> 
	<title>Home</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.css">
    <link rel="stylesheet" type="text/css" href="Site.css">
</head>
<body>
<h1 class="titre">Import fichier csv</h1><hr class="hr1">
<div class="container">
    <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <div class="col-md-1"></div>
                <label class="col-md-4 control-label">
                CSV
                </label>
            <div class="col-md-3">
                <input name="excel" type="file" class="form-control"/> 
            </div>
        </div>
        <div class="col-md-1"></div>
                <label class="col-md-4 control-label"></label>
                <div class="col-md-3">
                    <input type="submit" value="Submit" class="form-control"/>                
                </div>
</div>
    </form>
</div>
</body>
</html>
