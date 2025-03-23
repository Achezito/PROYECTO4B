-- Insertando en CATEGORY

use inventario;
INSERT INTO CATEGORY (name, description) VALUES ('Electrónicos', 'Materiales electrónicos y hardware');

-- Insertando en MATERIAL_TYPE
INSERT INTO MATERIAL_TYPE (name) VALUES ('Procesador');

-- Insertando en SUPPLIER
INSERT INTO SUPPLIER (name, contact_info, address) VALUES ('Proveedor A', 'proveedor@example.com', 'Calle Falsa 123');

-- Insertando en SUPPLY
INSERT INTO SUPPLY (nombre, descripcion, id_supplier) VALUES ('Suministro A', 'Descripción del suministro', 1);

-- Insertando en RECEIVED_MATERIAL
INSERT INTO RECEIVED_MATERIAL (name, description, quantity, batch_number, serial_number, date_received, id_supply, id_type, id_category, rotation, volume)
VALUES ('Tarjeta Gráfica', 'NVIDIA RTX 3080', 10, 12345, 'SN123456789', '2025-03-18', 1, 1, 1, 'FIFO', 1.5);

-- Insertando en MATERIAL_HARDWARE
INSERT INTO MATERIAL_HARDWARE (model, brand, power_consumption, frecuency, vram, speed, cores, threads, cache_memory, tipo, capacity, hardware_type, read_speed, write_speed)
VALUES ('RTX 3080', 'NVIDIA', 320.00, 1.70, 10.00, 1.50, 8704, 16, 'L2 5MB', 'GPU', 0.00, 'GDDR6X', 7600.00, 9500.00);

-- Insertando en MATERIAL_PHYSICAL
INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, size, design, material_type, sensitivity, connectivity)
VALUES (1, '1920x1080', '27"', 'Ergonómico', 'Plástico', 'Alta', 'USB-C');

-- Insertando en MATERIAL_COMPONENT
INSERT INTO MATERIAL_COMPONENT (id_material, chipset, form_factor, socket_type, RAM_slots, max_RAM, expansion_slots, capacity, voltage, quantity, audio_channels, component_type)
VALUES (1, 'Z690', 'ATX', 'LGA1700', 4, 128.00, 3, 1.00, 12.00, 1, 7, 'Motherboard');

-- Insertando en MATERIAL_LINK
INSERT INTO MATERIAL_LINK (id_material, id_material_hardware, id_material_component, id_material_physical)
VALUES (1, 1, NULL, NULL);

-- Insertando en WAREHOUSE
INSERT INTO WAREHOUSE (name, location, capacity) VALUES ('Almacén Central', 'Ciudad Industrial', 500);

-- Insertando en SUB_WAREHOUSE
INSERT INTO SUB_WAREHOUSE (location, capacity, id_warehouse, id_category)
VALUES ('Sector E', 200, 1, 1);

-- Insertando en SUB_WAREHOUSE_MATERIAL
INSERT INTO SUB_WAREHOUSE_MATERIAL (id_sub_warehouse, id_material, quantity)
VALUES (1, 1, 5);

-- Insertando en TRANSACTIONS
INSERT INTO TRANSACTIONS (id_material, id_sub_warehouse, type, quantity, transaction_date)
VALUES (1, 1, 'inbound', 5, '2025-03-18');

-- Insertando en STATUS
INSERT INTO STATUS (description) VALUES ('Pendiente');

-- Insertando en ORDERS
INSERT INTO ORDERS (order_date, id_status, id_supply, quantity) VALUES
('2025-03-18', 1, 1, 10);

-- Insertando en CONDITION
INSERT INTO `CONDITION` (`description`) VALUES ('Nuevo');

-- Insertando en EQUIPMENT
INSERT INTO EQUIPMENT (name, type, id_condition) VALUES
('Multímetro', 'Instrumento de medición', 1);

-- Insertando en MAINTENANCE
INSERT INTO MAINTENANCE (`description`, maintenance_date, id_equipment)
VALUES('Revisión general', '2025-03-18', 2);


