use inventario;


/* Actualizar la Cantidad de Materiales después de una Transacción
 Este trigger actualiza automáticamente la cantidad de materiales 
 en la tabla MATERIAL después de una transacción (entrante o saliente). */


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




