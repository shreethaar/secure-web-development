<?php
class ProductionController {
    private $productionModel;

    public function __construct($productionModel) {
        $this->productionModel = $productionModel;
    }

    // Show the form for creating a new production schedule
    public function schedule() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $data = [
                'recipe_id' => $_POST['recipe_id'],
                'order_id' => $_POST['order_id'],
                'production_date' => $_POST['production_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'quantity' => $_POST['quantity'],
                'assigned_baker' => $_POST['assigned_baker'],
                'equipment_needed' => $_POST['equipment_needed'],
                'created_by' => $_SESSION['user_id'] // Logged-in user
            ];

            $this->productionModel->createSchedule($data);
            header('Location: /production/schedules');
            exit;
        } else {
            // Show the create schedule form
            include __DIR__ . '/../views/production/schedule.php';
        }
    }

    // List all production schedules
    public function list() {
        $schedules = $this->productionModel->getDailySchedule(date('Y-m-d')); // Default to today's schedules
        include __DIR__ . '/../views/production/list.php';
    }

    // Update a production schedule
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $data = [
                'recipe_id' => $_POST['recipe_id'],
                'order_id' => $_POST['order_id'],
                'production_date' => $_POST['production_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'quantity' => $_POST['quantity'],
                'assigned_baker' => $_POST['assigned_baker'],
                'equipment_needed' => $_POST['equipment_needed'],
                'status' => $_POST['status']
            ];

            $this->productionModel->updateSchedule($id, $data);
            header('Location: /production/schedules');
            exit;
        } else {
            // Fetch the schedule and show the update form
            $schedule = $this->productionModel->getSchedule($id);
            include __DIR__ . '/../views/production/update.php';
        }
    }
}
?>
