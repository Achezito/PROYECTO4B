use inventario;


/* Actualizar la Cantidad de Materiales después de una Transacción
 Este trigger actualiza automáticamente la cantidad de materiales 
 en la tabla MATERIAL después de una transacción (entrante o saliente). */

DELIMITER //
CREATE TRIGGER modificar_estatus_Orders
AFTER UPDATE ON SUPPLY
FOR EACH ROW
BEGIN
    DECLARE entregados INT;
    SELECT COUNT(*)
    INTO entregados
    FROM SUPPLY
    WHERE id_order = NEW.id_order
    AND id_status != 2;

    IF entregados = 0 THEN
        UPDATE ORDERS
        SET id_status = 2
        WHERE id_order = NEW.id_order;
    END IF;
END //
DELIMITER ;


DELIMITER //

CREATE TRIGGER modificar_estatus_Supply
AFTER INSERT ON RECEIVED_MATERIAL
FOR EACH ROW
BEGIN
    DECLARE supply_exists INT;
    SELECT COUNT(*)
    INTO supply_exists
    FROM SUPPLY
    WHERE id_supply = NEW.id_supply;
    IF supply_exists > 0 THEN
        UPDATE SUPPLY
        SET id_status = 2
        WHERE id_supply = NEW.id_supply;
    END IF;
END //

DELIMITER ;



DELIMITER //

CREATE TRIGGER aumentar_Supply_Quantity
AFTER INSERT ON SUPPLY
FOR EACH ROW
BEGIN
    UPDATE ORDERS
    SET supply_quantity = supply_quantity + 1
    WHERE id_order = NEW.id_order;
END 

//DELIMITER ;


CREATE TRIGGER disminuir_Supply_Quantity
AFTER DELETE ON SUPPLY
FOR EACH ROW
BEGIN
    UPDATE ORDERS
    SET supply_quantity = supply_quantity - 1
    WHERE id_order = OLD.id_order;
END 

DELIMITER //

CREATE TRIGGER before_insert_received_material
BEFORE INSERT ON RECEIVED_MATERIAL
FOR EACH ROW
BEGIN
    DECLARE material_id INT;
 
    SELECT
        CASE
            WHEN id_material_hardware IS NOT NULL THEN id_material_hardware
            WHEN id_material_component IS NOT NULL THEN id_material_component
            WHEN id_material_physical IS NOT NULL THEN id_material_physical
            ELSE NULL
        END
    INTO material_id
    FROM MATERIAL_LINK
    WHERE id_supply = NEW.id_supply;
 
    IF material_id IS NOT NULL THEN 
        IF material_id = NEW.id_material_hardware THEN
            SET NEW.id_material_hardware = material_id;
        ELSEIF material_id = NEW.id_material_component THEN
            SET NEW.id_material_component = material_id;
        ELSE
            SET NEW.id_material_physical = material_id;
        END IF;
    ELSE 
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: No material found in MATERIAL_LINK for the specified supply.';
    END IF;

END //

DELIMITER ;




DELIMITER //
CREATE TRIGGER update_material_quantity
AFTER INSERT ON TRANSACTIONS
FOR EACH ROW 
BEGIN 
    
     DECLARE current_quantity INT;

  
    SELECT quantity INTO current_quantity
    FROM MATERIAL
    WHERE id_material = NEW.id_material;

    IF NEW.type = 'outbound' AND NEW.quantity > current_quantity THEN 
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'No hay suficiente stock para esta transaccion' ;
    END IF; 

    IF NEW.type = 'inbound' THEN 
        -- Aumentar la cantidad para transacciones entrantes
        UPDATE MATERIAL
        SET quantity = quantity + NEW.quantity
        WHERE id_material = NEW.id_material;
    ELSEIF NEW.type = 'outbound' THEN
        -- Disminuir la cantidad para transacciones salientes
        UPDATE MATERIAL
        SET quantity = quantity -  NEW.quantity
        WHERE id_material = NEW.id_material;
    END IF;
