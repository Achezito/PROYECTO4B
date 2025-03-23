use inventario;

-- Crear roles


INSERT INTO ROLE (`name`) VALUES ('Admin'), ('Usuario');

-- Crear usuario de prueba (Contraseña: 123456)
INSERT INTO USER (`name`, password_hash, email, id_role) VALUES ('Admin', SHA1('123456'), 'admin@example.com', 1);


SELECT 
    u.id_user,
    u.name,
    u.password_hash,
    u.email,
    r.name as role_name
    FROM user as u
    INNER JOIN `role` as r on r.id_role = u.id_role
    WHERE u.name = 'Admin';


----------------------- Category -----------------------
INSERT INTO CATEGORY (id_category, `name`, `description`) VALUES 
(1,'Hardware', 'Componentes físicos de computadoras y dispositivos electrónicos.'), 
(2,'Materiales Fisicos', 'Dispositivos y componentes para el almacenamiento de datos como discos duros y SSDs.'), 
(3,'Componentes', 'Unidades de procesamiento como CPUs y GPUs.');


----------------------- Material Type -----------------------
INSERT INTO MATERIAL_TYPE (id_type, `name`) VALUES
(1,'Procesador'),
(2,'Memoria RAM'),
(3,'Disco Duro'),
(4,'Unidad De Procesamiento Grafico (GPU)'),
(5,'Pantalla'),
(6,'Teclado'),
(7,'Touchpad'),
(8,'Camara'),
(9,'Chassis'),
(10,'Tarjeta Madre'),
(11,'Unidad De Ventilacion'),
(12,'Puerto'),
(13,'Tarjeta De Sonido'),
(14,'Altavoces');


----------------------- Supplier -----------------------
INSERT INTO SUPPLIER (id_supplier, name, contact_info, address) VALUES
(1, 'TechSource Inc.', 'techsource@email.com', '500 Tech St, San Francisco'),
(2,'ComponentPro', 'componentpro@email.com', '350 Silicon Ave, Austin'),
(3, 'EnergyMax Ltd.', 'energymax@email.com', '700 Energy Blvd, Houston');


----------------------- Supply -----------------------
INSERT INTO SUPPLY (id_supply, nombre, descripcion, id_supplier) VALUES
(1, 'procesador', 'procesador Ryzen 7 5800X', 1),
(2, 'Pantalla', '4k', 2),
(3, 'Battery', '5000mAh lithium battery', 3);


----------------------- STATUS -----------------------
INSERT INTO STATUS (id_status, description) VALUES
(1, 'Pendiente'),
(2, 'Entregado');


----------------------- ORDERS -----------------------
INSERT INTO ORDERS (order_date, id_status, id_supply, quantity) VALUES
('2025-03-18', 1, 1, 50),
('2025-03-18', 1, 2, 50);


----------------------- Material Hardware -----------------------
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, speed, cores, threads, cache_memory, power_consumption, id_type) VALUES  -- insert de procesadores
(1, 'Ryzen 7 5800X', 'AMD', 3.8, 8, 16, '32MB', 105.00, 1);
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, capacity, tipo, speed, id_type) VALUES   -- insert de memoria ram
(2, 'Vengeance LPX', 'Corsair', 16.00, 'DDR4', 3200.00, 2);
INSERT INTO MATERIAL_HARDWARE (id_material, model, tipo, capacity, read_speed, write_speed, id_type) VALUES    -- insert de discos duros
(3, 'WD Blue', 'HDD', 1000.00, 150.00, 140.00, 3);
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, cores, power_consumption, vram, frecuency, id_type) VALUES   -- insert de gpu's
(4, 'RTX 3080', 'NVIDIA', 8704, 320.00, 10.00, 1710.00, 4);


----------------------- Material Physical -----------------------
INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, size, design, material_type, sensitivity, connectivity) VALUES  -- insert de pantalla
(1, '3840x2160', '15.6 inches', 'Slim Bezel', 'OLED', 'High', 'HDMI, DisplayPort');


----------------------- RECEIVED_MATERIAL -----------------------
INSERT INTO RECEIVED_MATERIAL (id_material, name, description, quantity, serial_number, date_received, id_supply, id_type, id_category, rotation, volume) VALUES
(1, 'Ryzen 7 5800X', 'procesador', 50, 'DP-202403001', '2025-03-10', 1, 1, 1, 'FIFO', 3.5),
(2, 'Pantalla', 'pantalla 4k', 70, 'LB-202403002', '2025-03-12', 2, 5, 2, 'LIFO', 1.8);


----------------------- MATERIAL_LINK -----------------------
INSERT INTO MATERIAL_LINK (id_material, id_material_hardware, id_material_component, id_material_physical) VALUES
(1, 1, null, null),
(2, null, null, 1);


----------------------- WAREHOUSE -----------------------
INSERT INTO WAREHOUSE (id_warehouse, `name`, `location`, `capacity`) VALUES
(1, 'Almacén Principal', 'Ciudad Industrial, Sector B', 5000);


----------------------- SUB SUB_WAREHOUSE -----------------------
INSERT INTO SUB_WAREHOUSE (id_sub_warehouse,`location`, `capacity`, `id_warehouse`, `id_category`) VALUES
(1, 'Sector B1', 1500, 1, 2),
(2, 'Sector B2', 2000, 1, 1),
(3, 'Sector B3', 1500, 1, 2);




----------------------- SUB_WAREHOUSE_MATERIAL -----------------------
INSERT INTO SUB_WAREHOUSE_MATERIAL (id_sub_warehouse, id_material, quantity) VALUES
(1, 2, 50),
(2, 1, 50);



----------------------- TRANSACTIONS -----------------------
INSERT INTO TRANSACTIONS (id_material, id_sub_warehouse, type, quantity, transaction_date) VALUES
(2, 3, 'inbound', 40, '2025-03-20'),
(1, 1, 'inbound', 30, '2025-03-20');





























