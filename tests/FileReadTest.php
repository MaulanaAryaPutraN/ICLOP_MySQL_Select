<?php
// File: FileReadTest.php

use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Session\Session;
use PHPUnit\Framework\TestCase;

class FileReadTest extends TestCase
{

    public function testFileRead()
    {
        // Start output buffering
        ob_start();
        
        // Include the PHP file
        include __DIR__ . '/../storage/app\private\febri syawaldi\febri syawaldi_createDB.php';
        
        // Get the output and end output buffering
        $output = ob_get_clean();
        
        // Check if the connection message is in the output
        $this->assertStringContainsString('Koneksi dengan MYSQL berhasil', $output, "Koneksi dengan MYSQL gagal");
        
        // Check if the database creation message is in the output
        $this->assertStringContainsString('Database berhasil dibuat', $output, "Database gagal dibuat");
    }
}

// Sample function to read from a file (replace this with your actual implementation)
function readFileContent($filePath)
{
    if (!file_exists($filePath)) {
        throw new \Exception("File not found: $filePath");
    }

    return file_get_contents($filePath);
}
?>
