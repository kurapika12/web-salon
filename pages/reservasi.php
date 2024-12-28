<?php
include '../config/database.php';

// Ambil data pelanggan, pegawai, dan layanan
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
$pegawai = mysqli_query($conn, "SELECT * FROM pegawai");
$layanan = mysqli_query($conn, "SELECT * FROM layanan");

// Proses submit reservasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_pegawai = $_POST['id_pegawai'];
    $tanggal_reservasi = $_POST['tanggal_reservasi'];
    $waktu_reservasi = $_POST['waktu_reservasi'];
    $id_layanan = $_POST['id_layanan'];
    $jumlah = $_POST['jumlah'];
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Ambil harga layanan
    $result = mysqli_query($conn, "SELECT harga FROM layanan WHERE id_layanan = $id_layanan");
    $layanan_data = mysqli_fetch_assoc($result);
    $harga = $layanan_data['harga'];

    // Hitung subtotal
    $subtotal = $harga * $jumlah;

    // Insert ke tabel reservasi
    $query_reservasi = "INSERT INTO reservasi (tanggal_reservasi, waktu_reservasi, id_pelanggan, id_pegawai)
                        VALUES ('$tanggal_reservasi', '$waktu_reservasi', $id_pelanggan, $id_pegawai)";
    if (mysqli_query($conn, $query_reservasi)) {
        $id_reservasi = mysqli_insert_id($conn);

        // Insert ke tabel reservasi_has_layanan
        $query_reservasi_layanan = "INSERT INTO reservasi_has_layanan (id_reservasi, id_layanan, jumlah, subtotal)
                                    VALUES ($id_reservasi, $id_layanan, $jumlah, $subtotal)";
        mysqli_query($conn, $query_reservasi_layanan);

        // Insert ke tabel pembayaran
        $query_pembayaran = "INSERT INTO pembayaran (id_reservasi, total_bayar, metode_pembayaran, tanggal_pembayaran)
                             VALUES ($id_reservasi, $subtotal, '$metode_pembayaran', CURDATE())";
        mysqli_query($conn, $query_pembayaran);

        echo "<script>alert('Reservasi berhasil dibuat!');</script>";
        header("Location: reservasi.php");
    } else {
        echo "<script>alert('Gagal membuat reservasi: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>Reservasi Baru</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="id_pelanggan" class="form-label">Pelanggan</label>
            <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
                <option value="" disabled selected>Pilih Pelanggan</option>
                <?php while ($row = mysqli_fetch_assoc($pelanggan)) : ?>
                    <option value="<?= $row['id_pelanggan']; ?>"><?= $row['nama']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_pegawai" class="form-label">Pegawai</label>
            <select name="id_pegawai" id="id_pegawai" class="form-select" required>
                <option value="" disabled selected>Pilih Pegawai</option>
                <?php while ($row = mysqli_fetch_assoc($pegawai)) : ?>
                    <option value="<?= $row['id_pegawai']; ?>"><?= $row['nama']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="tanggal_reservasi" class="form-label">Tanggal Reservasi</label>
            <input type="date" name="tanggal_reservasi" id="tanggal_reservasi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="waktu_reservasi" class="form-label">Waktu Reservasi</label>
            <input type="time" name="waktu_reservasi" id="waktu_reservasi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_layanan" class="form-label">Layanan</label>
            <select name="id_layanan" id="id_layanan" class="form-select" required>
                <option value="" disabled selected>Pilih Layanan</option>
                <?php while ($row = mysqli_fetch_assoc($layanan)) : ?>
                    <option value="<?= $row['id_layanan']; ?>"><?= $row['nama_layanan']; ?> - Rp <?= number_format($row['harga'], 2, ',', '.'); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                <option value="" disabled selected>Pilih Metode Pembayaran</option>
                <option value="Tunai">Tunai</option>
                <option value="Kartu Kredit">Kartu Kredit</option>
                <option value="Transfer Bank">Transfer Bank</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Buat Reservasi</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
