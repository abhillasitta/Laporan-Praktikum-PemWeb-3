<?php
// ==========================================
// BAGIAN 1: LOGIKA PHP (BACKEND)
// ==========================================

// Inisialisasi variabel kosong agar tidak error saat pertama kali dibuka
$nama = "";
$nim = "";
$kehadiran = "";
$tugas = "";
$uts = "";
$uas = "";

// Variabel kontrol untuk menampilkan hasil atau error
$tampil_hasil = false;
$pesan_error = "";

// Cek apakah tombol "Proses" sudah ditekan
if (isset($_POST['proses'])) {
    
    // 1. Ambil data dari input form
    $nama = trim($_POST['nama']);
    $nim  = trim($_POST['nim']);
    $kehadiran = $_POST['kehadiran'];
    $tugas = $_POST['tugas'];
    $uts   = $_POST['uts'];
    $uas   = $_POST['uas'];

    // 2. VALIDASI: Cek apakah ada kolom yang kosong
    // Kita hapus atribut 'required' di HTML nanti agar validasi ini yang bekerja
    if (empty($nama) || empty($nim) || $kehadiran === "" || $tugas === "" || $uts === "" || $uas === "") {
        $pesan_error = "Semua kolom harus diisi!";
    } else {
        // Jika semua terisi, lanjut ke perhitungan
        
        // Konversi input ke tipe data float (angka desimal)
        $val_absen = floatval($kehadiran);
        $val_tugas = floatval($tugas);
        $val_uts   = floatval($uts);
        $val_uas   = floatval($uas);

        // 3. Menghitung Nilai Akhir sesuai bobot
        $nilai_akhir = ($val_absen * 0.1) + ($val_tugas * 0.2) + ($val_uts * 0.3) + ($val_uas * 0.4);

        // 4. Menentukan Grade Huruf
        if ($nilai_akhir >= 85) { $grade = "A"; }
        elseif ($nilai_akhir >= 70) { $grade = "B"; }
        elseif ($nilai_akhir >= 55) { $grade = "C"; }
        elseif ($nilai_akhir >= 40) { $grade = "D"; }
        else { $grade = "E"; }

        // 5. Logika Kelulusan (Default LULUS, lalu eliminasi jika gagal syarat)
        $lulus = true;

        // Syarat 1: Nilai Akhir harus >= 60
        if ($nilai_akhir < 60) { $lulus = false; }
        
        // Syarat 2: Absen harus > 70 (artinya 70 pas itu GAGAL)
        if ($val_absen <= 70) { $lulus = false; }
        
        // Syarat 3: Komponen Tugas, UTS, UAS tidak boleh di bawah 40
        if ($val_tugas < 40 || $val_uts < 40 || $val_uas < 40) { $lulus = false; }

        // Tentukan teks status dan warna background
        $status_text = $lulus ? "LULUS" : "TIDAK LULUS";
        $bg_class = $lulus ? "bg-success" : "bg-danger"; // Hijau jika lulus, Merah jika gagal
        
        // Aktifkan trigger untuk menampilkan card hasil
        $tampil_hasil = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body { background-color: #f8f9fa; }
        .form-label { font-weight: bold; }
    </style>
</head>

<body>
    <div class="container mt-4 mb-5 px-5">
        
        <div class="card shadow-sm">
            <div class="card-header text-center bg-primary text-white">
                <h1 class="h4 mb-0">Form Penilaian Mahasiswa</h1>
            </div>
            <div class="card-body">
                
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Masukkan Nama</label>
                        <input type="text" class="form-control" name="nama" placeholder="Agus" value="<?php echo $nama; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Masukkan NIM</label>
                        <input type="text" class="form-control" name="nim" placeholder="202332xxx" value="<?php echo $nim; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Kehadiran (10%)</label>
                        <input type="number" class="form-control" name="kehadiran" placeholder="Min > 70%" min="0" max="100" value="<?php echo $kehadiran; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Tugas (20%)</label>
                        <input type="number" class="form-control" name="tugas" placeholder="0 - 100" min="0" max="100" value="<?php echo $tugas; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai UTS (30%)</label>
                        <input type="number" class="form-control" name="uts" placeholder="0 - 100" min="0" max="100" value="<?php echo $uts; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai UAS (40%)</label>
                        <input type="number" class="form-control" name="uas" placeholder="0 - 100" min="0" max="100" value="<?php echo $uas; ?>">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="proses" class="btn btn-primary">Proses</button>
                    </div>
                </form>

                <?php if (!empty($pesan_error)) : ?>
                    <div class="alert alert-danger mt-3 mb-0 text-center" role="alert">
                        <strong><?php echo $pesan_error; ?></strong>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        
        
        <?php if ($tampil_hasil) : ?>
            <div class="card mt-4 shadow-sm">
                <div class="card-header <?php echo $bg_class; ?> text-white">
                    <h5 class="mb-0 text-center">Hasil Penilaian</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 text-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold">Nama: <?php echo htmlspecialchars($nama); ?></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold">NIM: <?php echo htmlspecialchars($nim); ?></h5>
                        </div>
                    </div>
                    
                    <hr> <div class="px-3">
                        <p class="mb-1">Nilai Kehadiran: <?php echo $val_absen; ?>%</p>
                        <p class="mb-1">Nilai Tugas: <?php echo $val_tugas; ?></p>
                        <p class="mb-1">Nilai UTS: <?php echo $val_uts; ?></p>
                        <p class="mb-1">Nilai UAS: <?php echo $val_uas; ?></p>
                        
                        <hr>
                        
                        <p class="mb-1 fw-bold">Nilai Akhir: <?php echo number_format($nilai_akhir, 2); ?></p>
                        <p class="mb-1 fw-bold">Grade: <?php echo $grade; ?></p>
                        
                        <h5 class="mt-3 fw-bold">Status: <span class="<?php echo $lulus ? 'text-success' : 'text-danger'; ?>"><?php echo $status_text; ?></span></h5>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="polos.php" class="btn <?php echo $bg_class; ?>">Selesai</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>