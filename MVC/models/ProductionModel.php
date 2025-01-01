<?php
class ProductionModel {
    private $db;
    private $table = 'production_schedules';
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getSchedule($id) {
        $sql = "SELECT ps.*, r.name as recipe_name, u.name as baker_name 
                FROM {$this->table} ps 
                JOIN recipes r ON ps.recipe_id = r.id 
                JOIN users u ON ps.assigned_baker = u.id 
                WHERE ps.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($schedule) {
            $schedule['equipment_needed'] = json_decode($schedule['equipment_needed'], true);
        }
        
        return $schedule;
    }
    
    public function getDailySchedule($date) {
        $sql = "SELECT ps.*, r.name as recipe_name, u.name as baker_name 
                FROM {$this->table} ps 
                JOIN recipes r ON ps.recipe_id = r.id 
                JOIN users u ON ps.assigned_baker = u.id 
                WHERE DATE(ps.production_date) = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($schedules as &$schedule) {
            $schedule['equipment_needed'] = json_decode($schedule['equipment_needed'], true);
        }
        
        return $schedules;
    }
    
    public function createSchedule($data) {
        $sql = "INSERT INTO {$this->table} 
                (recipe_id, order_id, production_date, start_time, end_time, 
                quantity, assigned_baker, equipment_needed, status, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['recipe_id'],
            $data['order_id'],
            $data['production_date'],
            $data['start_time'],
            $data['end_time'],
            $data['quantity'],
            $data['assigned_baker'],
            json_encode($data['equipment_needed']),
            'scheduled',
            $data['created_by']
        ]);
    }
    
    public function updateSchedule($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET recipe_id = ?, order_id = ?, production_date = ?, 
                    start_time = ?, end_time = ?, quantity = ?, 
                    assigned_baker = ?, equipment_needed = ?, status = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['recipe_id'],
            $data['order_id'],
            $data['production_date'],
            $data['start_time'],
            $data['end_time'],
            $data['quantity'],
            $data['assigned_baker'],
            json_encode($data['equipment_needed']),
            $data['status'],
            $id
        ]);
    }
    
    public function getBakerSchedule($baker_id, $date) {
        $sql = "SELECT ps.*, r.name as recipe_name 
                FROM {$this->table} ps 
                JOIN recipes r ON ps.recipe_id = r.id 
                WHERE ps.assigned_baker = ? AND DATE(production_date) = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$baker_id, $date]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($schedules as &$schedule) {
            $schedule['equipment_needed'] = json_decode($schedule['equipment_needed'], true);
        }
        
        return $schedules;
    }
}
