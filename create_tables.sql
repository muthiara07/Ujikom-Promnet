-- Database: simbs
CREATE DATABASE IF NOT EXISTS simbs CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE simbs;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(200) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- sample admin (username: admin, password: admin12345)
INSERT INTO users (username,email,password) VALUES
('admin','admin@example.com','$2b$12$zae9HXJoVatsEunj43ntTe5kLNSJ5OtyDFi49mZ4SsJv.iBLcJGkK')
ON DUPLICATE KEY UPDATE username=username;

CREATE TABLE IF NOT EXISTS kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(200) NOT NULL UNIQUE,
  tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS buku (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(255) NOT NULL,
  penulis VARCHAR(200) NOT NULL,
  kategori_id INT,
  sampul VARCHAR(255),
  tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- contoh data kategori
INSERT INTO kategori (nama_kategori) VALUES
('Pemrograman'), ('Basis Data'), ('Jaringan');

-- contoh data buku (kategori_id akan disesuaikan oleh auto-increment di atas)
INSERT INTO buku (judul,penulis,kategori_id) VALUES
('Belajar PHP','Andi','1'),
('Dasar MySQL','Budi','2'),
('Jaringan Komputer','Citra','3');
