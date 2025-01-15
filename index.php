
<?php
require_once __DIR__ . '/../crud_switch/config/res.php';

$action = isset($_GET['action']) ? ($_GET['action']) : '';

switch ($action) {
    case 'add':
        include __DIR__ . '/view/person-view/form.php';
        break; 

    case 'update':
        if (isset($_GET['id'])) {
            $person = $handler->getpersonbyid($_GET['id']);
            include __DIR__ . '/view/person-view/form.php';
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $handler->delete($_GET['id']);
            header("Location: /crud_switch/index.php"); // Redirect back to read.php
            exit;
        }
        break;

    default:
        require_once '../crud_switch/view/person-view/display.php';
        break;
}