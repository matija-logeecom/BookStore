<?php
include "includes/data.php";

$authorId = isset($_GET['author_id']) ? (int)$_GET['author_id'] : 0;
$authorBooks = $books[$authorId] ?? [];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book List</title>
    <link rel="stylesheet" href="style/tables.css"/>
</head>
<body>
<div class="container">
    <table>
        <thead>
        <tr>
            <th class="book-col">Book</th>
            <th class="actions-col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($authorBooks as $authorBook): ?>
            <tr>
                <td>
                    <b><?= htmlspecialchars($authorBook['title']) ?> (<?= htmlspecialchars($authorBook['year']) ?>)</b>
                </td>
                <td class="actions-col">
                    <div class="actions">
                        <a class="button edit" href="edit_book.php?book_id=<?= $authorBook['id'] ?>">
                            <img src="./images/pen.png" alt="pen">
                        </a>
                        <a class="button delete" href="delete_book.php?book_id=<?= $authorBook['id'] ?>">
                            <img src="./images/minus.png" alt="minus">
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="add-container">
    <a class="add" href="create_book.php?author_id=<?= $authorId ?>">
        <img src="./images/add.png" alt="add" class="add-image">
    </a>
</div>
</body>
</html>
