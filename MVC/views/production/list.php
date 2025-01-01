<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>Production Schedules</h1>
<a href="/schedule_production" class="btn btn-success mb-3">Create New Schedule</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Recipe</th>
            <th>Production Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Quantity</th>
            <th>Assigned Baker</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($schedules as $schedule): ?>
            <tr>
                <td><?= htmlspecialchars($schedule['id']) ?></td>
                <td><?= htmlspecialchars($schedule['recipe_name']) ?></td>
                <td><?= htmlspecialchars($schedule['production_date']) ?></td>
                <td><?= htmlspecialchars($schedule['start_time']) ?></td>
                <td><?= htmlspecialchars($schedule['end_time']) ?></td>
                <td><?= htmlspecialchars($schedule['quantity']) ?></td>
                <td><?= htmlspecialchars($schedule['baker_name']) ?></td>
                <td>
                    <a href="/update_schedule/<?= $schedule['id'] ?>" class="btn btn-warning">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
