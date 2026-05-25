<?php
class UserQueryCest
{

    public function testUserQuery(\Tests\Support\AcceptanceTester $I, \Codeception\Module\Db $db)
    {
        $userId = trim(getenv('USER_ID'));
        $query = getenv('USER_QUERY');

        if (!$query) {
            $I->fail('User query is empty');
        }

        // Validasi minimal: query harus diawali dengan kata kunci SQL yang diizinkan
        if (!preg_match('/^\s*(INSERT|UPDATE|DELETE|SELECT)\b/i', $query)) {
            throw new \Exception('The query must start with a valid SQL command (INSERT, UPDATE, DELETE, SELECT)');
        }

        // Validasi WHERE pada DELETE/UPDATE agar harus ada operator setelah kolom
        if (preg_match('/\b(DELETE|UPDATE)\b.+\bWHERE\b\s+([a-zA-Z0-9_]+)\s*;?$/i', $query)) {
            throw new \Exception('The WHERE condition must have a clear comparison operator or condition (e.g. =, IS NOT NULL, LIKE, etc.)');
        }

        // Validasi WHERE tanpa operator (=, <>, !=, IS, LIKE, BETWEEN, IN, >, <, >=, <=)
        if (preg_match('/\bWHERE\b\s+[a-zA-Z0-9_]+\s*($|;)/i', $query)) {
            throw new \Exception('The WHERE condition must have a clear comparison operator or condition (e.g. =, IS NOT NULL, LIKE, etc.)');
        }

        // Validasi INSERT kolom dan nilai
        if (preg_match('/^\s*INSERT\s+INTO\s+(\w+)\s*\(([^)]+)\)\s*VALUES\s*\(([^)]+)\)/i', $query, $matches)) {
            $columns = array_map('trim', explode(',', $matches[2]));
            $values = array_map('trim', explode(',', $matches[3]));
            if (count($columns) !== count($values)) {
                throw new \Exception('The number of columns and values in the INSERT statement must be the same');
            }
        }

        // Validasi INSERT tanpa penamaan kolom
        if (preg_match('/^\s*INSERT\s+INTO\s+(\w+)\s*VALUES\s*\(([^)]+)\)/i', $query, $matches)) {
            // Anda bisa tentukan jumlah kolom tabel mk secara hardcode atau dinamis (misal: 2 kolom)
            $expectedColumnCount = 2; // contoh untuk tabel mk
            $values = array_map('trim', explode(',', $matches[2]));
            if (count($values) !== $expectedColumnCount) {
                throw new \Exception('The number of values in the INSERT statement must match the number of columns in the table');
            }
        }

        // Jalankan query jika lolos validasi
        // try {
        //     $db->_getDbh()->exec($query);
        // } catch (\PDOException $e) {
        //     $I->fail($e->getMessage());
        // }
    }
}
