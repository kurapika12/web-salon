<?php
include '../config/database.php';

// Menangani proses tambah layanan
if (isset($_POST['submit_tambah'])) {
    $nama_layanan = $_POST['nama_layanan'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    $query_tambah = "INSERT INTO layanan (nama_layanan, deskripsi, harga) VALUES ('$nama_layanan', '$deskripsi', '$harga')";
    if (mysqli_query($conn, $query_tambah)) {
        echo "<script>alert('Layanan berhasil ditambahkan!');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan layanan: " . mysqli_error($conn) . "');</script>";
    }
}

// Menangani proses hapus layanan
if (isset($_GET['hapus_id'])) {
    $id_layanan = $_GET['hapus_id'];

    $query_hapus = "DELETE FROM layanan WHERE id_layanan = $id_layanan";
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>alert('Layanan berhasil dihapus!');</script>";
    } else {
        echo "<script>alert('Gagal menghapus layanan: " . mysqli_error($conn) . "');</script>";
    }
}

// Mengambil data layanan
$layanan = mysqli_query($conn, "SELECT * FROM layanan");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>Kelola Layanan</h2>

    <!-- Form Tambah Layanan -->
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="nama_layanan" class="form-label">Nama Layanan</label>
            <input type="text" name="nama_layanan" id="nama_layanan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" required>
        </div>
        <button type="submit" name="submit_tambah" class="btn btn-primary">Tambah Layanan</button>
    </form>

    <!-- Tabel Layanan -->
    <h3>Daftar Layanan</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Layanan</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($layanan)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_layanan']; ?></td>
                    <td><?= $row['deskripsi']; ?></td>
                    <td>Rp <?= number_format($row['harga'], 2, ',', '.'); ?></td>
                    <td>
                        <a href="layanan.php?hapus_id=<?= $row['id_layanan']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
