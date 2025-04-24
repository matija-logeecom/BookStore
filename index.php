<?php include 'includes/data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Author List</title>
    <link rel="stylesheet" href="./style/index.css">
</head>
<body>
<div class="container">
    <table>
        <thead>
        <tr>
            <th class="author-col">Author</th>
            <th class="books-col">Books</th>
            <th class="actions-col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($authors as $author): ?>
            <tr>
                <td>
                    <div class="author-name">
                        <div class="user-image">
                            <img src="./images/user.png" alt="user">
                        </div>
                        <a href="booklist.php?author_id=<?= $author['id'] ?>">
                            <b><?= htmlspecialchars($author['name']) ?></b>
                        </a>
                    </div>
                </td>
                <td class="books-col">
                    <div class="book-count"><?= $author['books'] ?></div>
                </td>
                <td class="actions-col">
                    <div class="actions">
                        <a class="button edit" href="edit_author.php?id=<?= $author['id'] ?>">
                            <img src="./images/pen.png" alt="pen">
                        </a>
                        <a class="button delete" href="delete_author.php?id=<?= $author['id'] ?>">
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
    <a class="add" href="create_author.php">
        <img src="./images/add.png" alt="add" class="add-image">
    </a>
</div>
</body>
</html>
