<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = __DIR__ . '/public/images/Laporan_645917_01_07_2026_31_07_2026.xls';

$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

echo "Total rows: " . count($rows) . "\n\n";

// Show first 20 rows to understand structure
echo "=== First 20 rows ===\n";
for ($i = 0; $i < min(20, count($rows)); $i++) {
    echo "Row " . ($i + 1) . ": " . json_encode($rows[$i], JSON_UNESCAPED_UNICODE) . "\n";
}

echo "\n=== Last 5 rows ===\n";
for ($i = max(0, count($rows) - 5); $i < count($rows); $i++) {
    echo "Row " . ($i + 1) . ": " . json_encode($rows[$i], JSON_UNESCAPED_UNICODE) . "\n";
}

// Try to find header row
echo "\n=== Searching for header row ===\n";
foreach ($rows as $idx => $row) {
    $rowStr = implode('|', array_filter($row));
    if (stripos($rowStr, 'tanggal') !== false || stripos($rowStr, 'barang') !== false || stripos($rowStr, 'nama') !== false) {
        echo "Possible header at row " . ($idx + 1) . ": " . json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
}