INSERT INTO SUPPLIER (name, contact_info, address) VALUES
('TechSupplier Inc.', 'contact@techsupplier.com', '123 Tech Street, Silicon Valley'),
('LaptopWorld', 'sales@laptopworld.com', '456 Laptop Lane, New York');


INSERT INTO SUPPLY (nombre, descripcion, id_supplier) VALUES
('Laptop HP EliteBook', 'Laptop empresarial de alto rendimiento', 1),
('Laptop Dell XPS 13', 'Laptop ultrabook con pantalla InfinityEdge', 2),
('Laptop Lenovo ThinkPad', 'Laptop robusta para profesionales', 1);



INSERT INTO CATEGORY (name, description) VALUES
('Laptops', 'Computadoras portátiles de alto rendimiento'),
('Accesorios', 'Accesorios para laptops y computadoras');

INSERT INTO MATERIAL_TYPE (name) VALUES
('Laptop'),
('Teclado'),
('Mouse');

INSERT INTO RECEIVED_MATERIAL (name, description, quantity, batch_number, serial_number, date_received, id_supply, id_type, id_category) VALUES
('HP EliteBook 840 G8', 'Laptop empresarial con Intel Core i7', 10, 1001, 'SN123456', '2023-10-01', 1, 1, 1),
('Dell XPS 13 9310', 'Laptop ultrabook con pantalla 4K', 5, 1002, 'SN654321', '2023-10-02', 2, 1, 1),
('Lenovo ThinkPad X1 Carbon', 'Laptop ligera y potente', 8, 1003, 'SN789012', '2023-10-03', 3, 1, 1);

INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, power_consumption, frecuency, vram, speed, cores, threads, cache_memory, tipo, capacity, hardware_type, read_speed, write_speed) VALUES
(1, 'EliteBook 840 G8', 'HP', 45.0, 2.8, 16.0, 4.2, 4, 8, '12MB', 'Laptop', 512.0, 'SSD', 3500.0, 3000.0),
(2, 'XPS 13 9310', 'Dell', 40.0, 2.4, 8.0, 3.8, 4, 8, '8MB', 'Laptop', 1024.0, 'SSD', 3200.0, 2800.0),
(3, 'ThinkPad X1 Carbon', 'Lenovo', 50.0, 2.6, 16.0, 4.0, 6, 12, '18MB', 'Laptop', 512.0, 'SSD', 3400.0, 2900.0);
INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, size, design, material_type, sensitivity, connectivity) VALUES
(1, '1920x1080', '14 pulgadas', 'Clamshell', 'Magnesio', 'Teclado retroiluminado', 'Wi-Fi 6, Bluetooth 5.0'),
(2, '3840x2400', '13.4 pulgadas', 'Ultrabook', 'Aluminio', 'Pantalla táctil', 'Wi-Fi 6, Thunderbolt 4'),
(3, '2560x1440', '14 pulgadas', 'Clamshell', 'Fibra de carbono', 'Teclado resistente a derrames', 'Wi-Fi 6, Bluetooth 5.1');

INSERT INTO MATERIAL_COMPONENT (id_material, chipset, form_factor, socket_type, RAM_slots, max_RAM, expansion_slots, capacity, voltage, quantity, audio_channels, component_type) VALUES
(1, 'Intel Q570', 'Laptop', 'BGA', 2, 64.0, 1, 512.0, 1.2, 1, 2, 'Placa base'),
(2, 'Intel EVO', 'Laptop', 'BGA', 2, 32.0, 1, 1024.0, 1.2, 1, 2, 'Placa base'),
(3, 'Intel QM570', 'Laptop', 'BGA', 2, 64.0, 1, 512.0, 1.2, 1, 2, 'Placa base');

INSERT INTO WAREHOUSE (name, location, capacity) VALUES
('Almacén Central', 'Ciudad de México', 1000),
('Almacén Norte', 'Monterrey', 500);

