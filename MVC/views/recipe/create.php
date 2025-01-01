<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>Create Recipe</h1>
<form action="/create_recipe" method="POST">
    <div class="form-group">
        <label for="name">Recipe Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="ingredients">Ingredients (JSON format)</label>
        <textarea name="ingredients" id="ingredients" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="steps">Steps (JSON format)</label>
        <textarea name="steps" id="steps" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="equipment">Equipment (JSON format)</label>
        <textarea name="equipment" id="equipment" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="prep_time">Preparation Time</label>
        <input type="text" name="prep_time" id="prep_time" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="yield">Yield</label>
        <input type="text" name="yield" id="yield" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Create Recipe</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
