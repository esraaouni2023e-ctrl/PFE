<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('storage/app/excels/filieres_data.xlsx');
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray();
// filter out empty columns for the first row
$headers = array_filter($data[0]);
$firstRow = array_intersect_key($data[1], $headers);
echo json_encode([
    'headers' => array_values($headers),
    'firstRow' => array_values($firstRow)
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
