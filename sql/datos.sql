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

-- ejemplo de supplier
INSERT INTO SUPPLIER (name, contact_info, address) 
VALUES ('Proveedor ABC', 'Teléfono: 555-1234, Email: contacto@proveedorabc.com', 'Calle Ficticia 123, Ciudad, País');




-- ejemplo de supply
INSERT INTO SUPPLY (nombre, descripcion, id_supplier) 
VALUES ('Suministro de Placas Base', 'Lote de placas base de última generación', 1);

-- tipos de materiales en MATERIAL_TYPE
INSERT INTO MATERIAL_TYPE (`name`) VALUES 
('Componentes Electrónicos'), 
('Placas Base'), 
('Memorias RAM'), 
('Procesadores'), 
('Discos Duros'), 
('Fuentes de Poder'), 
('Tarjetas Gráficas'), 
('Periféricos'), 
('Cables y Conectores'), 
('Chips y Circuitos Integrados');

INSERT INTO warehouse (`name`,location,capacity) VALUES 
('Almacen 1A', "calle 13", 200);

-- CAtegorias de los materiales (solo ejemplos)
INSERT INTO CATEGORY (`name`, `description`) VALUES 
('Hardware', 'Componentes físicos de computadoras y dispositivos electrónicos.'), 
('Almacenamiento', 'Dispositivos y componentes para el almacenamiento de datos como discos duros y SSDs.'), 
('Procesamiento', 'Unidades de procesamiento como CPUs y GPUs.'), 
('Memoria', 'Componentes de memoria RAM y almacenamiento temporal.'), 
('Energía', 'Fuentes de poder, baterías y reguladores de voltaje.'), 
('Conectividad', 'Cables, adaptadores y tarjetas de red.'), 
('Periféricos', 'Dispositivos de entrada y salida como teclados, ratones y monitores.'), 
('Circuitos y Componentes', 'Chips, transistores y otros elementos electrónicos esenciales.');

-- Insercion de ejemplo para procesadores
-- Supongamos que el id_supply generado es 1
INSERT INTO MATERIAL (
    name, description, quantity, batch_number, serial_number, 
    date_received, id_supply, id_type, id_category, rotation, volume
) VALUES (
    'Procesador de ejemplo',
    'procesador de laptops.',
    100,
    123456,
    'SN123ABC',
    '2025-03-17',
    1, -- id_supply
    2, -- id_type
    3, -- id_category
    'FIFO', -- Rotación
    0.75 -- Volumen
);

-- Material Hardware


-- insert de procesadores
INSERT INTO MATERIAL_HARDWARE (model, brand, speed, cores, threads, cache_memory, power_consumption, hardware_type) 
VALUES ('Ryzen 7 5800X', 'AMD', 3.8, 8, 16, '32MB', 105.00, 'processor');

-- insert de memoria ram
INSERT INTO MATERIAL_HARDWARE (model, brand, capacity, tipo, speed, hardware_type) 
VALUES ('Vengeance LPX', 'Corsair', 16.00, 'DDR4', 3200.00, 'ram');


-- insert de discos duros
INSERT INTO MATERIAL_HARDWARE (model, tipo, capacity, read_speed, write_speed, hardware_type) 
VALUES ('WD Blue', 'HDD', 1000.00, 150.00, 140.00, 'hard disc');

-- insert de gpu's
INSERT INTO MATERIAL_HARDWARE (model, brand, cores, power_consumption, vram, frecuency, hardware_type) 
VALUES ('RTX 3080', 'NVIDIA', 8704, 320.00, 10.00, 1710.00, 'gpu');

INSERT INTO SUB_WAREHOUSE (location, capacity, id_warehouse, id_category) 
VALUES 
('Zone 1 - NY', 2000, 1, 1),
('Zone 2 - LA', 3000, 2, 2),
('Zone 3 - CHI', 2500, 3, 3);

INSERT INTO SUPPLIER (name, contact_info, address) 
VALUES 
('TechSource Inc.', 'techsource@email.com', '500 Tech St, San Francisco'),
('ComponentPro', 'componentpro@email.com', '350 Silicon Ave, Austin'),
('EnergyMax Ltd.', 'energymax@email.com', '700 Energy Blvd, Houston');

INSERT INTO SUPPLY (nombre, descripcion, id_supplier) 
VALUES 
('Laptop Screen', '15-inch 4K Display', 1),
('Motherboard', 'High-performance motherboard', 2),
('Battery', '5000mAh lithium battery', 3);



INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, size, design, material_type, sensitivity, connectivity) 
VALUES 
(1, '3840x2160', '15.6 inches', 'Slim Bezel', 'OLED', 'High', 'HDMI, DisplayPort'),
(2, 'N/A', 'Standard', 'Rectangular', 'Lithium-Ion', 'N/A', 'USB-C');


INSERT INTO CATEGORY (name, description) 
VALUES 
('Electronics', 'Devices and components used in electronic products.'),
('Batteries', 'Rechargeable and non-rechargeable batteries.'),
('Computer Components', 'Essential components for computer assembly.');



INSERT INTO RECEIVED_MATERIAL (name, description, quantity, batch_number, serial_number, date_received, id_supply, id_type, id_category, rotation, volume) 
VALUES 
('Display Panel', '4K OLED Panel', 50, 101, 'DP-202403001', '2025-03-10', 1, 1, 1, 'FIFO', 3.5),
('Lithium Battery', 'High-capacity battery', 70, 102, 'LB-202403002', '2025-03-12', 3, 2, 2, 'LIFO', 1.8);



INSERT INTO TRANSACTIONS (id_material, id_sub_warehouse, type, quantity, transaction_date) 
VALUES 
(1, 1, 'inbound', 50, '2025-03-15'),
(2, 2, 'inbound', 70, '2025-03-16');





























