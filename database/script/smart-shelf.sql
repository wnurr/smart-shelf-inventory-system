USE smart_shelf;

-- =========================================================
-- BASIC TABLE CHECK
-- =========================================================

SELECT * FROM users;
SELECT * FROM roles;
SELECT * FROM permissions;
SELECT * FROM role_permissions;

SELECT * FROM locations;
SELECT * FROM items;
SELECT * FROM sensors;

SELECT * FROM inventory_readings;
SELECT * FROM histories;
SELECT * FROM calibrations;

SHOW TABLES;


-- =========================================================
-- ITEM + LOCATION + SENSOR + LATEST READING
-- =========================================================

SELECT
    i.id AS item_id,
    i.item_name,
    i.item_code,
    i.min_stock,

    CONCAT(
        l.aisle, '-',
        l.bay, '-',
        l.shelf, '-',
        l.bin
    ) AS location,

    s.sensor_code,

    r.weight,
    r.quantity,
    r.stock_status,
    r.recorded_at

FROM items i

LEFT JOIN locations l
    ON l.id = i.location_id

LEFT JOIN sensors s
    ON s.item_id = i.id

LEFT JOIN inventory_readings r
    ON r.id = (
        SELECT r2.id
        FROM inventory_readings r2
        WHERE r2.item_id = i.id
        ORDER BY r2.recorded_at DESC, r2.id DESC
        LIMIT 1
    )

ORDER BY i.item_name ASC;