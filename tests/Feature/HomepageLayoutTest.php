<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class HomepageLayoutTest extends TestCase
{
    /**
     * A basic feature test example.
     * ./vendor/bin/phpunit
     */
   
    public function testHtml():void{
      
        //$response = $this->get('/php/result-task');
        $response = $this->get("/phpunit/result-test-student/");
        //$response = $this->get("/execute-shell-command");
        //$response = $this->get('/phpunit/studi-kasus/upload-test-student');

        $html = "<!doctype html>
        <html lang=\"en\">
         <head>
            <title>Belajar Membuat Heading dan Paragraph</title>
         </head>
         <body>
             <h1>Heading ke-1</h1>
              <h2>Heading ke-2</h2>
              <h3>Heading ke-3</h3>
              <h4>Heading ke-4</h4>
              <h5>Heading ke-5</h5>
              <h6>Heading ke-6</h6>
            <p> <strong>ini contoh paragraph</strong></p>
          </body>
         </html>
        ";
        $test = str_replace(array("\r\n","\r","\n"," "),"",$html);
        $result_test = htmlspecialchars($test);
        //$this->assertEquals('10',$response->content()); 

        $result_content = str_replace(array("\r\n","\r"," "),"", $response->content());
        $this->assertStringContainsString($result_test, $result_content);
        
        
    }
    function testHtml_if(){
       
        $this->assertEquals('10',10); 

    }
   
}
