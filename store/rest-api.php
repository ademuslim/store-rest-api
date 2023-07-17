<?php
header("Content-Type:application/json");
include "koneksi.php";
mysqli_set_charset($conn, 'utf8');
$method = $_SERVER['REQUEST_METHOD'];
$results = array();


if ($method == 'GET') {
    $query = mysqli_query($conn, 'SELECT * FROM produk');

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $results['Status']['success'] = true;
            $results['Status']['code'] = 200;
            $results['Status']['description'] = 'Request Valid';
            $results['Hasil'][] = [
                'id_produk' => $row['id_produk'],
                'nama_produk' => $row['nama_produk'],
                'harga' => $row['harga'],
                'stok' => $row['stok']
            ];
        }
    } else {
        $results['Status']['code'] = 400;
        $results['Status']['description'] = 'Request Invalid';
    }
} elseif ($method == 'POST') {
    // insert data
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $sql = "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama_produk', '$harga', '$stok')";

    $conn->query($sql);

    $results['Status']['success'] = true;
    $results['Status']['code'] = 200;
    $results['Status']['description'] = 'Request Valid';
    $results['Hasil'] = array(
        'nama_produk' => $nama_produk,
        'harga' => $harga,
        'stok' => $stok
    );
} elseif ($method == 'PUT') {
    // update data
    parse_str(file_get_contents('php://input'), $_PUT);
    $id_produk = $_PUT['id_produk'];
    $nama_produk = $_PUT['nama_produk'];
    $harga = $_PUT['harga'];
    $stok = $_PUT['stok'];

    $sql = "UPDATE produk SET nama_produk = '$nama_produk', harga = '$harga', stok = '$stok' WHERE id_produk ='$id_produk'";

    $conn->query($sql);

    $results['Status']['success'] = true;
    $results['Status']['code'] = 200;
    $results['Status']['description'] = 'Update Data Berhasil';
    $results['Hasil'] = array(
        'nama_produk' => $nama_produk,
        'harga' => $harga,
        'stok' => $stok
    );
} elseif ($method == 'DELETE') {
    // Delete Data
    parse_str(file_get_contents('php://input'), $_DELETE);

    $id_produk = $_DELETE['id_produk'];

    $sql = "DELETE FROM produk WHERE id_produk ='$id_produk'";
    $conn->query($sql);

    $results['Status']['success'] = true;
    $results['Status']['code'] = 200;
    $results['Status']['description'] = 'Hapus Data Berhasil';
} else {
    $results['Status']['code'] = 404;
}

//Menampilkan Data JSON dari Database
$json = json_encode($results);
print_r($json);
