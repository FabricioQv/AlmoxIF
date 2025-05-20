<?php
require '../vendor/autoload.php';
require_once "../dao/MovimentoDAO.php";
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

$dataInicio = $_POST["dataInicio"] ?? date("Y-m-d");
$dataFim = $_POST["dataFim"] ?? date("Y-m-d");

$movimentoDAO = new MovimentoDAO();
$dados = $movimentoDAO->gerarRelatorioMovimentacao($dataInicio, $dataFim);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Cabeçalhos
$sheet->setCellValue('A1', 'Item');
$sheet->setCellValue('B1', 'Estoque Inicial');
$sheet->setCellValue('C1', 'Entradas');
$sheet->setCellValue('D1', 'Saídas');
$sheet->setCellValue('E1', 'Estoque Final');

// Estilo do cabeçalho
$headerStyle = $sheet->getStyle('A1:E1');
$headerStyle->getFont()->setBold(true);
$headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D4F4DD');

// Ajustes de colunas
$sheet->getColumnDimension('A')->setWidth(40); // Item
$sheet->getStyle('A')->getAlignment()->setWrapText(true);
$sheet->getDefaultRowDimension()->setRowHeight(-1);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);

// Preenchimento dos dados
$row = 2;
foreach ($dados as $linha) {
    $sheet->setCellValue("A{$row}", $linha["item_nome"]);
    $sheet->setCellValue("B{$row}", $linha["estoque_inicial"] ?? 0);
    $sheet->setCellValue("C{$row}", $linha["total_entrada"] ?? 0);
    $sheet->setCellValue("D{$row}", $linha["total_saida"] ?? 0);
    $sheet->setCellValue("E{$row}", $linha["estoque_final"] ?? 0);
    $row++;
}

// Aplica bordas nas células preenchidas
$ultimaLinha = $row - 1;
$sheet->getStyle("A1:E$ultimaLinha")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Exporta o arquivo
$filename = "relatorio_estoque_" . date("Ymd_His") . ".xlsx";
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"$filename\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
