<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


require_once 'SportDao.php';

try {
    $dao = new SportDao();
    echo json_encode($dao->getAll());
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
