<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php
    $parser = new \App\Services\Parsers\RusprofileParser();
    $companies = $parser->parseCompanies();
    echo count($companies);
?>
</body>
</html>
