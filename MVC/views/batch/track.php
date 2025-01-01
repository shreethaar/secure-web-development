<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>Track Batch</h1>
<form action="/track_batch" method="POST">
    <div class="form-group">
        <label for="batch_number">Batch Number</label>
        <input type="text" name="batch_number" id="batch_number" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="production_schedule_id">Production Schedule</label>
        <select name="production_schedule_id" id="production_schedule_id" class="form-control" required>
            <!-- Populate with production schedules from the database -->
            <?php foreach ($schedules as $schedule): ?>
                <option value="<?= $schedule['id'] ?>"><?= htmlspecialchars($schedule['recipe_name'] . ' - ' . $schedule['production_date']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea name="notes" id="notes" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Track Batch</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
