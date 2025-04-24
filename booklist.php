<?php
include "includes/data.php";

$authorId = isset($_GET['author_id']) ? (int)$_GET['author_id'] : 0;
$authorBooks = $books[$authorId] ?? [];

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booklist</title>
    <link rel="stylesheet" href="./style/booklist.css"/>
</head>
<body>
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