INSERT INTO SUB_WAREHOUSE (location, capacity, id_warehouse, id_category) VALUES
('Subalmacén A1', 200, 1, 1),
('Subalmacén B1', 150, 2, 1);

INSERT INTO SUB_WAREHOUSE_MATERIAL (id_sub_warehouse, id_material, quantity) VALUES
(1, 1, 5),  -- 5 HP EliteBook en Subalmacén A1
(1, 2, 3),  -- 3 Dell XPS 13 en Subalmacén A1
(2, 3, 4);  -- 4 Lenovo ThinkPad en Subalmacén B1



INSERT INTO CATEGORY (`name`, `description`) VALUES 
('Físico', 'Materiales físicos como pantallas, gabinetes, etc.'),
('Componente', 'Componentes electrónicos como RAM, SSD, procesadores.'),
('Hardware', 'Dispositivos completos como laptops, servidores.');


INSERT INTO MATERIAL_TYPE (`name`) VALUES 
('Pantalla'),
('Gabinete'),
('RAM'),
('SSD'),
('Procesador'),
('Laptop'),
('Servidor');


INSERT INTO SUPPLIER (`name`, contact_info, `address`) VALUES 
('TechSupplier Inc.', 'techsupplier@example.com', 'Av. Tecnológica #123, CDMX'),
('Hardware Global', 'hardwareglobal@example.com', 'Calle Circuitos #456, Monterrey'),
('ElectroParts', 'electroparts@example.com', 'Boulevard Innovación #789, Guadalajara');
INSERT INTO RECEIVED_MATERIAL (`name`, `description`, quantity, batch_number, serial_number, date_received, id_supply, id_type, id_category, rotation, volume) 
VALUES 
('Monitor 24" 144Hz', 'Pantalla LED 24 pulgadas Full HD 144Hz', 50, 1001, 'SN12345M24', '2025-03-10', 1, 1, 1, 'Alta', 12.5),
('Gabinete ATX RGB', 'Gabinete ATX con panel de vidrio templado y RGB', 30, 1002, 'SN98765GATX', '2025-03-11', 2, 2, 1, 'Media', 20.0),
('Memoria RAM 16GB DDR4', 'Memoria DDR4 3200MHz', 100, 2001, 'SN56789RAM', '2025-03-12', 3, 3, 2, 'Alta', 1.2),
('SSD NVMe 1TB', 'Unidad de estado sólido NVMe de 1TB', 80, 3001, 'SN11111SSD', '2025-03-13', 1, 4, 2, 'Alta', 0.5),
('Procesador Ryzen 7', 'Procesador AMD Ryzen 7 5800X', 40, 4001, 'SN22222CPU', '2025-03-14', 2, 5, 2, 'Alta', 0.3),
('Laptop Gaming', 'Laptop con RTX 4060 y Core i7', 25, 5001, 'SN33333LAP', '2025-03-15', 1, 6, 3, 'Baja', 5.5),
('Servidor Dell PowerEdge', 'Servidor de alto rendimiento con Xeon y 64GB RAM', 10, 6001, 'SN44444SRV', '2025-03-16', 2, 7, 3, 'Baja', 30.0);

INSERT INTO SUB_WAREHOUSE (`location`, capacity, id_warehouse, id_category) 
VALUES 
('Almacén de Materiales Físicos', 1000, 1, (SELECT id_category FROM CATEGORY WHERE `name` = 'Físico')),
('Almacén de Componentes', 1200, 1, (SELECT id_category FROM CATEGORY WHERE `name` = 'Componente')),
('Almacén de Hardware', 1500, 1, (SELECT id_category FROM CATEGORY WHERE `name` = 'Hardware'));


