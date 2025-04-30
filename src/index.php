<?php

require_once __DIR__ . "/../vendor/autoload.php";

use BookStore\Controller\AuthorController;
use BookStore\Core\ServiceRegistry;

try {
    $controller = ServiceRegistry::get(AuthorController::class);
} catch (Exception $e) {
    echo $e->getMessage();
}

$action = $_GET['action'] ?? 'listAuthors';
$id = $_GET['id'] ?? 0;

switch ($action) {
    case 'listAuthors':
        $controller->listAuthors();
        break;
    case 'createAuthor':
        $controller->createAuthor();
        break;
    case 'editAuthor':
        $controller->editAuthor($id);
        break;
    case 'deleteAuthor':
        $controller->deleteAuthor($id);
        break;
}