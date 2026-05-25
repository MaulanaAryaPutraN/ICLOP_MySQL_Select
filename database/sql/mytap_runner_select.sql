-- ============================================================
-- mytap_runner_select.sql
-- database/sql/mytap_runner_select.sql
-- ============================================================

DELIMITER //

DROP FUNCTION IF EXISTS has_forbidden_keywords //
CREATE FUNCTION has_forbidden_keywords(query_text VARCHAR(5000))
RETURNS BOOLEAN DETERMINISTIC
BEGIN
    IF UPPER(query_text) REGEXP '\\b(INSERT|UPDATE|DELETE|CREATE|DROP|ALTER|TRUNCATE|REPLACE|UNION)\\b' THEN
        RETURN TRUE;
    END IF;
    RETURN FALSE;
END //

DROP FUNCTION IF EXISTS has_select_star //
CREATE FUNCTION has_select_star(query_text VARCHAR(5000))
RETURNS BOOLEAN DETERMINISTIC
BEGIN
    IF query_text REGEXP 'SELECT\\s+(DISTINCT\\s+)?\\*' THEN
        RETURN TRUE;
    END IF;
    RETURN FALSE;
END //

DROP FUNCTION IF EXISTS validate_select_syntax //
CREATE FUNCTION validate_select_syntax(query_text VARCHAR(5000))
RETURNS BOOLEAN DETERMINISTIC
BEGIN
    DECLARE clean_query VARCHAR(5000);
    DECLARE upper_query VARCHAR(5000);
    SET clean_query = TRIM(REGEXP_REPLACE(query_text, ';\\s*$', ''));
    SET upper_query = UPPER(clean_query);
    IF upper_query NOT REGEXP '^SELECT' THEN RETURN FALSE; END IF;
    IF upper_query NOT REGEXP 'FROM'   THEN RETURN FALSE; END IF;
    RETURN TRUE;
END //

DROP FUNCTION IF EXISTS validate_supported_clauses //
CREATE FUNCTION validate_supported_clauses(query_text VARCHAR(5000))
RETURNS BOOLEAN DETERMINISTIC
BEGIN
    DECLARE upper_query VARCHAR(5000);
    SET upper_query = UPPER(TRIM(REGEXP_REPLACE(query_text, ';\\s*$', '')));
    IF upper_query REGEXP '\\b(GROUP|HAVING|JOIN|INNER|LEFT|RIGHT|CROSS|ON|UNION|INTERSECT|EXCEPT|LOCK|FOR|INTO)\\b' THEN
        RETURN FALSE;
    END IF;
    RETURN TRUE;
END //

-- ============================================================
-- [FIX] expected_has_order_by
-- Deteksi ORDER BY di kunci jawaban → mode hash order-sensitive.
-- ============================================================
DROP FUNCTION IF EXISTS expected_has_order_by //
CREATE FUNCTION expected_has_order_by(query_text VARCHAR(5000))
RETURNS BOOLEAN DETERMINISTIC
BEGIN
    IF UPPER(query_text) REGEXP '\\bORDER\\s+BY\\b' THEN
        RETURN TRUE;
    END IF;
    RETURN FALSE;
END //

-- ============================================================
-- [FIX] expected_has_distinct
-- Deteksi SELECT DISTINCT di kunci jawaban.
-- ============================================================
DROP FUNCTION IF EXISTS expected_has_distinct //
CREATE FUNCTION expected_has_distinct(query_text VARCHAR(5000))
RETURNS BOOLEAN DETERMINISTIC
BEGIN
    IF UPPER(TRIM(REGEXP_REPLACE(query_text, ';\\s*$', ''))) REGEXP '^SELECT\\s+DISTINCT\\b' THEN
        RETURN TRUE;
    END IF;
    RETURN FALSE;
END //

-- ============================================================
-- [FIX] student_has_order_by
-- Deteksi ORDER BY di query mahasiswa.
-- ============================================================
DROP FUNCTION IF EXISTS student_has_order_by //
CREATE FUNCTION student_has_order_by(query_text VARCHAR(5000))
RETURNS BOOLEAN DETERMINISTIC
BEGIN
    IF UPPER(query_text) REGEXP '\\bORDER\\s+BY\\b' THEN
        RETURN TRUE;
    END IF;
    RETURN FALSE;
END //

-- ============================================================
-- [FIX] student_has_distinct
-- Deteksi SELECT DISTINCT di query mahasiswa.
-- ============================================================
DROP FUNCTION IF EXISTS student_has_distinct //
CREATE FUNCTION student_has_distinct(query_text VARCHAR(5000))
RETURNS BOOLEAN DETERMINISTIC
BEGIN
    IF UPPER(TRIM(REGEXP_REPLACE(query_text, ';\\s*$', ''))) REGEXP '^SELECT\\s+DISTINCT\\b' THEN
        RETURN TRUE;
    END IF;
    RETURN FALSE;
