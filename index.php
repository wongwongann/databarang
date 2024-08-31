<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'barang3';

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die('Koneksi Gagal: ' . mysqli_connect_error());
}

$kode_barang = "";
$nama_barang = "";
$jumlah_barang = "";
$harga_barang = "";
$error_barang = "";
$sukses_barang = "";

if (isset($_GET['op']) && isset($_GET['kode_barang'])) {
    $op = $_GET['op'];
    $kode_barang = $_GET['kode_barang'];
} else {
    $op = "";
    $kode_barang = null;
}

if ($op == 'edit_barang' && isset($kode_barang)) {
    $sql1 = "SELECT * FROM `tabel barang` WHERE `kode barang` = '$kode_barang'";
    $q1 = mysqli_query($koneksi, $sql1);
    if (mysqli_num_rows($q1) > 0) {
        $r1 = mysqli_fetch_array($q1);
        $kode_barang = $r1['kode barang'];
        $nama_barang = $r1['nama barang'];
        $jumlah_barang = $r1['jumlah barang'];
        $harga_barang = $r1['harga barang'];
    } else {
        $error_barang = "Data Tidak Ditemukan";
    }
}

if ($op == 'delete_barang' && isset($kode_barang)) {
    $sql1 = "DELETE FROM `tabel barang` WHERE `kode barang` = '$kode_barang'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses_barang = "Data Barang Berhasil Dihapus";
    } else {
        $error_barang = "Data Barang Gagal Dihapus";
    }
}

if (isset($_POST['save_barang'])) {
    $kode_barang = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
    $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $jumlah_barang = mysqli_real_escape_string($koneksi, $_POST['jumlah_barang']);
    $harga_barang = mysqli_real_escape_string($koneksi, $_POST['harga_barang']);

    if ($kode_barang && $nama_barang && $jumlah_barang && $harga_barang) {
        $sql_check = "SELECT * FROM `tabel barang` WHERE `kode barang` = '$kode_barang'";
        $q_check = mysqli_query($koneksi, $sql_check);

        if (mysqli_num_rows($q_check) > 0) {
            $error_barang = "Data dengan Kode Barang $kode_barang sudah ada!";
        } else {
            $sql1 = "INSERT INTO `tabel barang` (`kode barang`, `nama barang`, `jumlah barang`, `harga barang`) 
                     VALUES ('$kode_barang', '$nama_barang', '$jumlah_barang', '$harga_barang')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses_barang = "Data Barang Berhasil Disimpan";
            } else {
                $error_barang = "Data Barang Gagal Disimpan";
            }
        }
    } else {
        $error_barang = "Data Barang Tidak Lengkap";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .mx-auto { width: 800px; }
        .card { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="mx-auto">
        <!-- Add/Edit Data -->
        <div class="card">
            <div class="card-header">Create / Edit Data</div>
            <div class="card-body">
                <?php if ($error_barang): ?>
                    <div class="alert alert-danger" role="alert"><?php echo $error_barang; ?></div>
                <?php endif; ?>

                <?php if ($sukses_barang): ?>
                    <div class="alert alert-success" role="alert"><?php echo $sukses_barang; ?></div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="mb-3 row">
                        <label for="kode_barang" class="col-sm-2 col-form-label">Kode Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="<?php echo htmlspecialchars($kode_barang); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($nama_barang); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jumlah_barang" class="col-sm-2 col-form-label">Jumlah Barang</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="jumlah_barang" name="jumlah_barang" value="<?php echo htmlspecialchars($jumlah_barang); ?>" min="1" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="harga_barang" class="col-sm-2 col-form-label">Harga Barang</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="harga_barang" name="harga_barang" value="<?php echo htmlspecialchars($harga_barang); ?>" min="0" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="save_barang" value="Save Data Barang" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

        <!-- Show Data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">Data Barang</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Kode Barang</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Jumlah Barang</th>
                            <th scope="col">Harga Barang</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM `tabel barang` ORDER BY `kode barang` DESC";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $kode_barang = htmlspecialchars($r2['kode barang']);
                            $nama_barang = htmlspecialchars($r2['nama barang']);
                            $jumlah_barang = htmlspecialchars($r2['jumlah barang']);
                            $harga_barang = htmlspecialchars($r2['harga barang']);
                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++; ?></th>
                                <td><?php echo $kode_barang; ?></td>
                                <td><?php echo $nama_barang; ?></td>
                                <td><?php echo $jumlah_barang; ?></td>
                                <td><?php echo $harga_barang; ?></td>
                                <td>
                                    <a href="index.php?op=edit_barang&kode_barang=<?php echo $kode_barang; ?>" class="btn btn-warning">Edit</a>
                                    <a href="index.php?op=delete_barang&kode_barang=<?php echo $kode_barang; ?>" onclick="return confirm('Yakin mau hapus data?')" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>f
</body>
</html>
