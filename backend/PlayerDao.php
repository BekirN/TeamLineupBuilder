<?php
require_once 'BaseDao.php';

class PlayerDao extends BaseDao {


    public function insertOrUpdate(array $data) {
        // Check if the player already exists in this lineup
        $stmt = $this->connection->prepare("
            SELECT id FROM players 
            WHERE LOWER(name) = LOWER(:name) AND lineup_id = :lineup_id
        ");
        $stmt->execute([
            ':name' => $data['name'],
            ':lineup_id' => $data['lineup_id']
        ]);

        $existing = $stmt->fetch();
        if ($existing) {
            // Player already in this lineup, do nothing
            return $existing['id'];
        }

        // Insert new player
        $stmt = $this->connection->prepare("
            INSERT INTO players (lineup_id, name) 
            VALUES (:lineup_id, :name)
        ");
        $stmt->execute([
            ':lineup_id' => $data['lineup_id'],
            ':name' => $data['name']
        ]);

        return $this->lastInsertId();
    }



    public function getAllPlayers() {
        $stmt = $this->connection->query("SELECT * FROM players ORDER BY name ASC");
        return $stmt->fetchAll();
    }
    
   
    public function getPlayers($lineupId = null) {
        if ($lineupId) {
            $stmt = $this->connection->prepare("SELECT * FROM players WHERE lineup_id = :lineup_id ORDER BY name ASC");
            $stmt->execute([':lineup_id' => $lineupId]);
            return $stmt->fetchAll();
        } else {
            return $this->getAllPlayers();
        }
    }

  
    public function getPlayerByName(string $name) {
        $stmt = $this->connection->prepare("SELECT * FROM players WHERE name = :name");
        $stmt->execute([':name' => $name]);
        return $stmt->fetch();
    }
    public function getTopPlayers($limit = 10) {
        $stmt = $this->connection->prepare("
            SELECT name, COUNT(DISTINCT lineup_id) as selections
            FROM players
            GROUP BY name
            ORDER BY selections DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
}