END //

DROP FUNCTION IF EXISTS clean_error_message //
CREATE FUNCTION clean_error_message(error_text VARCHAR(500))
RETURNS VARCHAR(500) DETERMINISTIC
BEGIN
    DECLARE result      VARCHAR(500);
    DECLARE error_upper VARCHAR(500);
    SET error_upper = UPPER(error_text);

    IF INSTR(error_upper, 'DOESN''T EXIST') > 0 THEN
        SET result = SUBSTRING(error_text, 1, INSTR(error_text, 'doesn''t') + 18);
        RETURN result;
    END IF;
    IF INSTR(error_upper, 'UNKNOWN COLUMN') > 0 THEN
        SET result = SUBSTRING(error_text, INSTR(error_text, 'Unknown'), 100);
        IF INSTR(result, '.') > 0 THEN
            SET result = SUBSTRING(result, 1, INSTR(result, '.'));
        END IF;
        RETURN result;
    END IF;
    IF INSTR(error_upper, 'UNKNOWN DATABASE') > 0 THEN
        RETURN SUBSTRING(error_text, INSTR(error_text, 'Unknown'), 80);
    END IF;
    RETURN SUBSTRING(error_text, 1, 80);
END //

-- ============================================================
-- MAIN PROCEDURE: test_select_query
--
-- Strategi perbandingan isi tabel (CHECK 7):
-- Karena temporary table TIDAK terdaftar di information_schema.COLUMNS,
-- kita gunakan cara lain:
--   Buat REAL (non-temporary) table dari SELECT,
--   ambil kolomnya dari information_schema, lalu drop.
--   Ini reliable karena real table terdaftar di information_schema.
--
-- [FIX] CHECK 7a – Structural check DISTINCT & ORDER BY:
--   Perbedaan penggunaan DISTINCT / ORDER BY antara query mahasiswa
--   dan kunci jawaban langsung dinyatakan sebagai "Result mismatch"
--   dengan format pesan yang sama seperti kesalahan kolom/WHERE/LIMIT,
--   tanpa perlu bergantung pada isi data di tabel.
--
-- [FIX] CHECK 7c – Order-sensitive hash:
--   Jika kunci jawaban pakai ORDER BY, hash dihitung via ROW_NUMBER()
--   sehingga urutan baris ikut dibandingkan (bukan di-sort ulang).
-- ============================================================
DROP PROCEDURE IF EXISTS test_select_query //
CREATE PROCEDURE test_select_query(
    IN p_query          VARCHAR(5000),
    IN p_expected_query VARCHAR(5000)
)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_clean_query      VARCHAR(5000);
    DECLARE v_clean_expected   VARCHAR(5000);
    DECLARE v_is_valid         BOOLEAN DEFAULT TRUE;
    DECLARE v_error_msg        VARCHAR(500) DEFAULT '';
    DECLARE v_test_result      VARCHAR(1000);
    DECLARE v_sqlstate         VARCHAR(10);
    DECLARE v_error_text       VARCHAR(500);
    DECLARE v_count_student    INT DEFAULT 0;
    DECLARE v_count_expected   INT DEFAULT 0;
    DECLARE v_need_order_check BOOLEAN DEFAULT FALSE;

    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
            v_sqlstate   = RETURNED_SQLSTATE,
            v_error_text = MESSAGE_TEXT;
        SET v_is_valid  = FALSE;
        SET v_error_msg = clean_error_message(v_error_text);
    END;

    UPDATE tap.counters SET test_num = 0 WHERE id = 1;

    SET v_clean_query = TRIM(REGEXP_REPLACE(TRIM(p_query), ';\\s*$', ''));

    -- CHECK 1
    IF LENGTH(v_clean_query) = 0 THEN
        SET v_is_valid = FALSE; SET v_error_msg = 'Query cannot be empty';
    END IF;

    -- CHECK 2
    IF v_is_valid = TRUE AND has_forbidden_keywords(v_clean_query) = TRUE THEN
        SET v_is_valid = FALSE; SET v_error_msg = 'Forbidden keywords: INSERT, UPDATE, DELETE, etc';
    END IF;

    -- CHECK 3
    IF v_is_valid = TRUE AND validate_select_syntax(v_clean_query) = FALSE THEN
        SET v_is_valid = FALSE; SET v_error_msg = 'Query does not match allowed SELECT patterns';
    END IF;

    -- CHECK 4
    IF v_is_valid = TRUE AND has_select_star(v_clean_query) = TRUE THEN
        SET v_is_valid = FALSE; SET v_error_msg = 'SELECT * is not allowed - specify columns';
    END IF;

    -- CHECK 5
    IF v_is_valid = TRUE AND validate_supported_clauses(v_clean_query) = FALSE THEN
        SET v_is_valid = FALSE; SET v_error_msg = 'Unsupported clauses: GROUP BY, JOIN, UNION, etc';
    END IF;

    -- CHECK 6: Eksekusi query mahasiswa
    IF v_is_valid = TRUE THEN
        BEGIN
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                GET DIAGNOSTICS CONDITION 1
                    v_sqlstate   = RETURNED_SQLSTATE,
                    v_error_text = MESSAGE_TEXT;
                SET v_is_valid  = FALSE;
                SET v_error_msg = clean_error_message(v_error_text);
            END;

            DROP TABLE IF EXISTS _tap_student_result;
            SET @_tap_sql = CONCAT('CREATE TABLE _tap_student_result AS ', v_clean_query);
            PREPARE _tap_stmt FROM @_tap_sql;
            EXECUTE _tap_stmt;
            DEALLOCATE PREPARE _tap_stmt;
        END;
    END IF;

    -- CHECK 7: Pencocokan dengan kunci jawaban
    IF v_is_valid = TRUE
        AND p_expected_query IS NOT NULL
        AND TRIM(p_expected_query) != ''
    THEN
        -- --------------------------------------------------------
        -- CHECK 7a: Structural check DISTINCT & ORDER BY
        -- Dilakukan di LUAR blok BEGIN...END ber-EXIT HANDLER agar
        -- v_is_valid = FALSE langsung efektif dan tidak di-override
        -- oleh handler. Pesan memakai format "Result mismatch: ..."
        -- --------------------------------------------------------
        SET v_clean_expected = TRIM(REGEXP_REPLACE(TRIM(p_expected_query), ';\\s*$', ''));

        -- Kunci pakai DISTINCT, mahasiswa tidak
        IF v_is_valid = TRUE
           AND expected_has_distinct(v_clean_expected) = TRUE
           AND student_has_distinct(v_clean_query)     = FALSE
        THEN
            SET v_is_valid  = FALSE;
            SET v_error_msg = 'Result mismatch: your query result does not match the expected answer';
        END IF;

        -- Mahasiswa pakai DISTINCT, kunci tidak
        IF v_is_valid = TRUE
           AND expected_has_distinct(v_clean_expected) = FALSE
           AND student_has_distinct(v_clean_query)     = TRUE
        THEN
            SET v_is_valid  = FALSE;
            SET v_error_msg = 'Result mismatch: your query result does not match the expected answer';
        END IF;

        -- Kunci pakai ORDER BY, mahasiswa tidak
        IF v_is_valid = TRUE
           AND expected_has_order_by(v_clean_expected) = TRUE
           AND student_has_order_by(v_clean_query)     = FALSE
        THEN
            SET v_is_valid  = FALSE;
            SET v_error_msg = 'Result mismatch: your query result does not match the expected answer';
        END IF;

        -- Mahasiswa pakai ORDER BY, kunci tidak
        IF v_is_valid = TRUE
           AND expected_has_order_by(v_clean_expected) = FALSE
           AND student_has_order_by(v_clean_query)     = TRUE
        THEN
            SET v_is_valid  = FALSE;
            SET v_error_msg = 'Result mismatch: your query result does not match the expected answer';
        END IF;

        -- --------------------------------------------------------
        -- Lanjut eksekusi dan bandingkan data hanya jika lolos 7a
        -- --------------------------------------------------------
        IF v_is_valid = TRUE THEN
        BEGIN
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                GET DIAGNOSTICS CONDITION 1
                    v_sqlstate   = RETURNED_SQLSTATE,
                    v_error_text = MESSAGE_TEXT;
                DROP TABLE IF EXISTS _tap_expected_result;
                SET v_is_valid  = FALSE;
                SET v_error_msg = CONCAT('Expected query error: ', clean_error_message(v_error_text));
            END;

                -- Tentukan mode hash berdasarkan kunci jawaban
                SET v_need_order_check = expected_has_order_by(v_clean_expected);

                DROP TABLE IF EXISTS _tap_expected_result;
                SET @_tap_sql = CONCAT('CREATE TABLE _tap_expected_result AS ', v_clean_expected);
                PREPARE _tap_stmt FROM @_tap_sql;
                EXECUTE _tap_stmt;
                DEALLOCATE PREPARE _tap_stmt;

                -- CHECK 7b: Bandingkan jumlah baris
                SELECT COUNT(*) INTO v_count_student  FROM _tap_student_result;
                SELECT COUNT(*) INTO v_count_expected FROM _tap_expected_result;

                IF v_count_student != v_count_expected THEN
                    SET v_is_valid  = FALSE;
                    SET v_error_msg = 'Result mismatch: your query result does not match the expected answer';
                END IF;

                -- CHECK 7c: Bandingkan isi via hash
                IF v_is_valid = TRUE THEN

                    -- Bangun ekspresi kolom untuk student result
                    SELECT GROUP_CONCAT(
                        CONCAT('IFNULL(CAST(`', COLUMN_NAME, '` AS CHAR),''NULL'')')
                        ORDER BY ORDINAL_POSITION
                        SEPARATOR ','','','
                    )
                    INTO @_tap_col_expr
                    FROM information_schema.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME   = '_tap_student_result';

                    IF v_need_order_check = TRUE THEN
                        -- [FIX] Order-sensitive: pakai ROW_NUMBER agar urutan
                        -- baris dari query mahasiswa ikut dibandingkan.
                        SET @_tap_sql = CONCAT(
                            'SELECT MD5(GROUP_CONCAT(CONCAT(',
                            @_tap_col_expr,
                            ') ORDER BY _rn SEPARATOR ''||'')) ',
                            'INTO @_tap_student_hash FROM (',
                            '  SELECT *, ROW_NUMBER() OVER () AS _rn',
                            '  FROM _tap_student_result',
                            ') _s'
                        );
                    ELSE
                        -- Set-based: urutan fisik diabaikan
                        SET @_tap_sql = CONCAT(
                            'SELECT MD5(GROUP_CONCAT(CONCAT(',
                            @_tap_col_expr,
                            ') ORDER BY CONCAT(', @_tap_col_expr, ') SEPARATOR ''||'')) ',
                            'INTO @_tap_student_hash FROM _tap_student_result'
                        );
                    END IF;
                    PREPARE _tap_stmt FROM @_tap_sql;
                    EXECUTE _tap_stmt;
                    DEALLOCATE PREPARE _tap_stmt;

                    -- Bangun ekspresi kolom untuk expected result
                    SELECT GROUP_CONCAT(
                        CONCAT('IFNULL(CAST(`', COLUMN_NAME, '` AS CHAR),''NULL'')')
                        ORDER BY ORDINAL_POSITION
                        SEPARATOR ','','','
                    )
                    INTO @_tap_col_expr
                    FROM information_schema.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME   = '_tap_expected_result';

                    IF v_need_order_check = TRUE THEN
                        SET @_tap_sql = CONCAT(
                            'SELECT MD5(GROUP_CONCAT(CONCAT(',
                            @_tap_col_expr,
                            ') ORDER BY _rn SEPARATOR ''||'')) ',
                            'INTO @_tap_expected_hash FROM (',
                            '  SELECT *, ROW_NUMBER() OVER () AS _rn',
                            '  FROM _tap_expected_result',
                            ') _e'
                        );
                    ELSE
                        SET @_tap_sql = CONCAT(
                            'SELECT MD5(GROUP_CONCAT(CONCAT(',
                            @_tap_col_expr,
                            ') ORDER BY CONCAT(', @_tap_col_expr, ') SEPARATOR ''||'')) ',
                            'INTO @_tap_expected_hash FROM _tap_expected_result'
                        );
                    END IF;
                    PREPARE _tap_stmt FROM @_tap_sql;
                    EXECUTE _tap_stmt;
                    DEALLOCATE PREPARE _tap_stmt;

                    IF IFNULL(@_tap_student_hash, '') != IFNULL(@_tap_expected_hash, '') THEN
                        SET v_is_valid  = FALSE;
                        SET v_error_msg = 'Result mismatch: your query result does not match the expected answer';
                    END IF;
                END IF;

                DROP TABLE IF EXISTS _tap_expected_result;

        END; -- END blok BEGIN...END eksekusi expected
        END IF; -- END IF v_is_valid setelah 7a
    END IF; -- END IF CHECK 7

    DROP TABLE IF EXISTS _tap_student_result;

    -- Output TAP
    IF v_is_valid = TRUE THEN
        SET v_test_result = tap.ok(TRUE,  'Query validation passed');
    ELSE
        SET v_test_result = tap.ok(FALSE, v_error_msg);
    END IF;

    SELECT v_test_result AS result;
END //

DELIMITER ;