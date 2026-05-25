<?php

use PHPUnit\Framework\TestCase;

class CreateTableGuru extends TestCase
{

    public function testTableRead()
    {
        // Start output buffering
        ob_start();
        
        // Include the PHP file
        include __DIR__ . '/../storage/app/private/testingunit/testingunit.php';
        
        // Get the output and end output buffering
        $output = ob_get_clean();

        // Check if the table 'siswa' is created successfully
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "data_sekolah";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if the 'siswa' table exists
        $result = mysqli_query($conn, "SHOW TABLES LIKE 'guru'");

        // Assert that the table 'siswa' exists
        $this->assertTrue(mysqli_num_rows($result) > 0, "Tabel guru tidak ditemukan");

        mysqli_close($conn);
        
    }
}

?>
