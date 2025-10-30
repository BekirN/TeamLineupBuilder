# TeamLineupBuilder
# TeamLineupBuilder

**Team Lineup Builder** is a full-stack example project demonstrating **frontend-backend communication** between a **React-based frontend** and a **PHP backend**.  
The goal of this project is to show how CRUD operations can be performed through API calls, integrating a modern frontend with a traditional backend and database layer.

---

##  Table of Contents
- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Project Structure](#-project-structure)
- [Database Schema](#-database-schema)
- [Backend (PHP)](#-backend-php)
- [Frontend (React)](#-frontend-react)
- [API Endpoints](#-api-endpoints)
- [Setup Instructions](#-setup-instructions)
- [Future Improvements](#-future-improvements)
- [Author](#-author)

---

##  Overview

This project simulates a simple **Team Lineup Builder** app where users can:
- Select a sport.
- Create a new lineup.
- Add players manually or from a predefined list.
- View the **Top 10 most frequently selected players** across all lineups.

It demonstrates:
RESTful API design using PHP (FlightPHP-style structure).
Asynchronous operations and state management with React.
Database interaction via DAO (Data Access Object) pattern.
JSON-based communication between frontend and backend.

---

##  Features

Create new lineups by selecting a sport  
Add players to a lineup manually or from a list  
View current lineup and submit it  
Display top 10 most selected players (auto-updated)  
Persistent data through MySQL database  
lear frontend-backend separation for educational clarity  

---

##  Tech Stack

### **Frontend**
- React (with hooks)
- JavaScript 
- Fetch API for backend communication
- Basic inline & component-level styling

### **Backend**
- PHP (Object-Oriented)
- Custom DAO classes for database access
- REST-style PHP endpoints (`sports.php`, `lineup.php`, `players.php`)

### **Database**
- MySQL
- Three main tables:
  - `sports`
  - `lineups`
  - `players`

---

## 🗂 Project Structure

TeamLineupBuilder/
├── backend/
│ ├── BaseDao.php
│ ├── PlayerDao.php
│ ├── LineupDao.php
│ ├── SportDao.php
│ ├── players.php
│ ├── lineup.php
│ └── sports.php
│
├── frontend/
│ ├── src/
│ │ ├── api.js
│ │ ├── components/
│ │ │ └── LineupBuilder.jsx
│ │ └── App.js
│ └── package.json
│
└── README.md

php
Copy code

---

## Database Schema

### **sports**
| Field | Type | Description |
|-------|------|--------------|
| id | INT (PK) | Unique sport ID |
| name | VARCHAR | Sport name (e.g. Football, Basketball) |

### **lineups**
| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Lineup ID |
| sport_id | INT (FK) | Reference to `sports.id` |

### **players**
| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK) | Player ID |
| lineup_id | INT (FK) | Reference to `lineups.id` |
| name | VARCHAR | Player’s full name |

---

## Backend (PHP)

The backend follows the **DAO (Data Access Object)** pattern to ensure clean and reusable database access logic.

### **`PlayerDao.php`**
Handles CRUD operations for players:
- `insertOrUpdate($data)` → Adds a new player or updates existing entry.
- `getAllPlayers()` → Retrieves all players.
- `getPlayers($lineupId)` → Fetches players by lineup ID.
- `getPlayerByName($name)` → Finds player by name.
- `getTopPlayers($limit)` → Returns top players by selection count.

**Key logic example:**
```php
public function insertOrUpdate(array $data) {
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
        return $existing['id'];
    }

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
API endpoints (PHP files)
Each PHP file (e.g., players.php, lineup.php, sports.php) acts as an API endpoint that:

Accepts JSON requests (POST).

Returns JSON responses (GET).

Connects to its corresponding DAO for database interaction.

💻 Frontend (React)
Main Component: LineupBuilder.jsx
Handles UI logic, state, and communication with the backend.

Key functionalities:

Select a sport → calls createLineup()

Add player → calls addPlayer()

Load and display players from backend

Submit lineup validation (requires ≥ 3 players)

Show top players dynamically

Example:

js
Copy code
const handleAddPlayer = async (name) => {
  const result = await addPlayer({ lineup_id: lineupId, name });
  setLineup([...lineup, { id: result.player_id, name }]);
  loadTopPlayers(); 
};
API Endpoints
Endpoint	Method	Description
/sports.php	GET	Fetch all available sports
/lineup.php	POST	Create a new lineup for selected sport
/players.php	GET	Get all or filtered players (by lineup or top)
/players.php	POST	Add or update a player in the lineup

Example request:

json
Copy code
POST /players.php
{
  "lineup_id": 1,
  "name": "Kobe Bryant"
}
Example response:

json
Copy code
{
  "player_id": 12,
  "status": "inserted"
}
Setup Instructions
1. Clone Repository
bash
Copy code
git clone https://github.com/<your-username>/LineupTeamBuilder.git
cd LineupTeamBuilder
2. Backend Setup
Copy backend/ folder to your XAMPP htdocs directory.

Create a MySQL database named teamlineup.

Import your SQL schema.

Adjust DB credentials in BaseDao.php.

3. Frontend Setup
bash
Copy code
cd frontend
npm install
npm start
4. Configure API URL
In frontend/src/api.js, ensure:

js
Copy code
const API_URL = 'http://localhost/TeamLineupBuilder/backend';
5. Access App
Visit:
http://localhost:3000 (React frontend)
http://localhost/TeamLineupBuilder/backend/players.php (API test)



Purpose: Demonstration of full-stack communication between React and PHP using RESTful architecture.
Focus: Educational project showcasing CRUD, JSON exchange, and DAO design pattern.
