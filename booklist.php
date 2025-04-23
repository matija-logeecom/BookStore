<?php
include "data.php";

$authorId = isset($_GET['author_id']) ? (int)$_GET['author_id'] : 0;
$authorBooks = $books[$authorId] ?? [];

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booklist</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
            margin: auto;
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #f8f8f8;
        }

        .actions {
            white-space: nowrap;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
            font-size: 1.2em;
        }

        .create-button {
            font-size: 2em;
            display: block;
            margin: 20px auto;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Booklist</h1>
    <table>
        <thead>
        <tr>
            <th>Book</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($authorBooks as $authorBook): ?>
                <tr>
                    <td>
                        <b>
                            <?= htmlspecialchars($authorBook['title']) ?>
                            (<?= htmlspecialchars($authorBook['year']) ?>)
                        </b>
                    </td>
                    <td class="actions">
                        <a href="edit_book.php?book_id=<?= $authorBook['id'] ?>">✏️</a>
                        <a href="delete_book.php?book_id=<?= $authorBook['id'] ?>">❌</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="create_book.php?author_id=<?= $authorId ?>" class="create-button">➕</a>
</body>
</html>
