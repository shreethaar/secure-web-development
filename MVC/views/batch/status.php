<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>Batch Status</h1>
<h2>Batch #<?= htmlspecialchars($batch['batch_number']) ?></h2>
<p>Recipe: <?= htmlspecialchars($batch['recipe_name']) ?></p>
<p>Production Date: <?= htmlspecialchars($batch['production_date']) ?></p>
<p>Assigned Baker: <?= htmlspecialchars($batch['assigned_baker_name']) ?></p>
<p>Status: <?= htmlspecialchars($batch['status']) ?></p>
<p>Start Time: <?= htmlspecialchars($batch['start_time']) ?></p>
<p>End Time: <?= htmlspecialchars($batch['end_time']) ?></p>
<p>Notes: <?= htmlspecialchars($batch['notes']) ?></p>

<h3>Quality Checks</h3>
<table class="table">
    <thead>
        <tr>
            <th>Checker</th>
            <th>Parameters</th>
            <th>Result</th>
            <th>Remarks</th>
            <th>Check Time</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($batch['quality_checks'] as $check): ?>
            <tr>
                <td><?= htmlspecialchars($check['checker_name']) ?></td>
                <td><?= htmlspecialchars(json_encode($check['parameters'])) ?></td>
                <td><?= htmlspecialchars($check['result']) ?></td>
                <td><?= htmlspecialchars($check['remarks']) ?></td>
                <td><?= htmlspecialchars($check['check_time']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form action="/update_batch_status/<?= $batch['id'] ?>" method="POST">
    <div class="form-group">
        <label for="status">Update Status</label>
        <select name="status" id="status" class="form-control">
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="failed">Failed</option>
        </select>
    </div>
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea name="notes" id="notes" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Status</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
