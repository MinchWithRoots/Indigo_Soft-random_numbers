<?php

// подключение автозагрузчика composer
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// проверка наличия библиотеки phpspreadsheet
if (!class_exists(Spreadsheet::class)) {
    die("ошибка: библиотека phpspreadsheet не установлена. выполните: composer install\n");
}

// настройки генерации файла
$outputDir = __DIR__ . '/excel_files';
$fileName  = 'random_numbers.xlsx';
$fullPath  = $outputDir . '/' . $fileName;
$range     = 'A1:J10'; // фиксированный диапазон 10×10

// проверка существования выходного файла
if (file_exists($fullPath)) {
    echo "файл уже существует: $fullPath\n";
    exit(0);
}

// создание директории для вывода
if (!is_dir($outputDir)) {
    if (!mkdir($outputDir, 0755, true)) {
        die("не удалось создать директорию: $outputDir\n");
    }
}

// генерация данных: 10 строк по 10 случайных чисел (1–100)
$data = [];
for ($i = 0; $i < 10; $i++) {
    $data[] = array_map(fn() => random_int(1, 100), range(1, 10));
}

// создание и заполнение таблицы
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->fromArray($data, null, 'A1');

// применение стилей: обводка + центрирование
$sheet->getStyle($range)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
]);

// автоматическая ширина колонок (a–j)
foreach (range('A', 'J') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// сохранение файла
$writer = new Xlsx($spreadsheet);
$writer->save($fullPath);

echo "файл успешно создан: $fullPath\n";