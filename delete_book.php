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

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px 30px;
            border: none;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .exclamation-icon {
            color: #d9534f;
            font-size: 1.8em;
            margin-right: 10px;
        }

        .modal-body {
            margin-bottom: 25px;
            font-size: 1.1em;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-footer button {
            padding: 12px 20px;
            font-size: 1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #deleteButton {
            background-color: #d9534f;
            color: white;
        }

        #deleteButton:hover {
            background-color: #c9302c;
        }

        #cancelButton {
            background-color: #e0e0e0;
            color: #333;
        }

        #cancelButton:hover {
            background-color: #ccc;
        }

        fieldset {
            border: none;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
<div id="deleteConfirmationDialog" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="exclamation-icon">❗</span>
            <h2>Delete Author</h2>
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
