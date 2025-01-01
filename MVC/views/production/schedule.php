<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>Production Schedule</h1>
<form action="/schedule_production" method="POST">
    <div class="form-group">
        <label for="recipe_id">Recipe</label>
        <select name="recipe_id" id="recipe_id" class="form-control" required>
            <!-- Populate with recipes from the database -->
            <?php foreach ($recipes as $recipe): ?>
                <option value="<?= $recipe['id'] ?>"><?= htmlspecialchars($recipe['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="order_id">Order ID</label>
        <input type="text" name="order_id" id="order_id" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="production_date">Production Date</label>
        <input type="date" name="production_date" id="production_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="start_time">Start Time</label>
        <input type="time" name="start_time" id="start_time" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="end_time">End Time</label>
        <input type="time" name="end_time" id="end_time" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="assigned_baker">Assigned Baker</label>
        <select name="assigned_baker" id="assigned_baker" class="form-control" required>
            <!-- Populate with bakers from the database -->
            <?php foreach ($bakers as $baker): ?>
                <option value="<?= $baker['id'] ?>"><?= htmlspecialchars($baker['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="equipment_needed">Equipment Needed (JSON format)</label>
        <textarea name="equipment_needed" id="equipment_needed" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Schedule Production</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
