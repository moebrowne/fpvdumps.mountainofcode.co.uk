<?php

require __DIR__ . '/bootstrap.php';

$id = $_GET['id'];

$jsonString = file_get_contents(__DIR__ . '/dumps.json');
$dumps = json_decode($jsonString);

foreach ($dumps as $dump) {
    if ($dump->id !== $id) {
        continue;
    }

    $dump = Dump::fromData($dump);

    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-Disposition: attachment; filename=\"" . $dump->getDownloadFileName() . "\"");
    header("Content-Length: " . mb_strlen($dump->getContent()));
    echo $dump->getContent();

    exit;
}

header("HTTP/1.0 404 Not Found");
echo 'Unable to find config dump';