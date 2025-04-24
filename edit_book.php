<?php
include "includes/data.php";

$book_id = (int)($_GET['book_id'] ?? 0);
$book = null;

foreach ($books as $authorBooks) {
    foreach ($authorBooks as $b) {
        if ($b['id'] === $book_id) {
            $book = $b;
            break;
        }
    }
}

$errors = [];
$title = $book['title'];
$year = (int)$book['year'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? '');
    $year = (int)trim($_POST["year"] ?? '');

    if ($title === '' || strlen($title) > 250) {
        $errors[] = "Title is required and must be ≤ 250 characters.";
    }

    if ($year === '' || $year < -5000 || $year > 999999 || $year === 0) {
        $errors[] = "Year must be valid.";
    }

    if (!$errors) {
        header('Location: index.php');
        exit;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="./style/edit_book.css"/>
</head>
<body>
<div class="form-wrapper">
    <form method="POST">
        <fieldset>
            <legend>Book Edit (<?= $book_id ?>)</legend>

            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title); ?>">

            <label for="year">Year</label>
            <input type="text" id="year" name="year" value="<?= htmlspecialchars($year); ?>">

            <?php foreach ($errors as $error): ?>
                <div style="color: red;">* <?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>

            <button type="submit">Save</button>
        </fieldset>
    </form>
</div>
</body>
</html>
