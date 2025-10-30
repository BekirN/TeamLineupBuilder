<?php
require_once 'BaseDao.php';

class LineupDao extends BaseDao {
    public function createLineup($sportId) {
        $stmt = $this->connection->prepare("INSERT INTO lineups (sport_id) VALUES (:sport_id)");
        $stmt->execute(['sport_id' => $sportId]);
        return $this->lastInsertId();
    }
}
