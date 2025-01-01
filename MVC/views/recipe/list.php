<?php include __DIR__ . '/../includes/header.php'; ?>

<h1>Recipes</h1>
<a href="/create_recipe" class="btn btn-success mb-3">Create New Recipe</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Preparation Time</th>
            <th>Yield</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($recipes as $recipe): ?>
            <tr>
                <td><?= htmlspecialchars($recipe['id']) ?></td>
                <td><?= htmlspecialchars($recipe['name']) ?></td>
                <td><?= htmlspecialchars($recipe['description']) ?></td>
                <td><?= htmlspecialchars($recipe['prep_time']) ?></td>
                <td><?= htmlspecialchars($recipe['yield']) ?></td>
                <td>
                    <a href="/update_recipe/<?= $recipe['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="/delete_recipe/<?= $recipe['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
