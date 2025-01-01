<?php
class RecipeModel {
    private $db;
    private $table = 'recipes';
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getAllRecipes() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRecipe($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createRecipe($data) {
        $sql = "INSERT INTO {$this->table} (name, ingredients, steps, equipment, prep_time, yield) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            json_encode($data['ingredients']),
            json_encode($data['steps']),
            json_encode($data['equipment']),
            $data['prep_time'],
            $data['yield']
        ]);
    }
    
    public function updateRecipe($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET name = ?, ingredients = ?, steps = ?, equipment = ?, prep_time = ?, yield = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            json_encode($data['ingredients']),
            json_encode($data['steps']),
            json_encode($data['equipment']),
            $data['prep_time'],
            $data['yield'],
            $id
        ]);
    }
    
    public function deleteRecipe($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
