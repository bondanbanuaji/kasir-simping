<?php
require('../fdpdf/fpdf.php');
include '../proses/connect.php';

// Ambil data transaksi lengkap
$query = "
    SELECT 
        t.id AS transaksi_id,
        t.tanggal,
        t.jam,
        t.nama AS nama_pembeli,
        u.username AS kasir,
        SUM(dt.jumlah * dt.harga) AS total
    FROM transaksi t
    JOIN detail_transaksi dt ON dt.transaksi_id = t.id
    JOIN users u ON u.id = t.kasir_id
    GROUP BY t.id, t.tanggal, t.jam, u.username
    ORDER BY t.tanggal DESC, t.jam DESC
";
$result = mysqli_query($conn, $query);

// Buat PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Laporan Transaksi', 0, 1, 'C');
$pdf->Ln(4);

// Header Tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 8, 'ID', 1);
$pdf->Cell(30, 8, 'Tanggal', 1);
$pdf->Cell(20, 8, 'Jam', 1);
$pdf->Cell(40, 8, 'Kasir', 1);
$pdf->Cell(35, 8, 'Pembeli', 1);
$pdf->Cell(35, 8, 'Total', 1);
$pdf->Ln();

// Isi Tabel
$pdf->SetFont('Arial', '', 10);
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(30, 8, $row['transaksi_id'], 1);
    $pdf->Cell(30, 8, $row['tanggal'], 1);
    $pdf->Cell(20, 8, $row['jam'], 1);
    $pdf->Cell(40, 8, $row['kasir'], 1);
    $pdf->Cell(35, 8, $row['nama_pembeli'], 1);
    $pdf->Cell(35, 8, 'Rp ' . number_format($row['total'], 0, ',', '.'), 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output();
?>
