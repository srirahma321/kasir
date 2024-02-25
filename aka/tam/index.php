<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$nama_barang        = "";
$jumlah_barang       = "";
$jenis_barang   = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $id         = $_GET['id'];
    $sql1       = "delete from mahasiswa where id = '$id'";
    $q1         = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id         = $_GET['id'];
    $sql1       = "select * from mahasiswa where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $nama_barang        = $r1['nama_barang'];
    $jumlah_barang       = $r1['jumlah_barang'];
    $jenis_barang   = $r1['jenis_barang'];

    if ($nama_barang == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $nama_barang        = $_POST['nama_barang'];
    $jumlah_barang       = $_POST['jumlah_barang'];
    $jenis_barang   = $_POST['jenis_barang'];

    if ($nama_barang && $jumlah_barang && $jenis_barang) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update mahasiswa set nama_barang = '$nama_barang',jumlah_barang='$jumlah_barang',jenis_barang='$jenis_barang' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1   = "insert into mahasiswa(nama_barang,jumlah_barang,jenis_barang) values ('$nama_barang','$jumlah_barang','$jenis_barang')";
            $q1     = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses     = "Berhasil memasukkan data baru";
            } else {
                $error      = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pet Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header text-white bg-info">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");//5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?php echo $nama_barang ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jumlah_barang" class="col-sm-2 col-form-label">Jumlah Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="jumlah_barang" name="jumlah_barang" value="<?php echo $jumlah_barang ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jenis_barang" class="col-sm-2 col-form-label">Jenis Barang</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="jenis_barang" id="jenis_barang">
                                <option value="">- Pilih Jenis -</option>
                                <option value="makanan" <?php if ($jenis_barang == "makanan") echo "selected" ?>>Makanan</option>
                                <option value="pasir" <?php if ($jenis_barang == "pasir") echo "selected" ?>>Pasir</option>
                                <option value="susu" <?php if ($jenis_barang == "susu") echo "selected" ?>>Susu</option>
                                <option value="obat" <?php if ($jenis_barang == "obat") echo "selected" ?>>Obat</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-success">
                Data Pet Shop
            </div>
            <div class="card-body">
                <table class="table" id="example">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Jumlah Barang</th>
                            <th scope="col">Jenis Barang</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select * from mahasiswa order by id desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id         = $r2['id'];
                            $nama_barang        = $r2['nama_barang'];
                            $jumlah_barang       = $r2['jumlah_barang'];
                            $jenis_barang   = $r2['jenis_barang'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $nama_barang ?></td>
                                <td scope="row"><?php echo $jumlah_barang ?></td>
                                <td scope="row"><?php echo $jenis_barang ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>            
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
   
</body> 

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>new DataTable('#example');</script>
</html>
