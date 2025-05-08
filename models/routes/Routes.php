<?php 

class Route{
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllRoutes() {
        $sql = "SELECT * FROM routes";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRouteById($routeId) {
        $sql = "SELECT * FROM routes WHERE route_id = :routeId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':routeId' => $routeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}