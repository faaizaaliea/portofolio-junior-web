CREATE DATABASE IF NOT EXISTS ujian;
USE ujian;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL
);

INSERT INTO users (first_name, last_name) VALUES
  ('Sanhero', 'Spider'),
  ('Ketut', 'Sanjaya'),
  ('Made', 'Arsana');