INSERT INTO SUB_WAREHOUSE_MATERIAL (id_sub_warehouse, id_material, quantity) 
VALUES 
(6, 1, 50), -- Monitor en almacén físico
(6, 2, 30), -- Gabinete en almacén físico
(7, 3, 100), -- RAM en almacén de componentes
(7, 4, 80), -- SSD en almacén de componentes
(7, 5, 40), -- Procesador en almacén de componentes
(8, 6, 25), -- Laptop en almacén de hardware
(8, 7, 10); -- Servidor en almacén de hardware

INSERT INTO ORDERS (order_date, id_status, id_supply, quantity) 
VALUES 
('2025-03-05', 1, 1, 50),
('2025-03-06', 1, 2, 30),
('2025-03-07', 1, 3, 100);

SELECT 
    sw.location AS "Ubicación del Subalmacén",
    c.name AS "Categoría",
    m.name AS "Material",
    m.description AS "Descripción",
    swm.quantity AS "Cantidad Disponible"
FROM SUB_WAREHOUSE_MATERIAL swm
JOIN SUB_WAREHOUSE sw ON swm.id_sub_warehouse = sw.id_sub_warehouse
JOIN RECEIVED_MATERIAL m ON swm.id_material = m.id_material
JOIN CATEGORY c ON sw.id_category = c.id_category
WHERE sw.id_sub_warehouse = 6; -- Reemplaza ? con el ID del subalmacén



SELECT 
    o.id_order AS "ID Orden",
    o.order_date AS "Fecha de Orden",
    s.description AS "Estado",
    su.nombre AS "Proveedor",  -- Cambié 'sp' por 'su' ya que es el alias de la tabla 'SUPPLY'
    o.quantity AS "Cantidad",
    sw.location AS "Ubicación del Subalmacén"
FROM ORDERS o
JOIN STATUS s ON o.id_status = s.id_status
JOIN SUPPLY su ON o.id_supply = su.id_supply  -- Cambio el alias de 'sp' a 'su'
JOIN RECEIVED_MATERIAL rm ON o.id_supply = rm.id_supply
JOIN SUB_WAREHOUSE_MATERIAL swm ON rm.id_material = swm.id_material
JOIN SUB_WAREHOUSE sw ON swm.id_sub_warehouse = sw.id_sub_warehouse
WHERE sw.id_sub_warehouse = 6; -- Reemplaza ? con el ID del subalmacén


-- Insertar órdenes de ejemplo
INSERT INTO ORDERS (order_date, id_status, id_supply, quantity) VALUES
('2025-03-01', 1, 1, 50),
('2025-03-02', 2, 2, 30),
('2025-03-03', 3, 3, 20),
('2025-03-04', 1, 3, 100),
('2025-03-05', 2, 3, 70),
('2025-03-06', 3, 3, 10);

-- Insertar estados de ejemplo
INSERT INTO STATUS (description) VALUES
('Pendiente'),
('En Proceso'),
('Completada'),
('Cancelada'),
('En Espera');


SELECT 
    t.id_transaction AS "ID Transacción",
    t.transaction_date AS "Fecha de Transacción",
    t.type AS "Tipo de Transacción", -- inbound o outbound
    t.quantity AS "Cantidad",
    sw.location AS "Ubicación del Subalmacén",
    rm.name AS "Material",
    rm.description AS "Descripción del Material"
FROM TRANSACTIONS t
JOIN SUB_WAREHOUSE sw ON t.id_sub_warehouse = sw.id_sub_warehouse
JOIN RECEIVED_MATERIAL rm ON t.id_material = rm.id_material
WHERE sw.id_sub_warehouse = 6; -- Reemplaza ? con el ID del subalmacén


-- Insertar transacciones de ejemplo
INSERT INTO TRANSACTIONS (id_material, id_sub_warehouse, type, quantity, transaction_date) VALUES
(1, 6, 'inbound', 50, '2025-03-01'),
(2, 6, 'outbound', 30, '2025-03-02'),
(3, 7, 'inbound', 100, '2025-03-03'),
(4, 7, 'outbound', 20, '2025-03-04'),
(5, 8, 'inbound', 70, '2025-03-05'),
(6, 8, 'outbound', 10, '2025-03-06');
