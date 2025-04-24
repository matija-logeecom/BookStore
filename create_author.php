<?php
session_start();

$errors['firstName'] = '';
$errors['lastName'] = '';
$firstName = '';
$lastName = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST["first_name"] ?? '');
    $lastName = trim($_POST["last_name"] ?? '');

    if ($firstName === '') {
        $errors['firstName'] = "* This field is required";
    } elseif (strlen($firstName) > 100) {
        $errors['firstName'] = "First name must be <= 100 characters";
    }

    if ($lastName === '') {
        $errors['lastName'] = "* This field is required";
    } elseif (strlen($lastName) > 100) {
        $errors['lastName'] = "Last name must be <= 100 characters";
    }

    if (!$errors) {
        $_SESSION['authors'][] = ['id' => $_SESSION['currentId'], 'name' => $firstName . ' ' . $lastName, 'books' => 0];
        $_SESSION['currentId'] =+ 1;
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
    <link rel="stylesheet" href="./style/create_author.css"/>
</head>
<body>
<div class="form-wrapper">
    <form method="POST">
        <fieldset>
            <div class="legend-div">Author Create</div>

            <label for="first_name">First name</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($firstName); ?>">
            <?php if ($errors['firstName'] !== ''): ?>
                <span class="error"><?= htmlspecialchars($errors['firstName']) ?></span>
            <?php endif; ?>

            <label for="last_name">Last name</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($lastName); ?>">
            <?php if ($errors['lastName'] !== ''): ?>
                <span class="error"><?= htmlspecialchars($errors['lastName']) ?></span>
            <?php endif; ?>

            <div class="button-div">
                <button type="submit">Save</button>
            </div>
        </fieldset>
    </form>
</div>
</body>
</html>
