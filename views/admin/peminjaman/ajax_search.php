<?php
require '../config/koneksi.php';

$cari = isset($_GET['cari']) ? $conn->real_escape_string($_GET['cari']) : '';

$sql = "
  SELECT p.*, u.nama AS member, b.judul AS buku 
  FROM peminjaman p
  JOIN users u ON p.user_id = u.id
  JOIN buku b ON p.buku_id = b.id
";

if ($cari !== '') {
    $sql .= " WHERE u.nama LIKE '%$cari%' OR b.judul LIKE '%$cari%'";
}

$sql .= " ORDER BY p.created_at DESC";

$data = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

if (count($data) === 0) {
    echo '<tr><td colspan="6" class="text-center p-4">Data tidak ditemukan.</td></tr>';
    exit;
}

foreach ($data as $i => $row) {
    echo "
    <tr>
      <td class='border p-2'>" . ($i + 1) . "</td>
      <td class='border p-2'>{$row['member']}</td>
      <td class='border p-2'>{$row['buku']}</td>
      <td class='border p-2'>{$row['tanggal_pinjam']}</td>
      <td class='border p-2'>" . ($row['tanggal_kembali'] ?: '-') . "</td>
      <td class='border p-2'>{$row['status']}</td>
    </tr>
  ";
}
