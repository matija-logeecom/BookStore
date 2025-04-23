<?php
$errors = [];
$firstName = '';
$lastName = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST["first_name"] ?? '');
    $lastName = trim($_POST["last_name"] ?? '');

    if ($firstName === '' || strlen($firstName) > 100) {
        $errors[] = "First name is required and must be ≤ 100 characters.";
    }

    if ($lastName === '' || strlen($lastName) > 100) {
        $errors[] = "Last name is required and must be ≤ 100 characters.";
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
    <title>Create Author</title>
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
    <h1>Author Create Form</h1>
<div class="form-wrapper">
    <form method="POST">
        <fieldset>
            <legend>Author Create</legend>

            <label for="first_name">First name</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($firstName); ?>">

            <label for="last_name">Last name</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($lastName); ?>">

            <?php foreach ($errors as $error): ?>
                <div style="color: red;">* <?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>

            <button type="submit">Save</button>
        </fieldset>
    </form>
</div>
</body>
</html>
