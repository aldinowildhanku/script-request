<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nilai dari input form
    $user_id = $_POST['user_id'];
    $server_id = $_POST['server_id'];
    
    // Variabel tetap
    $licanse = 'licanse kode';
    $kode = 'kode';
    
    // URL API yang akan diakses
    $api_url = "https://project.dinotrostore.com/games/api/cek?user_id=$user_id&server_id=$server_id&licanse=$licanse&kode=$kode";
    
    // Inisiasi curl
    $ch = curl_init();
    
    // Set curl options
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Untuk follow redirect jika ada
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL (jika diperlukan)

    // Eksekusi curl dan ambil response
    $response = curl_exec($ch);

    // Cek apakah curl error
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
    }
    
    // Tutup curl
    curl_close($ch);
    
    // Debugging jika ada error dari curl
    if (isset($error_msg)) {
        echo "Curl Error: " . $error_msg;
        exit();
    }

    // Decode JSON response
    $result = json_decode($response, true);
    
    // Debugging response yang diterima
    echo "<pre>";
    print_r($response);
    echo "</pre>";

    // Cek apakah response berhasil di-decode menjadi array
    if ($result === null) {
        echo "Error decoding JSON. Response yang diterima: " . $response;
    } else {
        // Cek status response
        if ($result['status'] == 'true') {
            // Jika sukses
            $nama = $result['data']['nama'];
            $message = $result['data']['message'];
            echo "Nama: " . $nama . "<br>";
            echo "Pesan: " . $message . "<br>";
        } elseif ($result['status'] == 'error_server') {
            // Jika gagal
            $message = $result['data']['message'];
            echo "Pesan: " . $message . "<br>";
        } else {
            echo "Terjadi kesalahan saat mengambil data dari API.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form API</title>
</head>
<body>
    <h1>Masukkan Data</h1>
    <form method="POST" action="">
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" id="user_id" required><br><br>
        
        <label for="server_id">Server ID:</label>
        <input type="text" name="server_id" id="server_id" required><br><br>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>
