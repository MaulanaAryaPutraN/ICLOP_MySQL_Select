-- ============================================================
-- mytap_setup.sql
--
-- Dijalankan SATU KALI saat setup server.
-- Membuat database tap yang dipakai oleh semua db_kuliah_*
-- sebagai TAP counter (tap.ok, tap.counters).
--
-- Install:
--   mysql -u root -p < database/sql/mytap_setup.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS tap CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tap;

DELIMITER //

DROP TABLE IF EXISTS counters //
CREATE TABLE counters (
    id       INT PRIMARY KEY,
    test_num INT DEFAULT 0
) //
INSERT INTO counters (id, test_num) VALUES (1, 0) //

DROP FUNCTION IF EXISTS plan //
CREATE FUNCTION plan(test_count INT) RETURNS VARCHAR(50) DETERMINISTIC
BEGIN
    UPDATE counters SET test_num = 0 WHERE id = 1;
    RETURN CONCAT('1..', test_count);
END //

DROP FUNCTION IF EXISTS ok //
CREATE FUNCTION ok(result BOOLEAN, description VARCHAR(500)) RETURNS VARCHAR(1000) DETERMINISTIC
BEGIN
    DECLARE v_test_num INT;
    DECLARE v_output   VARCHAR(1000);

    SELECT test_num INTO v_test_num FROM counters WHERE id = 1;
    SET v_test_num = v_test_num + 1;
    UPDATE counters SET test_num = v_test_num WHERE id = 1;

    IF result IS NULL OR result = FALSE THEN
        SET v_output = CONCAT('not ok ', v_test_num, ' - ', description);
    ELSE
        SET v_output = CONCAT('ok ',     v_test_num, ' - ', description);
    END IF;

    RETURN v_output;
END //

DELIMITER ;