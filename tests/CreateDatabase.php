<?php
// File: FileReadTest.php

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;

class CreateDatabase extends TestCase
{

    public function testDatabaseConnection()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password);

        // Check if the connection was successful
        $this->assertTrue($conn !== false, "Koneksi dengan MYSQL gagal");

        // Close the connection
        mysqli_close($conn);
    }

    public function testDatabaseCreation()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password);

        // Check if the connection was successful
        $this->assertTrue($conn !== false, "Koneksi dengan MYSQL gagal");

        $database = "data_sekolah";

        // Check if the database creation was successful
        $result = mysqli_query($conn, "CREATE DATABASE $database");
        $this->assertTrue($result !== false, "Database gagal ditemukan atau dibuat");

        // Close the connection
        mysqli_close($conn);
    }
}

?>
