<?php
    use PHPUnit\Framework\TestCase;

    class CheckConnection extends TestCase
    {
        public function testFileRead()
        {
            // Start output buffering
            ob_start();
            
            // Include the PHP file
            include __DIR__ . '/../storage/app/private/testingunit/testingunit.php';
            
            // Get the output and end output buffering
            $output = ob_get_clean();
            
            // Check if the connection was successful
            $this->assertTrue(mysqli_ping($conn), "Koneksi dengan MYSQL gagal");
            
            // Check if the database exists
            $result = mysqli_select_db($conn, $database);
            $this->assertTrue($result, "Database gagal ditemukan atau dibuat");
            
            // Close the connection
            mysqli_close($conn);
            
        }
    }
?>
