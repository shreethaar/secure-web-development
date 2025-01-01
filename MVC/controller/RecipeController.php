<?php
class RecipeController {
    private $recipeModel;

    public function __construct($recipeModel) {
        $this->recipeModel = $recipeModel;
    }

    // Show the form for creating a new recipe
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'ingredients' => $_POST['ingredients'],
                'steps' => $_POST['steps'],
                'equipment' => $_POST['equipment'],
                'prep_time' => $_POST['prep_time'],
                'yield' => $_POST['yield'],
                'created_by' => $_SESSION['user_id'] // Logged-in user
            ];

            $this->recipeModel->createRecipe($data);
            header('Location: /recipes');
            exit;
        } else {
            // Show the create recipe form
            include __DIR__ . '/../views/recipe/create.php';
        }
    }

    // Show the form for updating an existing recipe
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'ingredients' => $_POST['ingredients'],
                'steps' => $_POST['steps'],
                'equipment' => $_POST['equipment'],
                'prep_time' => $_POST['prep_time'],
                'yield' => $_POST['yield']
            ];

            $this->recipeModel->updateRecipe($id, $data);
            header('Location: /recipes');
            exit;
        } else {
            // Fetch the recipe and show the update form
            $recipe = $this->recipeModel->getRecipe($id);
            include __DIR__ . '/../views/recipe/update.php';
        }
    }

    // List all recipes
    public function list() {
        $recipes = $this->recipeModel->getAllRecipes();
        include __DIR__ . '/../views/recipe/list.php';
    }

    // Delete a recipe
    public function delete($id) {
        $this->recipeModel->deleteRecipe($id);
        header('Location: /recipes');
        exit;
    }
}
?>
