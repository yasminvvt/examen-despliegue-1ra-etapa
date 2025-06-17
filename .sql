    CREATE TABLE students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        apellido VARCHAR(255) NOT NULL,
        curso VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE
    );