<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //Mengecek koneksi
    die("Tidak bisa terkoneksi ke database Anda");
} 

// mendefinisikan variabel
$nim        = "";
$nama       = "";
$alamat     = "";
$fakultas   = "";
$sukses     = "";
$error      = "";

// Ini akan menangikap isi dari opnya, lalu akan didapatkan dari get dimasukkan dari get jika tidak opnya = 0
// Op --> menangkap variabel yang kita lewatkan di url kita
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Codingan menu delete
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

// Codingan menu edit, dimana data yg ditampilkan yaitu, nim, nama, alamat dan fakultas,
if ($op == 'edit') {
    $id         = $_GET['id'];
    $sql1       = "select * from mahasiswa where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $nim        = $r1['nim'];
    $nama       = $r1['nama'];
    $alamat     = $r1['alamat'];
    $fakultas   = $r1['fakultas'];

    // ji
    if ($nim == '') {
        $error = "Data tidak ditemukan";
    }
}
// digunakan untuk memasukkan datanya
if (isset($_POST['simpan'])) { //untuk create
    $nim        = $_POST['nim']; //buat variabel baru yg menangkap data dari value nim
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $fakultas   = $_POST['fakultas'];

    // operasi akan dijalankana apabila nim, nama, alamat dan fakultas memiliki data yang sudah terisi
    if ($nim && $nama && $alamat && $fakultas) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update mahasiswa set nim = '$nim',nama='$nama',alamat = '$alamat',fakultas='$fakultas' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { 
            
            //untuk insert (memasukkan data)
            $sql1   = "insert into mahasiswa(nim,nama,alamat,fakultas) values ('$nim','$nama','$alamat','$fakultas')";
            $q1     = mysqli_query($koneksi, $sql1); //memasukkan koneksi dan sql kita
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
    <title>Data Mahasiswa Gunadarma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" 
          rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!-- menstyle class mx-auto dan card -->
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
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                // menampilkan pesan error
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
                // menampilkan pesan sukses
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <!-- membuat form dengan method POST dan dengan label nim, nama, alamat dan fakultas -->
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <!-- jurusan yang ada di gunadarma -->
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value="">- Pilih Fakultas -</option>
                                <option value="informatika" <?php if ($fakultas == "informatika") echo "selected" ?>>Informatika</option>
                                <option value="elektro" <?php if ($fakultas == "elektro") echo "selected" ?>>Elektro</option>
                                <option value="mesin" <?php if ($fakultas == "mesin") echo "selected" ?>>Mesin</option>
                                <option value="sistem_informasi" <?php if ($fakultas == "sistem_informasi") echo "selected" ?>>Sistem Informasi</option>
                                <option value="arsitektur" <?php if ($fakultas == "arsitektur") echo "selected" ?>>Arsitektur</option>
                                <option value="psikologi" <?php if ($fakultas == "psikologi") echo "selected" ?>>Psikologi</option>
                                <option value="akuntansi" <?php if ($fakultas == "akuntansi") echo "selected" ?>>Akuntansi</option>
                                <option value="pariwisata" <?php if ($fakultas == "pariwisata") echo "selected" ?>>Pariwisata</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <!-- tombol simpan data dengan warna template bootstrap -->
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <!-- memberi warna teks dan background -->
            <div class="card-header text-white bg-secondary"> 
                Data Mahasiswa
            </div>
            <div class="card-body">
                <!-- kita bentuk menggunakan class tabel, tr = menjadi barisnya, th = menjadi kolom-kolomnya -->
                <table class="table"> 
                    <thead>
                        <tr>
                            <!-- th akan terbentuk kolom, membuat bagian nomor, nim, nama, alamat, fakultas dan aksi-->
                            <th scope="col">#</th>  
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Fakultas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <!-- Di tbody ini kita akan keluarkan data-data tadi -->
                    <tbody>
                        <?php
                        // simpan di variabel sql2 
                        // data akan menurun ( desc)
                        // nilai deafult 1 akan terus meningkat
                        $sql2   = "select * from mahasiswa order by id desc"; 
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        // menggunakan perulangan while, disini dibuat beberapa kolom untuk menampilkan data yang ada
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id         = $r2['id'];
                            $nim        = $r2['nim'];
                            $nama       = $r2['nama'];
                            $alamat     = $r2['alamat'];
                            $fakultas   = $r2['fakultas'];

                        ?>
                        <!-- tr = membentuk barisnya,  -->
                            <tr> 
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $nim ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $fakultas ?></td>
                                <td scope="row">
                                    <!-- Membuat tombol button Edit dan Delete dengan framework bootstrap -->   `
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-success">Edit</button></a>
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

</html>
