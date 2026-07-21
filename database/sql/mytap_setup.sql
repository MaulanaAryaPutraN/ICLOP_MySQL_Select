-- ============================================================
-- mytap_setup.sql
--
-- Dijalankan satu kali saat pertama kali db_kuliah dibuat,
-- ATAU setiap kali schema direset (isi data dihapus, tapi
-- tabel TAP tetap aman karena pakai CREATE TABLE IF NOT EXISTS).
--
-- Semua objek TAP (counters, ok, plan) berada di db_kuliah
-- itu sendiri — tidak butuh database tap terpisah.
--
-- Install:
--   mysql -u root -p db_kuliah < database/sql/mytap_setup.sql
-- ============================================================

-- Tabel counter TAP: dibuat sekali, tidak dihapus saat schema direset
CREATE TABLE IF NOT EXISTS _tap_counters (
    id       INT PRIMARY KEY,
    test_num INT DEFAULT 0
);

-- Pastikan row counter selalu ada
INSERT IGNORE INTO _tap_counters (id, test_num) VALUES (1, 0);

DELIMITER //

DROP FUNCTION IF EXISTS ok //
CREATE FUNCTION ok(result BOOLEAN, description VARCHAR(500)) RETURNS VARCHAR(1000) DETERMINISTIC
BEGIN
    DECLARE v_test_num INT;
    DECLARE v_output   VARCHAR(1000);

    SELECT test_num INTO v_test_num FROM _tap_counters WHERE id = 1;
    SET v_test_num = v_test_num + 1;
    UPDATE _tap_counters SET test_num = v_test_num WHERE id = 1;

    IF result IS NULL OR result = FALSE THEN
        SET v_output = CONCAT('not ok ', v_test_num, ' - ', description);
    ELSE
        SET v_output = CONCAT('ok ',     v_test_num, ' - ', description);
    END IF;

    RETURN v_output;
END //

DELIMITER ;