<?php
include '../config/database.php';

// Menangani proses hapus pembayaran
if (isset($_GET['hapus_id'])) {
    $id_pembayaran = $_GET['hapus_id'];

    // Query untuk menghapus data pembayaran
    $query_hapus = "DELETE FROM pembayaran WHERE id_pembayaran = $id_pembayaran";
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>alert('Pembayaran berhasil dihapus!');</script>";
    } else {
        echo "<script>alert('Gagal menghapus pembayaran: " . mysqli_error($conn) . "');</script>";
    }
}

// Mengambil data pembayaran dengan nama pelanggan dan pegawai
$query_pembayaran = "
    SELECT p.id_pembayaran, p.id_reservasi, p.total_bayar, p.metode_pembayaran, p.tanggal_pembayaran, 
           r.id_pegawai, pl.nama AS nama_pelanggan, pe.nama AS nama_pegawai
    FROM pembayaran p
    JOIN reservasi r ON p.id_reservasi = r.id_reservasi
    JOIN pelanggan pl ON r.id_pelanggan = pl.id_pelanggan
    JOIN pegawai pe ON r.id_pegawai = pe.id_pegawai
";

$pembayaran = mysqli_query($conn, $query_pembayaran);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <!-- Tabel Pembayaran -->
    <h3>Daftar Pembayaran</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Reservasi</th>
                <th>Nama Pelanggan</th>
                <th>Nama Pegawai</th>
                <th>Total Bayar</th>
                <th>Metode Pembayaran</th>
                <th>Tanggal Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($pembayaran)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['id_reservasi']; ?></td>
                    <td><?= $row['nama_pelanggan']; ?></td>
                    <td><?= $row['nama_pegawai']; ?></td>
                    <td>Rp <?= number_format($row['total_bayar'], 2, ',', '.'); ?></td>
                    <td><?= $row['metode_pembayaran']; ?></td>
                    <td><?= $row['tanggal_pembayaran']; ?></td>
                    <td>
                        <!-- Tombol Hapus Pembayaran -->
                        <a href="pembayaran.php?hapus_id=<?= $row['id_pembayaran']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
