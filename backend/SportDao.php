<?php
require_once 'BaseDao.php';

class SportDao extends BaseDao {
    public function getAll() {
        $stmt = $this->connection->query("SELECT * FROM sports");
        return $stmt->fetchAll();
    }
}
