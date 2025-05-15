<?php
require_once "../services/leitor_pdf_service.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    $itensEncontrados = processarPDF($_FILES['pdf']['tmp_name']);
    echo json_encode(['success' => true, 'data' => $itensEncontrados]);
} else {
    echo json_encode(['success' => false, 'message' => 'Arquivo PDF não enviado.']);
}
