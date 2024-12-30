<?php
// recipe_management.php
include 'dbconnection.php';

function create_recipe($name, $ingredients, $steps, $equipment) {
    global $db;
    $stmt = $db->prepare("INSERT INTO recipes (name, ingredients, steps, equipment) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $ingredients, $steps, $equipment]);
}

function update_recipe($id, $name, $ingredients, $steps, $equipment) {
    global $db;
    $stmt = $db->prepare("UPDATE recipes SET name = ?, ingredients = ?, steps = ?, equipment = ? WHERE id = ?");
    return $stmt->execute([$name, $ingredients, $steps, $equipment, $id]);
}

function view_recipe($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function delete_recipe($id) {
    global $db;
    $stmt = $db->prepare("DELETE FROM recipes WHERE id = ?");
    return $stmt->execute([$id]);
}

function list_recipes() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM recipes");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

