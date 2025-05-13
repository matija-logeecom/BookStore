-- File: schema.sql

-- Ensure using the correct database
USE
BookStore;

-- Create Authors Table
CREATE TABLE Authors
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL
) ENGINE=InnoDB;

-- Create Books Table
CREATE TABLE Books
(
    id        INT AUTO_INCREMENT PRIMARY KEY,
    title     VARCHAR(255) NOT NULL,
    year      INT          NOT NULL,
    author_id INT          NOT NULL,
    FOREIGN KEY (author_id) REFERENCES Authors (id)
        ON DELETE CASCADE -- Automatically delete books if their author is deleted
) ENGINE=InnoDB;