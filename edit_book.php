<?php
include "data.php";

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
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { text-align: center; }
        .form-wrapper {
            width: 300px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"] {
            width: 100%; padding: 8px; box-sizing: border-box; margin-top: 5px;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            background-color: #5cacee;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        fieldset { border: none; padding: 0; }
        legend { font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>
<h1>Book Edit Form</h1>
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
