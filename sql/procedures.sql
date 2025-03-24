use inventario;


/* anterior procedimiento para registrar material 
DELIMITER //
CREATE PROCEDURE RegisterMaterial(
    IN p_name VARCHAR(255),
    IN p_description TEXT,
    IN p_quantity INT,
    IN p_date_received DATE,
    IN p_id_supplier INT,
    IN p_id_category INT, -- Categoría del material
    IN p_rotation VARCHAR(50),
    IN p_volume DECIMAL(5,2)
)
BEGIN
    DECLARE v_id_material INT;
    DECLARE v_id_sub_warehouse INT;

    -- Insertar el material en la tabla MATERIAL
    INSERT INTO MATERIAL (`name`, `description`, quantity, date_received, id_supplier, id_category, rotation, volume)
    VALUES (p_name, p_description, p_quantity, p_date_received, p_id_supplier, p_id_category, p_rotation, p_volume);

    -- Obtener el ID del material recién insertado
    SET v_id_material = LAST_INSERT_ID();

    -- Si el material tiene una categoría, asignarlo al subalmacén correspondiente
    IF p_id_category IS NOT NULL THEN
        -- Obtener el subalmacén asociado a la categoría
        SELECT id_sub_warehouse INTO v_id_sub_warehouse
        FROM SUB_WAREHOUSE
        WHERE id_category = p_id_category
        LIMIT 1;

        -- Si existe un subalmacén para la categoría, asignar el material
        IF v_id_sub_warehouse IS NOT NULL THEN
            INSERT INTO SUB_WAREHOUSE_MATERIAL (id_sub_warehouse, id_material, quantity)
            VALUES (v_id_sub_warehouse, v_id_material, p_quantity);
        END IF;
    END IF;
END //
DELIMITER ;
*/

--nuevo procedimiento para almacenar materiales
DELIMITER // 
CREATE PROCEDURE RegisterMaterial(
    IN p_description VARCHAR(255),
    IN p_serial_number VARCHAR(255),
    IN p_id_supply INT,
    IN p_id_type INT,
    IN p_id_category INT,
    IN p_id_rotation INT,
    IN p_volume DECIMAL(5,2)
)
BEGIN
    DECLARE v_id_material INT;
    DECLARE v_id_sub_warehouse INT;

    -- Insertar el material en la tabla RECEIVED_MATERIAL
    INSERT INTO RECEIVED_MATERIAL (`description`, serial_number, id_supply, id_type, id_category, id_rotation, volume)
    VALUES (p_description, p_serial_number, p_id_supply, p_id_type, p_id_category, p_id_rotation, p_volume);

    -- Obtener el ID del material recién insertado
    SET v_id_material = LAST_INSERT_ID();

    -- Si el material tiene una categoría, asignarlo al subalmacén correspondiente
    IF p_id_category IS NOT NULL THEN
        -- Obtener el subalmacén asociado a la categoría
        SELECT id_sub_warehouse INTO v_id_sub_warehouse
        FROM SUB_WAREHOUSE
        WHERE id_category = p_id_category
        LIMIT 1;

        -- Si existe un subalmacén para la categoría, asignar el material
        IF v_id_sub_warehouse IS NOT NULL THEN
            INSERT INTO SUB_WAREHOUSE_MATERIAL (id_sub_warehouse, id_material, quantity)
            VALUES (v_id_sub_warehouse, v_id_material, 1); -- Se registra con cantidad 1 por ser recibido
        END IF;
    END IF;
END //

DELIMITER ;





-- Registrar un material sin categoría (se quedará en el almacén principal)
CALL RegisterMaterial(
    'Procesador de 8 núcleos y 16 hilos', --description
    "1234", -- serial number
    1, --suply
    1, --type
    null, -- category
    1, -- rotation
    0.1 --volume
);


CALL RegisterMaterial(
    'Procesador de 8 núcleos y 16 hilos',
    "1234",
    1,
    1,
    1,
    1,
    0.1
);

CALL RegisterMaterial(
    'Procesador de 8 núcleos y 16 hilos',
    "1234",
    1,
    1,
    null,
    1,
    0.1
);



DELIMITER //

CREATE PROCEDURE UpdateOrderStatus(
    IN p_id_order INT
)
BEGIN
    DECLARE order_quantity INT;
    DECLARE transaction_quantity INT;

    -- Obtener la cantidad de la orden
    SELECT quantity INTO order_quantity
    FROM ORDERS
    WHERE id_order = p_id_order;

    -- Obtener la cantidad total de transacciones outbound para la orden
    SELECT COALESCE(SUM(quantity), 0) INTO transaction_quantity
    FROM TRANSACTIONS
    WHERE id_material = (
        SELECT id_material
        FROM ORDERS
        WHERE id_order = p_id_order
    ) AND type = 'outbound';

    -- Actualizar el estado de la orden
    IF transaction_quantity >= order_quantity THEN
        UPDATE ORDERS
        SET id_status = (
            SELECT id_status
            FROM STATUS
            WHERE description = 'completed'
        )
        WHERE id_order = p_id_order;
    ELSE
        UPDATE ORDERS
        SET id_status = (
            SELECT id_status
            FROM STATUS
            WHERE description = 'pending'
        )
        WHERE id_order = p_id_order;
    END IF;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE GenerateInventoryReport(
    IN p_id_sub_warehouse INT
)
BEGIN
    SELECT
        M.id_material,
        M.name,
        M.quantity,
        SW.location
    FROM MATERIAL M
    JOIN SUB_WAREHOUSE_MATERIAL SWM ON M.id_material = SWM.id_material
    JOIN SUB_WAREHOUSE SW ON SWM.id_sub_warehouse = SW.id_sub_warehouse
    WHERE SW.id_sub_warehouse = p_id_sub_warehouse;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE RegisterTransaction(
    IN p_type ENUM('inbound', 'outbound'),
    IN p_id_material INT,
    IN p_quantity INT,
    IN p_transaction_date DATE,
    IN p_id_sub_warehouse INT
)
BEGIN
    DECLARE current_quantity INT;

    -- Validar la cantidad para transacciones salientes
    IF p_type = 'outbound' THEN
        SELECT quantity INTO current_quantity
        FROM MATERIAL
        WHERE id_material = p_id_material;

        IF current_quantity < p_quantity THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No hay suficiente stock para esta transacción.';
        END IF;
    END IF;

    -- Insertar la transacción
    INSERT INTO TRANSACTIONS (type, transaction_date, id_material, quantity, id_sub_warehouse)
    VALUES (p_type, p_transaction_date, p_id_material, p_quantity, p_id_sub_warehouse);
END //

DELIMITER ;