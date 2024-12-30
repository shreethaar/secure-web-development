<?php
// production_scheduling.php
include 'dbconnection.php';

function create_schedule($order_volume, $staff_id, $equipment_id, $start_time, $end_time) {
    global $db;
    $stmt = $db->prepare("INSERT INTO production_schedules (order_volume, staff_id, equipment_id, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$order_volume, $staff_id, $equipment_id, $start_time, $end_time]);
}

function update_schedule($schedule_id, $order_volume, $staff_id, $equipment_id, $start_time, $end_time) {
    global $db;
    $stmt = $db->prepare("UPDATE production_schedules SET order_volume = ?, staff_id = ?, equipment_id = ?, start_time = ?, end_time = ? WHERE id = ?");
    return $stmt->execute([$order_volume, $staff_id, $equipment_id, $start_time, $end_time, $schedule_id]);
}

function view_schedule($schedule_id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM production_schedules WHERE id = ?");
    $stmt->execute([$schedule_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function list_schedules() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM production_schedules");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

