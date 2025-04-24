<?php
session_start();

include "includes/data.php";
include "includes/functions.php";

$authorId = (int)$_GET['id'] ?? 0;
$fullName = current(array_filter($_SESSION['authors'], fn($author) => $author['id'] === $authorId))['name'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? '';

    if ($action === 'delete') {
        removeAuthor($_SESSION['authors'], $authorId);
        header("Location: index.php");
    } elseif ($action === 'cancel') {
        header("Location: index.php");
    }

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Author</title>
    <link rel="stylesheet" href="./style/delete_item.css"/>
</head>
<body>
<div id="deleteConfirmationDialog" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="exclamation-icon">❗</span>
            <h2>Delete Author</h2>
        </div>
        <div class="modal-body">
            <p>You are about to delete the author <?= htmlspecialchars($fullName) ?>. If you proceed with this action, the application will permanently delete all books related to this author.</p>
        </div>
        <form method="POST">
            <fieldset class="modal-footer">
                <button id="cancelButton" name="action" value="cancel" type="submit">Cancel</button>
                <button id="deleteButton" name="action" value="delete" type="submit">Delete</button>
            </fieldset>
        </form>
    </div>
</div>
</body>
</html>
