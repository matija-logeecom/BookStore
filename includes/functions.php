<?php
function checkError(&$errors, $firstName, $lastName): bool
{
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

    return ($errors['firstName'] === '' && $errors['lastName'] === '');
}

function findAuthorById($authors, $authorId): int|null {
    foreach ($authors as $index => $author) {
        if ($author['id'] === $authorId) {
            return $index;
        }
    }
    return null;
}
function changeAuthorName(&$authors, $authorId, $newFirstName, $newLastName): void {
    $index = findAuthorById($authors, $authorId);
    if ($index !== null) {
        $authors[$index]['name'] = $newFirstName . ' ' . $newLastName;
    }
}

function removeAuthor(&$authors, $authorId): void {
    $index = findAuthorById($authors, $authorId);
    if ($index !== null) {
        unset($authors[$index]);
    }
}