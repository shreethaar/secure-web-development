<?php
// Include config and routing
require_once '../config/database.php';
require_once '../config/config.php';

// Include models
require_once '../models/UserModel.php';
require_once '../models/RecipeModel.php';
require_once '../models/ProductionModel.php';
require_once '../models/BatchModel.php';

// Include controllers
require_once '../controllers/AuthController.php';
require_once '../controllers/RecipeController.php';
require_once '../controllers/ProductionController.php';
require_once '../controllers/BatchController.php';

// Initialize the database connection
$pdo = Database::getConnection();

// Initialize models
$userModel = new UserModel($pdo);
$recipeModel = new RecipeModel($pdo);
$productionModel = new ProductionModel($pdo);
$batchModel = new BatchModel($pdo);

// Initialize controllers
$authController = new AuthController($userModel);
$recipeController = new RecipeController($recipeModel);
$productionController = new ProductionController($productionModel);
$batchController = new BatchController($batchModel);

// Routing logic (Basic Example)
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($requestUri, '/'));

// Default route
if (empty($segments[0])) {
    $segments[0] = 'home';
}

// Handle routes
switch ($segments[0]) {
    case 'login':
        $authController->login();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'recipes':
        if (isset($segments[1]) && $segments[1] == 'create') {
            $recipeController->createRecipe();
        } elseif (isset($segments[1]) && $segments[1] == 'update') {
            $recipeController->updateRecipe($segments[2]);
        } else {
            $recipeController->listRecipes();
        }
        break;

    case 'production':
        if (isset($segments[1]) && $segments[1] == 'schedule') {
            $productionController->scheduleProduction();
        } else {
            $productionController->listSchedules();
        }
        break;

    case 'batch':
        if (isset($segments[1]) && $segments[1] == 'track') {
            $batchController->trackBatch();
        } else {
            $batchController->batchStatus();
        }
        break;

    default:
        // Show a 404 page or home page if the route is not found
        echo "404 - Not Found";
        break;
}

