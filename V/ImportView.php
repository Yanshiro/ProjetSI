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
        <input name="controller" value="Import" type="hidden"/> 
        <input name="f" value="importExcel" type="hidden"/> 
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