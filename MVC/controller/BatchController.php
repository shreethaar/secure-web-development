<?php
class BatchController {
    private $batchModel;

    public function __construct($batchModel) {
        $this->batchModel = $batchModel;
    }

    // Show the form for tracking a new batch
    public function track() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $data = [
                'batch_number' => $_POST['batch_number'],
                'production_schedule_id' => $_POST['production_schedule_id'],
                'notes' => $_POST['notes']
            ];

            $this->batchModel->createBatch($data);
            header('Location: /batches');
            exit;
        } else {
            // Show the track batch form
            include __DIR__ . '/../views/batch/track.php';
        }
    }

    // List all batches
    public function list() {
        $batches = $this->batchModel->getDailyBatches(date('Y-m-d')); // Default to today's batches
        include __DIR__ . '/../views/batch/list.php';
    }

    // Show batch status
    public function status($id) {
        $batch = $this->batchModel->getBatch($id);
        include __DIR__ . '/../views/batch/status.php';
    }

    // Update batch status
    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'];
            $notes = $_POST['notes'] ?? null;

            $this->batchModel->updateBatchStatus($id, $status, $notes);
            header('Location: /batches/status/' . $id);
            exit;
        }
    }

    // Add quality check to a batch
    public function addQualityCheck($batch_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'checker_id' => $_SESSION['user_id'], // Logged-in user
                'parameters' => $_POST['parameters'],
                'result' => $_POST['result'],
                'remarks' => $_POST['remarks']
            ];

            $this->batchModel->addQualityCheck($batch_id, $data);
            header('Location: /batches/status/' . $batch_id);
            exit;
        }
    }
}
?>
