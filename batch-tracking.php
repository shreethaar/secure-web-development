<?php
// batch_tracking.php
include 'dbconnection.php';

function start_batch($batch_number, $start_time, $recipe_id) {
    global $db;
    $stmt = $db->prepare("INSERT INTO batches (batch_number, start_time, recipe_id) VALUES (?, ?, ?)");
    return $stmt->execute([$batch_number, $start_time, $recipe_id]);
}

function update_batch_progress($batch_id, $stage, $status) {
    global $db;
    $stmt = $db->prepare("UPDATE batches SET stage = ?, status = ? WHERE id = ?");
    return $stmt->execute([$stage, $status, $batch_id]);
}

function view_batch_status($batch_id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM batches WHERE id = ?");
    $stmt->execute([$batch_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function log_batch_progress($batch_id, $log_message) {
    global $db;
    $stmt = $db->prepare("INSERT INTO batch_logs (batch_id, log_message) VALUES (?, ?)");
    return $stmt->execute([$batch_id, $log_message]);
}

function batch_report($batch_id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM batches WHERE id = ?");
    $stmt->execute([$batch_id]);
    $batch_info = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt_logs = $db->prepare("SELECT * FROM batch_logs WHERE batch_id = ?");
    $stmt_logs->execute([$batch_id]);
    $batch_logs = $stmt_logs->fetchAll(PDO::FETCH_ASSOC);

    return ['batch_info' => $batch_info, 'logs' => $batch_logs];
}
?>

