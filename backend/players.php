<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once 'PlayerDao.php';
$dao = new PlayerDao();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['top']) && $_GET['top'] == 1) {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        try {
            $topPlayers = $dao->getTopPlayers($limit);
            echo json_encode($topPlayers);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch top players']);
        }
        exit();
    }

   
    try {
        $players = $dao->getAllPlayers();
        echo json_encode($players);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch players']);
    }
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['lineup_id']) && !empty($data['name'])) {
        try {
           
            $id = $dao->insertOrUpdate($data); 
            echo json_encode(['player_id' => $id]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'lineup_id and name required']);
    }
    exit();
}
