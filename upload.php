<?php

require __DIR__ . '/bootstrap.php';

$jsonString = file_get_contents('php://input');
$data = json_decode($jsonString);


if (empty(trim($data->make)) === true || empty(trim($data->model)) === true || empty(trim($data->dump)) === true) {
    echo json_encode(['success' => false]);
    die();
}

$dump = new Dump(guidv4(), $data->make, $data->model, $data->dump);

storeDump($dump);

echo json_encode([
    'success' => true,
    'data' => $dump->toArray(),
]);


