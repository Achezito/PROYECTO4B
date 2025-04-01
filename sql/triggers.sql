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


