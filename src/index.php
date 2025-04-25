<?php
require_once __DIR__ . "/controller/AuthorController.php";

$controller = new AuthorController();

$action = $_GET['action'] ?? 'listAuthors';
$id = $_GET['id'] ?? 0;

switch ($action) {
    case 'listAuthors': $controller->listAuthors(); break;
    case 'createAuthor': $controller->createAuthor(); break;
    case 'editAuthor': $controller->editAuthor($id); break;
    case 'deleteAuthor': $controller->deleteAuthor($id); break;
}