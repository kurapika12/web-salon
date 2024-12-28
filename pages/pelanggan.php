<?php
include '../config/database.php';

// Menangani proses hapus pelanggan
if (isset($_GET['hapus_id'])) {
    $id_pelanggan = $_GET['hapus_id'];

    // Hapus data di tabel reservasi yang terkait dengan pelanggan
    $query_hapus_reservasi = "DELETE FROM reservasi WHERE id_pelanggan = $id_pelanggan";
    if (mysqli_query($conn, $query_hapus_reservasi)) {
        // Kemudian hapus data pelanggan
        $query_hapus_pelanggan = "DELETE FROM pelanggan WHERE id_pelanggan = $id_pelanggan";
        if (mysqli_query($conn, $query_hapus_pelanggan)) {
            echo "<script>alert('Pelanggan dan data terkait berhasil dihapus!');</script>";
        } else {
            echo "<script>alert('Gagal menghapus pelanggan: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Gagal menghapus data reservasi: " . mysqli_error($conn) . "');</script>";
    }
}

// Ambil data pelanggan
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");

// Proses penambahan pelanggan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $email = $_POST['email'];

    $query = "INSERT INTO pelanggan (nama, alamat, no_telepon, email) 
              VALUES ('$nama', '$alamat', '$no_telepon', '$email')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pelanggan berhasil ditambahkan');</script>";
        header("Location: pelanggan.php");
    } else {
        echo "<script>alert('Gagal menambahkan pelanggan: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
<h3 class="mt-5">Tambah Pelanggan</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="no_telepon" class="form-label">No Telepon</label>
            <input type="text" name="no_telepon" id="no_telepon" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Pelanggan</button>
    </form>

    <h2>Data Pelanggan</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($pelanggan)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td><?= $row['no_telepon']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td>
                        <!-- Tombol Hapus Pelanggan -->
                        <a href="pelanggan.php?hapus_id=<?= $row['id_pelanggan']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini? Semua data terkait akan dihapus.')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
