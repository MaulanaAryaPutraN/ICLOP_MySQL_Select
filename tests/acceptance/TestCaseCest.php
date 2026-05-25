<?php
class TestCaseCest
{
    public function testInsert(\Tests\Support\AcceptanceTester $I)
    {
        // 1. Pastikan tidak ada data yang sama dengan data yang akan di-insert
        //    di tabel mk, jika ada maka hapus terlebih dahulu data tersebut

        // 2. command untuk running di terminal
        // vendor/bin/codecept run acceptance TestCaseCest:testInsert --debug

        $I->comment('Insert data ke tabel mk');
        $I->haveInDatabase('mk', [
            'kode_mk' => '02010',
            'nama_mk' => 'Basis Data'
        ]);
        $I->comment('Cek data di tabel mk');
        $I->seeInDatabase('mk', [
            'kode_mk' => '02010',
            'nama_mk' => 'Basis Data'
        ]);
        $I->comment('Data sudah di-insert, cek database sekarang!');
        sleep(10); // Beri waktu 10 detik untuk cek manual di phpMyAdmin
    }

    public function testUpdate(\Tests\Support\AcceptanceTester $I)
    {
        // 1. INSERT terlebih dahulu secara manual di database atau bisa menggunakan data yang sudah ada 
        //    sebelum menjalankan function ini 
        // example: INSERT INTO mk (kode_mk, nama_mk) VALUES ('02011', 'Basis Data');

        // 2. command untuk running di terminal
        // vendor/bin/codecept run acceptance TestCaseCest:testUpdate --debug

        $I->comment('Update data di tabel mk');
        $I->updateInDatabase('mk', ['nama_mk' => 'Sistem Basis Data'], ['kode_mk' => '02011']);
        $I->seeInDatabase('mk', [
            'kode_mk' => '02011',
            'nama_mk' => 'Sistem Basis Data'
        ]);

        //cek apakah data sudah terupdate
        $I->comment('Data sudah di-update, cek database sekarang!');
        $I->dontSeeInDatabase('mk', [
            'kode_mk' => '02011',
            'nama_mk' => 'Basis Data'
        ]);
    }
}
