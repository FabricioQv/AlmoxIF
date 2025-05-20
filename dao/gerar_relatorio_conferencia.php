<?php
require '../vendor/autoload.php';
require_once "../dao/ItemDAO.php";
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

$itemDAO = new ItemDAO();
$itens = $itemDAO->listarItensComEstoque();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Cabeçalhos
$sheet->setCellValue('A1', 'Código');
$sheet->setCellValue('B1', 'Material de Consumo');
$sheet->setCellValue('C1', 'Estoque');
$sheet->setCellValue('D1', 'Unidade');
$sheet->setCellValue('E1', 'Fichas');
$sheet->setCellValue('F1', 'Físico');
$sheet->setCellValue('G1', 'Observações');

// Estilo do cabeçalho
$headerStyle = $sheet->getStyle('A1:G1');
$headerStyle->getFont()->setBold(true);
$headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D4F4DD');

// Ajuste das colunas
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setWidth(40);
$sheet->getStyle('B')->getAlignment()->setWrapText(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// Preenchimento dos dados
$row = 2;
foreach ($itens as $item) {
    $sheet->setCellValue("A{$row}", $item["codigo"]);
    $sheet->setCellValue("B{$row}", $item["nome"]);
    $sheet->setCellValue("C{$row}", $item["estoque"]);
    $sheet->setCellValue("D{$row}", $item["unidade"]);
    $sheet->setCellValue("E{$row}", '');
    $sheet->setCellValue("F{$row}", '');
    $sheet->setCellValue("G{$row}", '');
    $row++;
}

// Aplica bordas em todas as células utilizadas
$ultimaLinha = $row - 1;
$sheet->getStyle("A1:G$ultimaLinha")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

$filename = "relatorio_material_consumo_" . date("Ymd_His") . ".xlsx";
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"$filename\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
