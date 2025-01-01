<?php
class BatchModel {
    private $db;
    private $table = 'batches';
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function createBatch($data) {
        $sql = "INSERT INTO {$this->table} 
                (batch_number, production_schedule_id, start_time, status, notes) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['batch_number'],
            $data['production_schedule_id'],
            $data['start_time'] ?? date('Y-m-d H:i:s'),
            'in_progress',
            $data['notes'] ?? null
        ]);
    }
    
    public function updateBatchStatus($id, $status, $notes = null) {
        $sql = "UPDATE {$this->table} 
                SET status = ?, notes = ?, end_time = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $status,
            $notes,
            ($status === 'completed' || $status === 'failed') ? date('Y-m-d H:i:s') : null,
            $id
        ]);
    }
    
    public function addQualityCheck($batch_id, $data) {
        $sql = "INSERT INTO batch_quality_checks 
                (batch_id, checker_id, parameters, result, remarks) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $batch_id,
            $data['checker_id'],
            json_encode($data['parameters']),
            $data['result'],
            $data['remarks'] ?? null
        ]);
    }
    
    public function getBatch($id) {
        $sql = "SELECT b.*, ps.recipe_id, r.name as recipe_name,
                       ps.order_id, ps.production_date,
                       u.name as assigned_baker_name
                FROM {$this->table} b 
                JOIN production_schedules ps ON b.production_schedule_id = ps.id 
                JOIN recipes r ON ps.recipe_id = r.id 
                JOIN users u ON ps.assigned_baker = u.id 
                WHERE b.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $batch = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($batch) {
            // Get quality checks
            $sql = "SELECT qc.*, u.name as checker_name 
                   FROM batch_quality_checks qc
                   JOIN users u ON qc.checker_id = u.id
                   WHERE qc.batch_id = ?
                   ORDER BY qc.check_time";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $batch['quality_checks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($batch['quality_checks'] as &$check) {
                $check['parameters'] = json_decode($check['parameters'], true);
            }
        }
        
        return $batch;
    }
    
    public function getDailyBatches($date) {
        $sql = "SELECT b.*, ps.recipe_id, r.name as recipe_name,
                       ps.order_id, ps.production_date,
                       u.name as assigned_baker_name
                FROM {$this->table} b 
                JOIN production_schedules ps ON b.production_schedule_id = ps.id 
                JOIN recipes r ON ps.recipe_id = r.id 
                JOIN users u ON ps.assigned_baker = u.id 
                WHERE DATE(b.start_time) = ?
                ORDER BY b.start_time";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getQualityChecks($batch_id) {
        $sql = "SELECT qc.*, u.name as checker_name 
               FROM batch_quality_checks qc
               JOIN users u ON qc.checker_id = u.id
               WHERE qc.batch_id = ?
               ORDER BY qc.check_time";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$batch_id]);
        $checks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($checks as &$check) {
            $check['parameters'] = json_decode($check['parameters'], true);
        }
        
        return $checks;
    }
}
