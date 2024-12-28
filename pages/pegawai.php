<?php
include '../config/database.php';

// Menangani proses hapus pegawai
if (isset($_GET['hapus_id'])) {
    $id_pegawai = $_GET['hapus_id'];

    // Hapus pegawai berdasarkan id
    $query_hapus = "DELETE FROM pegawai WHERE id_pegawai = $id_pegawai";

    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>alert('Pegawai berhasil dihapus');</script>";
        header("Location: pegawai.php");
    } else {
        echo "<script>alert('Gagal menghapus pegawai: " . mysqli_error($conn) . "');</script>";
    }
}

// Ambil data pegawai
$pegawai = mysqli_query($conn, "SELECT * FROM pegawai");

// Proses penambahan pegawai baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $no_telepon = $_POST['no_telepon'];
    $gaji = $_POST['gaji'];

    $query = "INSERT INTO pegawai (nama, jabatan, no_telepon, gaji) 
              VALUES ('$nama', '$jabatan', '$no_telepon', '$gaji')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pegawai berhasil ditambahkan');</script>";
        header("Location: pegawai.php");
    } else {
        echo "<script>alert('Gagal menambahkan pegawai: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
<h3 class="mt-5">Tambah Pegawai</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="no_telepon" class="form-label">No Telepon</label>
            <input type="text" name="no_telepon" id="no_telepon" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="gaji" class="form-label">Gaji</label>
            <input type="number" step="0.01" name="gaji" id="gaji" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Pegawai</button>
    </form>
    
    <h2>Data Pegawai</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>No Telepon</th>
                <th>Gaji</th>
                <th>Aksi</th> <!-- Menambahkan kolom Aksi untuk tombol hapus -->
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($pegawai)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['jabatan']; ?></td>
                    <td><?= $row['no_telepon']; ?></td>
                    <td>Rp <?= number_format($row['gaji'], 2, ',', '.'); ?></td>
                    <td>
                        <!-- Tombol Hapus Pegawai -->
                        <a href="pegawai.php?hapus_id=<?= $row['id_pegawai']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pegawai ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
