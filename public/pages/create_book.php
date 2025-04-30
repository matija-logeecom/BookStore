<?php
$errors = [];
$title = '';
$year = '';

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
        header('Location: authors.phtml');
        exit;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Book</title>
    <link rel="stylesheet" href="../style/create_book.css"/>
</head>
<body>
<div class="form-wrapper">
    <form method="POST">
        <fieldset>
            <legend>Book Create</legend>

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
