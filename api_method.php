<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.txt');
include ('api.php');

    $action = $_GET['action'] ?? '';
        switch ($action) {
            case 'customer':
                # code...
                break;
            
                case 'value':
                # code...
                break;

                case 'value':
                # code...
                break;
            default:
                # code...
                break;
        }








?>