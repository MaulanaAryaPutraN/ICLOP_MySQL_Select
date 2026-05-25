<?php
namespace Tests;


use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckInsertGuru extends TestCase
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
        $hit = 0;
        $lisa = mysqli_query($conn, "SELECT COUNT(*) FROM guru WHERE nama LIKE '%Teguh%' ");
        $hit = mysqli_num_rows($lisa) == 1 ? $hit+1 : $hit+0;

        $joshua = mysqli_query($conn, "SELECT COUNT(*) FROM guru WHERE nama LIKE '%Sari%' ");
        $hit = mysqli_num_rows($joshua) == 1 ? $hit+1 : $hit+0;

        $dion = mysqli_query($conn, "SELECT COUNT(*) FROM guru WHERE nama LIKE '%Prihadi%' ");
        $hit = mysqli_num_rows($dion) == 1 ? $hit+1 : $hit+0;
        
        // Assert that the table 'siswa' exists
        $this->assertTrue($hit > 1, "Insert Berhasil ".$hit." data");

        mysqli_close($conn);
    
    }
}

?>
