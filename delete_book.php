<?php
include "includes/data.php";

$bookId = (int)$_GET['book_id'] ?? 0;
$title = current(array_filter(array_merge(...$books), fn($book) => $book['id'] === $bookId))['title'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? '';

    $authorId = 0;
    foreach ($books as $id => $authorBooks) {
        foreach ($authorBooks as $b) {
            if ($b['id'] === $bookId) {
                $authorId = $id;
                break;
            }
        }
    }

    if ($action === 'delete') {
        // delete logic
        header("Location: booklist.php?author_id=$authorId");
    } elseif ($action === 'cancel') {
        header("Location: booklist.php?author_id=$authorId");
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
            <h2>Delete Book</h2>
        </div>
        <div class="modal-body">
            <p>You are about to delete the book <?= htmlspecialchars($title) ?>. If you proceed with this action, the application will permanently delete this book from the database.</p>
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