END //
DELIMITER;





DROP TRIGGER IF EXISTS after_received_material_insert;
DELIMITER $$

CREATE TRIGGER after_received_material_insert
AFTER INSERT ON received_material
FOR EACH ROW
BEGIN
    -- Declarar variables para el subalmacén y la cantidad del suministro
    DECLARE sub_almacen_id INT;
    DECLARE supply_quantity INT;

    -- Obtener el ID del subalmacén basado en la categoría del material recibido
    SELECT id_sub_warehouse
    INTO sub_almacen_id
    FROM sub_warehouse
    WHERE id_category = NEW.id_category
    LIMIT 1;

    -- Obtener la cantidad del suministro desde la tabla SUPPLY
    SELECT quantity
    INTO supply_quantity
    FROM SUPPLY
    WHERE id_supply = NEW.id_supply;

    -- Si se encuentra un subalmacén, insertar el material en la tabla sub_warehouse_material
    IF sub_almacen_id IS NOT NULL THEN
        INSERT INTO sub_warehouse_material (id_sub_warehouse, id_material, quantity)
        VALUES (sub_almacen_id, NEW.id_material, supply_quantity);
    END IF;
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER after_transaction_outbound
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
    -- Verificar si la transacción es de tipo "salida"
    IF NEW.type = 'outbound' THEN
        -- Restar la cantidad de materiales del subalmacén
        UPDATE sub_warehouse_material
        SET quantity = quantity - NEW.quantity
        WHERE id_sub_warehouse = NEW.id_sub_warehouse
          AND id_material = NEW.id_material;

        -- Verificar que la cantidad no sea negativa
        IF (SELECT quantity FROM sub_warehouse_material
            WHERE id_sub_warehouse = NEW.id_sub_warehouse
              AND id_material = NEW.id_material) < 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La cantidad de materiales no puede ser negativa';
        END IF;
    END IF;
END$$

DELIMITER ;



DELIMITER $$

CREATE TRIGGER after_transaction_inbound
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
    -- Verificar si la transacción es de tipo "entrada"
    IF NEW.type = 'inbound' THEN
        -- Verificar si ya existe el material en el subalmacén
        IF EXISTS (
            SELECT 1
            FROM sub_warehouse_material
            WHERE id_sub_warehouse = NEW.id_sub_warehouse
              AND id_material = NEW.id_material
        ) THEN
            -- Si existe, sumar la cantidad
            UPDATE sub_warehouse_material
            SET quantity = quantity + NEW.quantity
            WHERE id_sub_warehouse = NEW.id_sub_warehouse
              AND id_material = NEW.id_material;
        ELSE
            -- Si no existe, insertar un nuevo registro
            INSERT INTO sub_warehouse_material (id_sub_warehouse, id_material, quantity)
            VALUES (NEW.id_sub_warehouse, NEW.id_material, NEW.quantity);
        END IF;
    END IF;
END$$

DELIMITER ;



DELIMITER $$

CREATE TRIGGER after_supply_insert
AFTER INSERT ON SUPPLY
FOR EACH ROW
BEGIN
    -- Ejemplo: Relacionar automáticamente con un material de hardware
    INSERT INTO MATERIAL_LINK (id_supply, id_material_hardware, id_material_component, id_material_physical)
    VALUES (NEW.id_supply, 1, NULL, NULL);
END$$

DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS after_supply_quantity_update;

CREATE TRIGGER after_supply_quantity_update
AFTER UPDATE ON SUPPLY
FOR EACH ROW
BEGIN
    -- Verificar si la cantidad del suministro es 0
    IF NEW.quantity = 0 THEN
        -- Actualizar el estado del suministro a "agotado" (por ejemplo, id_status = 3)
        UPDATE SUPPLY
        SET id_status = 2 -- Cambia 3 por el ID correspondiente al estado "agotado"
        WHERE id_supply = NEW.id_supply;
    END IF;
END$$

DELIMITER ;