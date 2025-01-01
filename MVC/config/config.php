<?php
// config/config.php

class Config {
    // Application Settings
    private static $config = [
        // Basic App Configuration
        'app' => [
            'name' => 'Bakery Production System',
            'version' => '1.0.0',
            'url' => 'http://localhost/bakery',
            'timezone' => 'UTC',
            'debug' => true,
            'maintenance_mode' => false
        ],

        // Session Configuration
        'session' => [
            'lifetime' => 7200, // 2 hours
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true
        ],

        // Route Definitions
        'routes' => [
            // Auth Routes
            'auth' => [
                'login' => '/auth/login',
                'logout' => '/auth/logout',
                'dashboard' => '/auth/dashboard'
            ],
            
            // Recipe Routes
            'recipe' => [
                'list' => '/recipe/list',
                'create' => '/recipe/create',
                'edit' => '/recipe/edit',
                'delete' => '/recipe/delete',
                'view' => '/recipe/view'
            ],
            
            // Production Routes
            'production' => [
                'schedule' => '/production/schedule',
                'list' => '/production/list',
                'edit' => '/production/edit',
                'delete' => '/production/delete',
                'view' => '/production/view'
            ],
            
            // Batch Routes
            'batch' => [
                'track' => '/batch/track',
                'status' => '/batch/status',
                'quality-check' => '/batch/quality-check',
                'list' => '/batch/list',
                'view' => '/batch/view'
            ]
        ],

        // User Roles and Permissions
        'roles' => [
            'supervisor' => [
                'permissions' => ['create_recipe', 'edit_recipe', 'delete_recipe', 
                                'create_schedule', 'edit_schedule', 'delete_schedule',
                                'view_reports', 'manage_users']
            ],
            'baker' => [
                'permissions' => ['view_recipe', 'view_schedule', 
                                'update_batch_status', 'add_quality_check']
            ]
        ],

        // Production Settings
        'production' => [
            'max_daily_batches' => 20,
            'working_hours' => [
                'start' => '06:00',
                'end' => '18:00'
            ],
            'quality_check_parameters' => [
                'appearance',
                'texture',
                'taste',
                'weight',
                'temperature'
            ]
        ],

        // File Upload Settings
        'upload' => [
            'allowed_types' => ['jpg', 'jpeg', 'png'],
            'max_size' => 5242880, // 5MB
            'path' => '/uploads/'
        ],

        // Error Logging
        'logging' => [
            'enabled' => true,
            'path' => '/logs/',
            'level' => 'ERROR' // DEBUG, INFO, WARNING, ERROR
        ]
    ];

    // Method to get configuration values
    public static function get($key = null) {
        if ($key === null) {
            return self::$config;
        }

        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }

        return $value;
    }

    // Method to set configuration values
    public static function set($key, $value) {
        $keys = explode('.', $key);
        $config = &self::$config;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }

        $config = $value;
    }

    // Initialize application settings
    public static function init() {
        // Set timezone
        date_default_timezone_set(self::get('app.timezone'));

        // Start session with secure settings
        $sessionConfig = self::get('session');
        session_set_cookie_params(
            $sessionConfig['lifetime'],
            $sessionConfig['path'],
            $sessionConfig['domain'],
            $sessionConfig['secure'],
            $sessionConfig['httponly']
        );
        session_start();

        // Set error reporting based on debug mode
        if (self::get('app.debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }

        // Initialize error logging
        if (self::get('logging.enabled')) {
            ini_set('log_errors', 1);
            ini_set('error_log', dirname(__DIR__) . self::get('logging.path') . 'error.log');
        }
    }

    // Check if user has permission
    public static function hasPermission($role, $permission) {
        $roleConfig = self::get('roles.' . $role);
        return $roleConfig && in_array($permission, $roleConfig['permissions']);
    }

    // Get route URL
    public static function getRoute($path) {
        $baseUrl = rtrim(self::get('app.url'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

// Initialize the configuration
Config::init();
