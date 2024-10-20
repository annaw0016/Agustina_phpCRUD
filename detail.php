<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_php";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Gunakan prepared statement untuk menghindari SQL Injection
    $stmt = $conn->prepare("SELECT * FROM tb_users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }

    // Jika diminta gambar, kirim gambar sebagai respons
    if (isset($_GET['show_image'])) {
        header("Content-type: image/jpeg"); // Sesuaikan dengan format gambar
        echo $row['foto']; // Tampilkan gambar dari BLOB
        exit; // Hentikan eksekusi lebih lanjut untuk hanya menampilkan gambar
    }
} else {
    echo "ID tidak ditemukan.";
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS tambahan untuk mempercantik tampilan */
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 40px;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-img-top {
            width: 100%;
            height: auto;
            max-height: 350px; /* Atur tinggi maksimum gambar */
            object-fit: contain; /* Menyesuaikan gambar di dalam frame tanpa terdistorsi */
            background-color: #f1f1f1; /* Tambahkan background untuk gambar kosong atau batas yang muncul */
            padding: 10px; /* Beri jarak kecil agar gambar lebih rapi di dalam card */
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-text {
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004080;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #565e64;
            border-color: #494f53;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center">Detail User</h1>
    <div class="card">
        <!-- Gunakan parameter show_image untuk menampilkan gambar -->
        <img src="detail.php?id=<?php echo $row['id']; ?>&show_image=1" class="card-img-top" alt="Foto User">
        <div class="card-body">
            <h5 class="card-title"><?php echo $row['nama']; ?></h5>
            <p class="card-text"><strong>Jenis Kelamin:</strong> <?php echo $row['jenis_kelamin']; ?></p>
            <p class="card-text"><strong>No HP:</strong> <?php echo $row['nohp']; ?></p>
            <p class="card-text"><strong>Email:</strong> <?php echo $row['email']; ?></p>
            <p class="card-text"><strong>Alamat:</strong> <?php echo $row['alamat']; ?></p>
            <p class="card-text"><strong>Alamat Lengkap:</strong> <?php echo $row['alamat_lngkp']; ?></p>
            <a href="index.php" class="btn btn-primary">Kembali</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
