<?php include 'data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Author List</title>
    <style>
        body { font-family: sans-serif; }
        table {width: 100%; max-width: 800px; border-collapse: collapse; margin: 20px auto; table-layout: fixed; }
        th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: left; }
        .actions { display: flex; gap: 10px; }
        a.button { text-decoration: none; padding: 5px 10px; border-radius: 4px; }
        .edit { background-color: #5cacee; color: white; }
        .delete { background-color: #ee5c5c; color: white; }
        .add { display: block; text-align: center; margin: 20px; font-size: 42px; text-decoration: none; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Author List</h1>
    <table>
        <thead>
        <tr>
            <th>Author</th>
            <th>Books</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($authors as $author): ?>
            <tr>
                <td><a href="booklist.php?author_id=<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?></a></td>
                <td><?= $author['books'] ?></td>
                <td class="actions">
                    <a class="button edit" href="edit_author.php?id=<?= $author['id'] ?>">✏️</a>
                    <a class="button delete" href="delete_author.php?id=<?= $author['id'] ?>">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a class="add" href="create_author.php">➕</a>
</body>
</html>