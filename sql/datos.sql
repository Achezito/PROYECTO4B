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

