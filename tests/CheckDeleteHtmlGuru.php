<?php
namespace Tests;


use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckDeleteHtmlGuru extends TestCase
{

    public function testFileRead()
    {
        // Start output buffering
        ob_start();
        
        // Include the PHP file
        include __DIR__ . '/../storage/app/private/testingunit/testingunit.php';
        
        // Get the output and end output buffering
        $output = ob_get_clean();
    }
}

?>
