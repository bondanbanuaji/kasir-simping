<?php
require('../fdpdf/fpdf.php');
include '../proses/connect.php';

date_default_timezone_set('Asia/Jakarta');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Times','B',16);
        $this->Cell(0,10,'TOKO SIMPING',0,1,'C');

        $this->SetFont('Times','',12);
        $this->Cell(0,7,'Jl. Contoh Alamat No.123, Jakarta - Indonesia',0,1,'C');
        $this->Cell(0,7,'Telp: (021) 12345678 | Email: info@toko.com',0,1,'C');

        $this->Ln(3);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Times','I',10);
        $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'C');
    }

    function RowMulti($data, $widths, $aligns) {
        $maxLines = 0;
        foreach ($data as $i => $text) {
            $nb = $this->NbLines($widths[$i], $text);
            if ($nb > $maxLines) {
                $maxLines = $nb;
            }
        }

        $h = 5 * $maxLines;
        $this->CheckPageBreak($h);

        foreach ($data as $i => $text) {
            $w = $widths[$i];
            $a = isset($aligns[$i]) ? $aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, 5, $text, 0, $a);
            $this->SetXY($x + $w, $y);
        }

        $this->Ln($h);
    }

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n") {
            $nb--;
        }

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }

            if ($c == ' ') {
                $sep = $i;
            }

            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }

                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }

        return $nl;
    }

    function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }
}

$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,10,'Laporan Transaksi Penjualan',0,1,'C');
$pdf->Ln(3);

// Header Tabel
$pdf->SetFont('Times','B',10);
$pdf->SetFillColor(230,230,230);
$headers = ['Invoice', 'Tanggal', 'Kasir', 'Produk', 'Total'];
$widths = [30, 30, 30, 60, 40];
$aligns = ['L', 'L', 'L', 'L', 'R'];

foreach ($headers as $i => $header) {
    $pdf->Cell($widths[$i], 8, $header, 1, 0, 'C', true);
}
$pdf->Ln();

// Ambil data transaksi
$query = "
    SELECT 
        t.id_transaksi AS transaksi_id,
        t.tgl_transaksi,
        t.no_invoice,
        u.username AS kasir,
        GROUP_CONCAT(CONCAT(p.nama, ' (x', dt.jumlah, ')') SEPARATOR ', ') AS produk,
        SUM(dt.jumlah * dt.harga) AS total
    FROM transaksi t
    JOIN detail_transaksi dt ON dt.transaksi_id = t.id_transaksi
    JOIN produk p ON dt.produk_id = p.id
    JOIN users u ON t.kasir_id = u.id
    GROUP BY t.id_transaksi, t.tgl_transaksi, t.no_invoice, u.username
    ORDER BY t.tgl_transaksi DESC
";

$result = mysqli_query($conn, $query);

$pdf->SetFont('Times','',10);
while ($row = mysqli_fetch_assoc($result)) {
    $tanggal = date('d-m-Y', strtotime($row['tgl_transaksi']));
    $produk = iconv('UTF-8', 'windows-1252', $row['produk']);
    $invoice = iconv('UTF-8', 'windows-1252', $row['no_invoice']);
    $kasir = iconv('UTF-8', 'windows-1252', $row['kasir']);
    $total = 'Rp. ' . number_format($row['total'], 0, ',', '.');

    $pdf->RowMulti([$invoice, $tanggal, $kasir, $produk, $total], $widths, $aligns);
}

$pdf->Ln(5);
$pdf->SetFont('Times','I',10);
$pdf->Cell(0,10,'Dicetak pada: ' . date('d-m-Y H:i:s') . ' WIB',0,0,'L');

$pdf->Output();