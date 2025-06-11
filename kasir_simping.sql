
-- --------------------------------------------------------
-- Database: kasir_simping
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS kasir_simping;
USE kasir_simping;

-- Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'kasir', 'pemilik') NOT NULL
);

-- Tabel Produk
CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL
);

-- Tabel Transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kasir_id INT NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    total INT NOT NULL,
    FOREIGN KEY (kasir_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Detail Transaksi
CREATE TABLE IF NOT EXISTS transaksi_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    produk_id INT NOT NULL,
    qty INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

-- Contoh user awal
INSERT INTO users (username, password, role) VALUES
('admin1', MD5('admin123'), 'admin'),
('kasir1', MD5('kasir123'), 'kasir'),
('pemilik1', MD5('pemilik123'), 'pemilik');
