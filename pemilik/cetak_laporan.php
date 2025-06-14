<?php
require('../fdpdf/fpdf.php');
include '../proses/connect.php';

class PDF extends FPDF {
    function Header() {
        // Logo toko (opsional kalau ada gambar)
        // $this->Image('logo.png',10,6,30);
        
        // Judul
        $this->SetFont('Times','B',16);
        $this->Cell(0,10,'TOKO SIMPING',0,1,'C');

        $this->SetFont('Times','',12);
        $this->Cell(0,7,'Jl. Contoh Alamat No.123, Jakarta - Indonesia',0,1,'C');
        $this->Cell(0,7,'Telp: (021) 12345678 | Email: info@toko.com',0,1,'C');

        // Garis bawah
        $this->Ln(3);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    function Footer() {
        // Posisi 15mm dari bawah
        $this->SetY(-15);
        $this->SetFont('Times','I',10);
        $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'C');
    }
}

$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,10,'Laporan Transaksi Penjualan',0,1,'C');
$pdf->Ln(3);

// Header tabel
$pdf->SetFont('Times','B',10);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(28,8,'Invoice',1,0,'C',true);
$pdf->Cell(25,8,'Tanggal',1,0,'C',true);
$pdf->Cell(20,8,'Jam',1,0,'C',true);
$pdf->Cell(25,8,'Kasir',1,0,'C',true);
$pdf->Cell(60,8,'Produk',1,0,'C',true);
$pdf->Cell(32,8,'Total',1,1,'C',true);

// Ambil data transaksi
$query = "
    SELECT 
        t.id_transaksi AS transaksi_id,
        t.tgl_transaksi,
        t.no_invoice,
        t.metode_pembayaran,
        u.username AS kasir,
        GROUP_CONCAT(CONCAT(p.nama, ' (x', dt.jumlah, ')') SEPARATOR ', ') AS produk,
        SUM(dt.jumlah * dt.harga) AS total
    FROM transaksi t
    JOIN detail_transaksi dt ON dt.transaksi_id = t.id_transaksi
    JOIN produk p ON dt.produk_id = p.id
    JOIN users u ON t.kasir_id = u.id
    GROUP BY t.id_transaksi, t.tgl_transaksi, t.no_invoice, t.metode_pembayaran, u.username
    ORDER BY t.tgl_transaksi DESC
";

$result = mysqli_query($conn, $query);

// Isi tabel
$pdf->SetFont('Times','',10);
while ($row = mysqli_fetch_assoc($result)) {
    $tanggal_waktu = date_create($row['tgl_transaksi']);
    $tanggal = date_format($tanggal_waktu, 'Y-m-d');
    $jam = date_format($tanggal_waktu, 'H:i:s');

    // Potong produk jika terlalu panjang
    $produk = iconv('UTF-8', 'windows-1252', $row['produk']);
    if (strlen($produk) > 90) {
        $produk = substr($produk, 0, 87) . '...';
    }

    $pdf->Cell(28,8,$row['no_invoice'],1,0);
    $pdf->Cell(25,8,$tanggal,1,0);
    $pdf->Cell(20,8,$jam,1,0);
    $pdf->Cell(25,8,$row['kasir'],1,0);
    $pdf->Cell(60,8,$produk,1,0);
    $pdf->Cell(32,8,'Rp ' . number_format($row['total'], 0, ',', '.'),1,1);
}

$pdf->Ln(5);
$pdf->SetFont('Times','I',10);
$pdf->Cell(0,10,'Dicetak pada: ' . date('d-m-Y H:i:s'),0,0,'L');

$pdf->Output();