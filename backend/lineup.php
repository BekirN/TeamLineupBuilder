<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


require_once 'LineupDao.php';

$dao = new LineupDao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data['sport_id'])) {
        $id = $dao->createLineup($data['sport_id']);
        echo json_encode(['id' => $id]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'sport_id is required']);
    }
}
