-- Active: 1742707096914@@127.0.0.1@3306@inventario
use inventario;

-- Crear roles
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS material_component;
DROP TABLE IF EXISTS material_hardware;
DROP TABLE IF EXISTS material_link;
DROP TABLE IF EXISTS material_physical;
DROP TABLE IF EXISTS material_type;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS received_material;
DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS rotation;
DROP TABLE IF EXISTS status;
DROP TABLE IF EXISTS sub_warehouse;
DROP TABLE IF EXISTS sub_warehouse_material;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS supply;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS warehouse;
SELECT 
    COALESCE(mh.model, mc.model, mp.model) AS material_model,
    COALESCE(mh.brand, mc.brand, mp.brand) AS material_brand,
    mt.name AS material_type
FROM material_link ml
LEFT JOIN material_hardware mh ON ml.id_material_hardware = mh.id_material
LEFT JOIN material_component mc ON ml.id_material_component = mc.id_material
LEFT JOIN material_physical mp ON ml.id_material_physical = mp.id_material
LEFT JOIN material_type mt ON mt.id_type = COALESCE(mh.id_type, mc.id_type, mp.id_type)
WHERE ml.id_supply = 1;

INSERT INTO MATERIAL_LINK (id_supply, id_material_hardware, id_material_component, id_material_physical)
VALUES (3, NULL, NULL, 1);
INSERT INTO received_material (description, serial_number, id_supply, id_category, volume, created_at)
VALUES ('Material recibido', 'SN-1234567890', 1, 1, 10, NOW());
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

----------------------- Rotation -----------------------
INSERT INTO ROTATION (id_rotation, type, description) VALUES
(1, 'Alta', 'Productos con un movimiento constante y rápido, de alta demanda o perecederos.'),
(2, 'Media', 'Productos con una demanda estable pero no tan rápida como los de alta rotación.'),
(3, 'Baja', 'Productos que permanecen largos periodos en el almacén sin ser vendidos o distribuidos.'),
(4, 'Justo a Tiempo (JIT)', 'Se mantiene el inventario al mínimo, reabasteciendo solo cuando es necesario.'),
(5, 'FIFO', 'Los primeros productos en entrar son los primeros en salir, común en alimentos y farmacéuticos.'),
(6, 'LIFO', 'Los últimos productos en entrar son los primeros en salir, útil en industrias con costos cambiantes.'),
(7, 'ABC', 'Clasificación basada en valor y demanda: A (alto valor, baja cantidad), B (valor medio), C (bajo valor, alta cantidad).');


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
(14,'Altavoces'),
(15,'Batteria (Fuente de Alimentacion)');


----------------------- Supplier -----------------------
INSERT INTO SUPPLIER (id_supplier, name, contact_info, address) VALUES
(1, 'TechSource Inc.', 'techsource@email.com', '500 Tech St, San Francisco'),
(2,'ComponentPro', 'componentpro@email.com', '350 Silicon Ave, Austin'),
(3, 'EnergyMax Ltd.', 'energymax@email.com', '700 Energy Blvd, Houston'),
(4, 'PC Parts Direct', 'pcparts@email.com', '100 Hardware Rd, New York'),
(5, 'Silicon Valley Hardware', 'svhardware@email.com', '250 Innovation St, San Jose'),
(6, 'MemoryTech Solutions', 'memorytech@email.com', '125 Memory Ln, Seattle'),
(7, 'StorageXperts', 'storagexperts@email.com', '400 Storage Dr, Chicago'),
(8, 'GPU Masters', 'gpumasters@email.com', '600 Graphics Ave, Los Angeles'),
(9, 'NextGen Components', 'nextgencomp@email.com', '275 Future Way, Miami'),
(10, 'MegaChip Supplies', 'megachip@email.com', '900 Processor Blvd, Boston');

----------------------- STATUS -----------------------
INSERT INTO STATUS (id_status, description) VALUES
(1, 'Pendiente'),
(2, 'Entregado');


----------------------- Supply -----------------------
INSERT INTO SUPPLY (id_supply, quantity, id_supplier) VALUES
(1, 30, 1),
(2, 30, 2),
(3, 30, 3);


----------------------- ORDERS -----------------------
INSERT INTO ORDERS (id_order, supply_quantity) VALUES
(1, 2),
(2, 3);

----------------------- Material Component ----------------------
INSERT INTO MATERIAL_COMPONENT (id_material, model, brand, chipset, form_factor, socket_type, RAM_slots, max_RAM, expansion_slots, capacity, voltage, id_type, id_supplier) VALUES
(1, 'Z490 AORUS MASTER', 'Gigabyte', 'Intel Z490', 'ATX', 'LGA 1200', 4, 64.00, 2, 450.00, 1.20, 1, 1),
(2, 'ROG Strix B550-F', 'Asus', 'AMD B550', 'ATX', 'AM4', 4, 64.00, 2, 380.00, 1.10, 2, 3),
(3, 'MSI B450 TOMAHAWK', 'MSI', 'AMD B450', 'ATX', 'AM4', 4, 64.00, 1, 320.00, 1.20, 3, 3),
(4, 'Z390 AORUS PRO', 'Gigabyte', 'Intel Z390', 'ATX', 'LGA 1151', 4, 64.00, 2, 420.00, 1.20, 4, 4),
(5, 'X570 TUF GAMING', 'Asus', 'AMD X570', 'ATX', 'AM4', 4, 64.00, 2, 500.00, 1.20, 5, 5),
(6, 'B460M-A', 'Asus', 'Intel B460', 'Micro-ATX', 'LGA 1200', 2, 32.00, 1, 280.00, 1.20, 1, 6),
(7, 'TUF Gaming B550M-PLUS', 'Asus', 'AMD B550', 'Micro-ATX', 'AM4', 4, 64.00, 2, 360.00, 1.10, 2, 2),
(8, 'MSI MAG B550M MORTAR', 'MSI', 'AMD B550', 'Micro-ATX', 'AM4', 4, 64.00, 2, 390.00, 1.10, 3, 3),
(9, 'B450M DS3H', 'Gigabyte', 'AMD B450', 'Micro-ATX', 'AM4', 2, 32.00, 1, 270.00, 1.20, 4, 4),
(10, 'B460M Pro-VDH', 'MSI', 'Intel B460', 'Micro-ATX', 'LGA 1200', 2, 32.00, 1, 290.00, 1.20, 5, 5),
(11, 'B550-A PRO', 'MSI', 'AMD B550', 'ATX', 'AM4', 4, 64.00, 2, 440.00, 1.20, 1, 1),
(12, 'B460M Steel Legend', 'ASRock', 'Intel B460', 'Micro-ATX', 'LGA 1200', 2, 32.00, 1, 310.00, 1.20, 2, 2),
(13, 'ASRock X570 Phantom Gaming', 'ASRock', 'AMD X570', 'ATX', 'AM4', 4, 64.00, 2, 470.00, 1.20, 3, 3),
(14, 'MSI MEG Z490 GODLIKE', 'MSI', 'Intel Z490', 'ATX', 'LGA 1200', 4, 64.00, 2, 520.00, 1.20, 4, 4),
(15, 'ASUS Prime Z590-A', 'Asus', 'Intel Z590', 'ATX', 'LGA 1200', 4, 64.00, 2, 500.00, 1.20, 5, 5),
(16, 'Gigabyte AORUS B550 PRO', 'Gigabyte', 'AMD B550', 'ATX', 'AM4', 4, 64.00, 2, 440.00, 1.20, 1, 1),
(17, 'MSI MPG Z490 GAMING EDGE', 'MSI', 'Intel Z490', 'ATX', 'LGA 1200', 4, 64.00, 2, 480.00, 1.20, 2, 2),
(18, 'Gigabyte X570 AORUS ELITE', 'Gigabyte', 'AMD X570', 'ATX', 'AM4', 4, 64.00, 2, 500.00, 1.20, 3, 3),
(19, 'Z370 Pro4', 'ASRock', 'Intel Z370', 'ATX', 'LGA 1151', 4, 64.00, 2, 410.00, 1.20, 4, 4),
(20, 'MSI Z490-A PRO', 'MSI', 'Intel Z490', 'ATX', 'LGA 1200', 4, 64.00, 2, 450.00, 1.20, 5, 5);

INSERT INTO MATERIAL_COMPONENT (id_material, capacity, voltage, type, brand, model, id_type, id_supplier) VALUES 
(21, 4000, 3.7, 'Lithium-ion', 'Samsung', 'EB-BG975ABY', 3, 1),
(22, 3000, 3.8, 'Lithium-ion', 'LG', 'LG G5 Battery', 3, 2),
(23, 3500, 3.7, 'Lithium-ion', 'Sony', 'Xperia Z3 Battery', 3, 3),
(24, 5000, 3.8, 'Lithium-ion', 'Motorola', 'Moto G Power Battery', 3, 4),
(25, 3500, 3.7, 'Lithium-ion', 'Apple', 'iPhone 6S Battery', 3, 5),
(26, 4000, 3.8, 'Lithium-ion', 'Huawei', 'P30 Pro Battery', 3, 6),
(27, 2800, 3.7, 'Lithium-ion', 'Nokia', 'Nokia 7 Plus Battery', 3, 7),
(28, 4500, 3.8, 'Lithium-ion', 'OnePlus', 'OnePlus 8T Battery', 3, 8),
(29, 3200, 3.7, 'Lithium-ion', 'Google', 'Pixel 4 Battery', 3, 9),
(30, 3700, 3.7, 'Lithium-ion', 'Xiaomi', 'Mi 10 Battery', 3, 10),
(31, 2900, 3.8, 'Lithium-ion', 'Oppo', 'Oppo Reno2 Battery', 3, 1),
(32, 3600, 3.7, 'Lithium-ion', 'Realme', 'Realme 7 Pro Battery', 3, 2),
(33, 4200, 3.7, 'Lithium-ion', 'Vivo', 'Vivo V21e Battery', 3, 3),
(34, 5000, 3.8, 'Lithium-ion', 'Samsung', 'Galaxy S21 Ultra Battery', 3, 4),
(35, 2500, 3.7, 'Lithium-ion', 'Lenovo', 'Moto E Battery', 3, 5),
(36, 3700, 3.7, 'Lithium-ion', 'Asus', 'Zenfone 7 Battery', 3, 6),
(37, 4100, 3.8, 'Lithium-ion', 'HTC', 'HTC U11 Battery', 3, 7),
(38, 4200, 3.7, 'Lithium-ion', 'Blackberry', 'KeyOne Battery', 3, 8),
(39, 3800, 3.7, 'Lithium-ion', 'Sony', 'Xperia XZ Premium Battery', 3, 9),
(40, 4500, 3.8, 'Lithium-ion', 'LG', 'V40 ThinQ Battery', 3, 10);


INSERT INTO MATERIAL_COMPONENT (id_material, capacity, type, brand, model, id_type, id_supplier) VALUES
(41, 150, 'Air Cooling', 'Cooler Master', 'Hyper 212 EVO', 11, 1),
(42, 200, 'Air Cooling', 'NZXT', 'KRaken X73', 11, 2),
(43, 180, 'Liquid Cooling', 'Corsair', 'iCUE H100i Elite Capellix', 11, 3),
(44, 160, 'Air Cooling', 'Noctua', 'NH-D15', 11, 4),
(45, 250, 'Liquid Cooling', 'Corsair', 'Hydro Series H150i Pro', 11, 5),
(46, 120, 'Air Cooling', 'be quiet!', 'Dark Rock Pro 4', 11, 6),
(47, 220, 'Liquid Cooling', 'Cooler Master', 'MasterLiquid ML360R', 11, 7),
(48, 140, 'Air Cooling', 'DeepCool', 'Gammaxx 400 V2', 11, 8),
(49, 100, 'Air Cooling', 'ARCTIC', 'Freezer 34 eSports', 11, 9),
(50, 180, 'Liquid Cooling', 'NZXT', 'Kraken Z63', 11, 10),
(51, 160, 'Air Cooling', 'Scythe', 'Mugen 5 Rev.B', 11, 1),
(52, 250, 'Liquid Cooling', 'EKWB', 'EK-AIO 360 D-RGB', 11, 2),
(53, 140, 'Air Cooling', 'Thermaltake', 'Frio Silent 12', 11, 3),
(54, 210, 'Liquid Cooling', 'Corsair', 'iCUE H115i Elite Capellix', 11, 4),
(55, 200, 'Air Cooling', 'Phanteks', 'PH-TC14PE', 11, 5),
(56, 150, 'Air Cooling', 'Cryorig', 'R1 Ultimate', 11, 6),
(57, 170, 'Liquid Cooling', 'MSI', 'MAG CoreLiquid 360R', 11, 7),
(58, 120, 'Air Cooling', 'Zalman', 'CNPS10X Performa+', 11, 8),
(59, 220, 'Liquid Cooling', 'Gigabyte', 'AORUS Liquid Cooler 360', 11, 9),
(60, 180, 'Air Cooling', 'SilverStone', 'Argon AR07', 11, 10);


INSERT INTO MATERIAL_COMPONENT (id_material, type, quantity, brand, model, id_type, id_supplier) VALUES --ports
(61, 'USB 3.0', 4, 'Intel', 'USB 3.0 Port', 12, 1),
(62, 'HDMI', 1, 'Samsung', 'HDMI 2.1', 12, 2),
(63, 'Ethernet', 1, 'TP-Link', 'Gigabit Ethernet', 12, 3),
(64, 'USB-C', 2, 'Apple', 'Thunderbolt 3', 12, 4),
(65, 'DisplayPort', 1, 'Nvidia', 'DisplayPort 1.4', 12, 5),
(66, 'Thunderbolt', 1, 'Intel', 'Thunderbolt 3', 12, 6),
(67, 'VGA', 1, 'AMD', 'VGA 1080p', 12, 7),
(68, 'Audio Jack', 2, 'Realtek', 'Audio Jack 3.5mm', 12, 8),
(69, 'USB 2.0', 2, 'Asus', 'USB 2.0 Port', 12, 9),
(70, 'SD Card Slot', 1, 'Kingston', 'MicroSD Slot', 12, 10),
(71, 'USB 3.0', 3, 'Intel', 'USB 3.0 Port', 12, 1),
(72, 'HDMI', 1, 'Samsung', 'HDMI 2.1', 12, 2),
(73, 'Ethernet', 1, 'TP-Link', 'Gigabit Ethernet', 12, 3),
(74, 'USB-C', 1, 'Apple', 'Thunderbolt 3', 12, 4),
(75, 'DisplayPort', 1, 'Nvidia', 'DisplayPort 1.4', 12, 5),
(76, 'Thunderbolt', 1, 'Intel', 'Thunderbolt 3', 12, 6),
(77, 'VGA', 1, 'AMD', 'VGA 1080p', 12, 7),
(78, 'Audio Jack', 1, 'Realtek', 'Audio Jack 3.5mm', 12, 8),
(79, 'USB 2.0', 2, 'Asus', 'USB 2.0 Port', 12, 9),
(80, 'SD Card Slot', 1, 'Kingston', 'MicroSD Slot', 12, 10);

INSERT INTO MATERIAL_COMPONENT (id_material, type, brand, model, id_type, id_supplier) VALUES --- sound card
(81, 'Integrated', 'Realtek', 'ALC1150', 13, 1),
(82, 'External', 'Creative', 'Sound Blaster X3', 13, 2),
(83, 'Integrated', 'Realtek', 'ALC892', 13, 3),
(84, 'External', 'Asus', 'Xonar U7 MKII', 13, 4),
(85, 'Integrated', 'Intel', 'High Definition Audio', 13, 5),
(86, 'External', 'Focusrite', 'Scarlett 2i2', 13, 6),
(87, 'Integrated', 'Realtek', 'ALC269', 13, 7),
(88, 'External', 'Behringer', 'UFO202', 13, 8),
(89, 'Integrated', 'Realtek', 'ALC892', 13, 9),
(90, 'External', 'MOTU', 'M4 Audio Interface', 13, 10),
(91, 'Integrated', 'Realtek', 'ALC1220', 13, 1),
(92, 'External', 'Sennheiser', 'GSX 1000', 13, 2),
(93, 'Integrated', 'Realtek', 'ALC897', 13, 3),
(94, 'External', 'FiiO', 'K3', 13, 4),
(95, 'Integrated', 'Intel', 'High Definition Audio', 13, 5),
(96, 'External', 'Behringer', 'UMC22', 13, 6),
(97, 'Integrated', 'Realtek', 'ALC888', 13, 7),
(98, 'External', 'Focusrite', 'Scarlett Solo', 13, 8),
(99, 'Integrated', 'Realtek', 'ALC1220', 13, 9),
(100, 'External', 'PreSonus', 'AudioBox USB 96', 13, 10);

INSERT INTO MATERIAL_COMPONENT (id_material, voltage, brand, model, id_type, id_supplier) VALUES
(101, 15, 'JBL', 'Flip 5', 14, 1),
(102, 30, 'Sony', 'SRS-XB12', 14, 2),
(103, 20, 'Bose', 'SoundLink Mini', 14, 3),
(104, 25, 'Logitech', 'Z313', 14, 4),
(105, 18, 'Anker', 'Soundcore 2', 14, 5),
(106, 35, 'Creative', 'Pebble V2', 14, 6),
(107, 12, 'Ultimate Ears', 'BOOM 3', 14, 7),
(108, 40, 'Harman Kardon', 'SoundSticks 4', 14, 8),
(109, 16, 'Marshall', 'Emberton', 14, 9),
(110, 50, 'Edifier', 'R980T', 14, 10),
(111, 14, 'JBL', 'Charge 4', 14, 1),
(112, 45, 'Bose', 'SoundTouch 10', 14, 2),
(113, 22, 'Sony', 'SRS-XB43', 14, 3),
(114, 30, 'Logitech', 'Z906', 14, 4),
(115, 24, 'Anker', 'Soundcore Flare', 14, 5),
(116, 28, 'Harman Kardon', 'Onyx Studio 4', 14, 6),
(117, 20, 'Bose', 'Home Speaker 500', 14, 7),
(118, 38, 'Sony', 'HT-S350', 14, 8),
(119, 18, 'Ultimate Ears', 'MEGABOOM 3', 14, 9),
(120, 60, 'Edifier', 'S350DB', 14, 10);



----------------------- Material Hardware -----------------------
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, speed, cores, threads, cache_memory, power_consumption, id_type, id_supplier) VALUES  -- insert de procesadores
(1, 'Ryzen 7 5800X', 'AMD', 3.8, 8, 16, '32MB', 105.00, 1, 1);
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, capacity, tipo, speed, id_type, id_supplier) VALUES   -- insert de memoria ram
(2, 'Vengeance LPX', 'Corsair', 16.00, 'DDR4', 3200.00, 2, 2);
INSERT INTO MATERIAL_HARDWARE (id_material, model, tipo, capacity, read_speed, write_speed, id_type, id_supplier) VALUES    -- insert de discos duros
(3, 'WD Blue', 'HDD', 1000.00, 150.00, 140.00, 3, 3);
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, cores, power_consumption, vram, frecuency, id_type, id_supplier) VALUES   -- insert de gpu's
(4, 'RTX 3080', 'NVIDIA', 8704, 320.00, 10.00, 1710.00, 4, 4);

-- Inserts de Procesadores
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, speed, cores, threads, cache_memory, power_consumption, id_type, id_supplier) VALUES  
(5, 'Core i9-13900K', 'Intel', 3.0, 24, 32, '36MB', 125.00, 1, 9),
(6, 'Ryzen 9 7950X', 'AMD', 4.5, 16, 32, '64MB', 170.00, 1, 9),
(7, 'Core i7-13700K', 'Intel', 3.4, 16, 24, '30MB', 125.00, 1, 9),
(8, 'Ryzen 5 7600X', 'AMD', 4.7, 6, 12, '32MB', 105.00, 1, 9),
(9, 'Core i5-13600K', 'Intel', 3.5, 14, 20, '24MB', 125.00, 1, 9),
(10, 'Ryzen 7 5800X3D', 'AMD', 3.4, 8, 16, '96MB', 105.00, 1, 9),
(11, 'Core i3-13100', 'Intel', 3.4, 4, 8, '12MB', 60.00, 1, 9),
(12, 'Ryzen 5 5600G', 'AMD', 3.9, 6, 12, '16MB', 65.00, 1, 9),
(13, 'Core i9-12900KS', 'Intel', 3.4, 16, 24, '30MB', 150.00, 1, 9),
(14, 'Ryzen 3 5300G', 'AMD', 4.0, 4, 8, '8MB', 65.00, 1, 9),
(15, 'Core i7-12700K', 'Intel', 3.6, 12, 20, '25MB', 125.00, 1, 9),
(16, 'Ryzen 7 7700X', 'AMD', 4.5, 8, 16, '32MB', 105.00, 1, 9),
(17, 'Core i5-12600K', 'Intel', 3.7, 10, 16, '20MB', 125.00, 1, 9),
(18, 'Ryzen 9 5950X', 'AMD', 3.4, 16, 32, '64MB', 105.00, 1, 9),
(19, 'Core i3-12100F', 'Intel', 3.3, 4, 8, '12MB', 60.00, 1, 9),
(20, 'Ryzen 5 5500', 'AMD', 3.6, 6, 12, '16MB', 65.00, 1, 9);

-- Inserts de Memoria RAM
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, capacity, tipo, speed, id_type, id_supplier) VALUES
(21, 'Trident Z RGB', 'G.Skill', 32.00, 'DDR4', 3600.00, 2, 6),
(22, 'Ripjaws V', 'G.Skill', 16.00, 'DDR4', 3200.00, 2, 6),
(23, 'Vengeance RGB Pro', 'Corsair', 32.00, 'DDR4', 3600.00, 2, 6),
(24, 'Ballistix Sport', 'Crucial', 16.00, 'DDR4', 2666.00, 2, 6),
(25, 'HyperX Fury', 'Kingston', 16.00, 'DDR4', 3200.00, 2, 6),
(26, 'Dominator Platinum', 'Corsair', 64.00, 'DDR5', 5200.00, 2, 6),
(27, 'Aegis', 'G.Skill', 8.00, 'DDR4', 3000.00, 2, 6),
(28, 'Predator RGB', 'Adata', 32.00, 'DDR5', 6000.00, 2, 6),
(29, 'XPG Spectrix D50', 'Adata', 32.00, 'DDR4', 3600.00, 2, 6),
(30, 'Crucial Pro', 'Crucial', 16.00, 'DDR5', 4800.00, 2, 6),
(31, 'Patriot Viper Steel', 'Patriot', 32.00, 'DDR4', 3733.00, 2, 6),
(32, 'TeamGroup T-Force', 'TeamGroup', 16.00, 'DDR4', 3200.00, 2, 6),
(33, 'Corsair Vengeance LPX', 'Corsair', 32.00, 'DDR4', 3200.00, 2, 6),
(34, 'Crucial Ballistix MAX', 'Crucial', 16.00, 'DDR4', 4400.00, 2, 6),
(35, 'Kingston Fury Renegade', 'Kingston', 32.00, 'DDR5', 6000.00, 2, 6),
(36, 'G.Skill Royal Elite', 'G.Skill', 16.00, 'DDR4', 4000.00, 2, 6),
(37, 'XPG Lancer RGB', 'Adata', 32.00, 'DDR5', 5200.00, 2, 6),
(38, 'TeamGroup Delta RGB', 'TeamGroup', 16.00, 'DDR4', 3600.00, 2, 6),
(39, 'Patriot Viper RGB', 'Patriot', 32.00, 'DDR5', 5600.00, 2, 6),
(40, 'PNY XLR8 Gaming', 'PNY', 16.00, 'DDR4', 3200.00, 2, 6);

-- Inserts de Discos Duros
INSERT INTO MATERIAL_HARDWARE (id_material, model, tipo, capacity, read_speed, write_speed, id_type, id_supplier) VALUES
(41, 'Crucial MX500', 'SSD SATA', 2000.00, 560.00, 510.00, 3, 7),
(42, 'WD Black SN850', 'SSD NVMe', 1000.00, 7000.00, 5300.00, 3, 7),
(43, 'Seagate Barracuda', 'HDD', 4000.00, 190.00, 185.00, 3, 7),
(44, 'Toshiba X300', 'HDD', 8000.00, 200.00, 195.00, 3, 7),
(45, 'Kingston KC3000', 'SSD NVMe', 2000.00, 7000.00, 6000.00, 3, 7),
(46, 'WD Red Pro', 'HDD', 12000.00, 210.00, 200.00, 3, 7),
(47, 'Samsung 870 QVO', 'SSD SATA', 4000.00, 560.00, 530.00, 3, 7),
(48, 'Seagate FireCuda 530', 'SSD NVMe', 2000.00, 7300.00, 6900.00, 3, 7),
(49, 'ADATA SU800', 'SSD SATA', 1000.00, 560.00, 520.00, 3, 7),
(50, 'Crucial P5 Plus', 'SSD NVMe', 1000.00, 6600.00, 5000.00, 3, 7),
(51, 'WD Blue SN550', 'SSD NVMe', 1000.00, 2400.00, 1950.00, 3, 7),
(52, 'Toshiba N300', 'HDD', 14000.00, 260.00, 250.00, 3, 7),
(53, 'Samsung 980 Pro', 'SSD NVMe', 2000.00, 7000.00, 5000.00, 3, 7),
(54, 'Seagate IronWolf Pro', 'HDD', 16000.00, 270.00, 260.00, 3, 7),
(55, 'Kingston A2000', 'SSD NVMe', 500.00, 2200.00, 2000.00, 3, 7),
(56, 'SanDisk Ultra 3D', 'SSD SATA', 2000.00, 560.00, 530.00, 3, 7),
(57, 'WD Gold Enterprise', 'HDD', 18000.00, 280.00, 270.00, 3, 7),
(58, 'Corsair MP600 Pro XT', 'SSD NVMe', 2000.00, 7100.00, 6800.00, 3, 7),
(59, 'Gigabyte Aorus Gen4', 'SSD NVMe', 1000.00, 5000.00, 4400.00, 3, 7),
(60, 'Samsung 970 EVO Plus', 'SSD NVMe', 1000.00, 3500.00, 3300.00, 3, 7);

-- Inserts de GPUs
INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, cores, power_consumption, vram, frecuency, id_type, id_supplier) VALUES
(61, 'RTX 4090', 'NVIDIA', 16384, 450.00, 24.00, 2520.00, 4, 8),
(62, 'RTX 4080', 'NVIDIA', 9728, 320.00, 16.00, 2505.00, 4, 8),
(63, 'RTX 4070 Ti', 'NVIDIA', 7680, 285.00, 12.00, 2610.00, 4, 8),
(64, 'RX 7900 XTX', 'AMD', 6144, 355.00, 24.00, 2500.00, 4, 8),
(65, 'RX 7800 XT', 'AMD', 3840, 263.00, 16.00, 2475.00, 4, 8),
(66, 'RTX 4060', 'NVIDIA', 3072, 115.00, 8.00, 2460.00, 4, 8),
(67, 'RTX 3060', 'NVIDIA', 3584, 170.00, 12.00, 1777.00, 4, 8),
(68, 'RX 6700 XT', 'AMD', 2560, 230.00, 12.00, 2424.00, 4, 8),
(69, 'RTX 3050', 'NVIDIA', 2560, 130.00, 8.00, 1777.00, 4, 8),
(70, 'RX 6650 XT', 'AMD', 2048, 180.00, 8.00, 2635.00, 4, 8),
(71, 'RTX 3070', 'NVIDIA', 5888, 220.00, 8.00, 1725.00, 4, 8),
(72, 'RTX 3090', 'NVIDIA', 10496, 350.00, 24.00, 1700.00, 4, 8),
(73, 'RX 6800 XT', 'AMD', 4608, 300.00, 16.00, 2250.00, 4, 8),
(74, 'RX 7600', 'AMD', 2048, 165.00, 8.00, 2655.00, 4, 8),
(75, 'RTX 2080 Ti', 'NVIDIA', 4352, 250.00, 11.00, 1545.00, 4, 8),
(76, 'RX 5500 XT', 'AMD', 1408, 130.00, 4.00, 1845.00, 4, 8),
(77, 'RTX 4060 Ti', 'NVIDIA', 4352, 160.00, 8.00, 2535.00, 4, 8),
(78, 'RX 6950 XT', 'AMD', 5120, 335.00, 16.00, 2310.00, 4, 8),
(79, 'RTX 2070 Super', 'NVIDIA', 2560, 215.00, 8.00, 1770.00, 4, 8),
(80, 'RX 6400', 'AMD', 768, 53.00, 4.00, 2321.00, 4, 8);


----------------------- Material Physical -----------------------
INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, size, design, material_type, sensitivity, connectivity) VALUES  -- insert de pantalla
(1, '3840x2160', '15.6 inches', 'Slim Bezel', 'OLED', 'High', 'HDMI, DisplayPort');

INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, size, sensitivity, model, brand, id_type, id_supplier) VALUES 
(2, '1920x1080', '15.6"', 'Touchscreen', 'Inspiron 15 5000', 'Dell', 5, 1),
(3, '2560x1440', '17.3"', 'Non-Touch', 'XPS 17', 'Dell', 5, 2),
(4, '3840x2160', '13.3"', 'Touchscreen', 'MacBook Pro 13', 'Apple', 5, 3),
(5, '1366x768', '14.0"', 'Non-Touch', 'Lenovo IdeaPad 14"', 'Lenovo', 5, 4),
(6, '1920x1200', '16.0"', 'Non-Touch', 'ThinkPad T14', 'Lenovo', 5, 5),
(7, '2560x1600', '14.5"', 'Touchscreen', 'Surface Laptop 3', 'Microsoft', 5, 6),
(8, '1920x1080', '13.3"', 'Non-Touch', 'MacBook Air 13"', 'Apple', 5, 7),
(9, '3840x2160', '15.6"', 'Touchscreen', 'XPS 15', 'Dell', 5, 8),
(10, '1280x800', '12.5"', 'Non-Touch', 'Chromebook 12"', 'HP', 5, 9),
(11, '1920x1080', '17.3"', 'Non-Touch', 'TUF Gaming A17', 'ASUS', 5, 10),
(12, '2560x1440', '14.0"', 'Touchscreen', 'Surface Pro 7', 'Microsoft', 5, 1),
(13, '1366x768', '11.6"', 'Non-Touch', 'Chromebook 11"', 'HP', 5, 2),
(14, '1920x1080', '16.1"', 'Touchscreen', 'Envy x360', 'HP', 5, 3),
(15, '3840x2400', '16.0"', 'Non-Touch', 'MacBook Pro 16"', 'Apple', 5, 4),
(16, '1920x1080', '14.1"', 'Non-Touch', 'ThinkPad X1 Carbon', 'Lenovo', 5, 5),
(17, '2560x1440', '17.0"', 'Touchscreen', 'Precision 5000 Series', 'Dell', 5, 6),
(18, '1920x1200', '15.6"', 'Non-Touch', 'Latitude 5510', 'Dell', 5, 7),
(19, '3840x2160', '13.3"', 'Touchscreen', 'MacBook Pro 13"', 'Apple', 5, 8),
(20, '1920x1080', '15.6"', 'Non-Touch', 'Inspiron 15 3000', 'Dell', 5, 9),
(21, '1366x768', '12.0"', 'Non-Touch', 'Acer Aspire 12"', 'Acer', 5, 10);

INSERT INTO MATERIAL_PHYSICAL (id_material, size, design, sensitivity, model, brand, id_type, id_supplier) VALUES -- Teclados
(22, 'Full-size', 'Chiclet, RGB Backlit', 'Mechanical', 'K95 RGB Platinum', 'Corsair', 6, 8),
(23, 'Compact', 'Low-Profile, White Backlit', 'Membrane', 'MX Keys', 'Logitech', 6, 9),
(24, 'Tenkeyless', 'Mechanical, Per-key RGB', 'Mechanical', 'Razer BlackWidow V3', 'Razer', 6, 10),
(25, 'Full-size', 'Standard', 'Membrane', 'K120', 'Logitech', 6, 1),
(26, 'Mechanical', 'RGB Backlit', 'Mechanical', 'G Pro X', 'Logitech', 6, 9),
(27, 'Wireless', 'Ultra-thin', 'Membrane', 'K780 Multi-Device', 'Logitech', 6, 2),
(28, 'Compact', 'Gaming, RGB', 'Mechanical', 'Huntsman Mini', 'Razer', 6, 6),
(29, 'Standard', 'No Backlight', 'Membrane', 'MK120', 'Microsoft', 6, 6),
(30, 'Mechanical', 'High Durability', 'Mechanical', 'SteelSeries Apex Pro', 'SteelSeries', 6, 7),
(31, 'Full-size', 'White LED Backlit', 'Membrane', 'DeskBoard', 'Keychron', 6, 8),
(32, 'Low-Profile', 'Minimalist', 'Membrane', 'Pavilion 500', 'HP', 6, 9),
(33, 'Gaming', 'RGB Backlit', 'Mechanical', 'Corsair K70 RGB MK.2', 'Corsair', 6, 10),
(34, 'Standard', 'Office Use', 'Membrane', 'Classic 104', 'Microsoft', 6, 4),
(35, 'Compact', 'Ultra-lightweight', 'Membrane', 'Logitech K380', 'Logitech', 6, 3),
(36, 'Gaming', 'Per-key RGB', 'Mechanical', 'Razer Huntsman Elite', 'Razer', 6, 4),
(37, 'Standard', 'No Numpad', 'Membrane', 'M100', 'Logitech', 6, 7),
(38, 'Mechanical', 'RGB, Durable', 'Mechanical', 'HyperX Alloy FPS Pro', 'HyperX', 6, 6),
(39, 'Slim', 'Silent Typing', 'Membrane', 'Ultra Slim', 'Microsoft', 6, 7),
(40, 'Gaming', 'High Response', 'Mechanical', 'SteelSeries Apex 7', 'SteelSeries', 6, 8),
(41, 'Compact', 'Standard', 'Membrane', 'Keychron K6', 'Keychron', 6, 9);



INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, size, design, model, brand, id_type, id_supplier) VALUES -- Touchpad
(42, NULL, '4.5"x3.1"', 'Glass Precision Touchpad', 'Precision Touchpad', 'Dell', 7, 1),
(43, NULL, '5.3"x3.2"', 'Haptic Feedback, Click Anywhere', 'TouchPad X', 'HP', 7, 2),
(44, NULL, '4.7"x3.0"', 'Standard Multi-Touch', 'TouchPad 101', 'Lenovo', 7, 3),
(45, NULL, '4.6"x3.2"', 'Precision Touchpad', 'Multi-Precision', 'Asus', 7, 4),
(46, NULL, '5.0"x3.0"', 'Responsive Touchpad', 'EcoTouch', 'Acer', 7, 5),
(47, NULL, '4.5"x3.0"', 'Smooth Glass Surface', 'Glass Surface 500', 'Apple', 7, 6),
(48, NULL, '5.2"x3.1"', 'Responsive, Multi-Touch', 'UltraTouch', 'Microsoft', 7, 7),
(49, NULL, '5.0"x3.3"', 'Standard Click Touchpad', 'ClickPad', 'HP', 7, 8),
(50, NULL, '4.8"x3.2"', 'Glass Precision Touchpad', 'Precision Touch', 'Dell', 7, 9),
(51, NULL, '4.9"x3.1"', 'Haptic Feedback', 'FeelPad', 'Logitech', 7, 10),
(52, NULL, '4.6"x3.0"', 'Standard Touchpad', 'SmoothGlide', 'Lenovo', 7, 1),
(53, NULL, '5.1"x3.2"', 'Force Touch', 'ForceTouchPad', 'Apple', 7, 2),
(54, NULL, '5.0"x3.1"', 'Standard Precision', 'PrecisionPad', 'Microsoft', 7, 3),
(55, NULL, '4.8"x3.0"', 'Responsive Glass Surface', 'ClearTouch', 'Asus', 7, 4),
(56, NULL, '5.4"x3.3"', 'Click Anywhere', 'AnyClick Pad', 'Acer', 7, 5),
(57, NULL, '5.2"x3.0"', 'Precision Touchpad', 'TouchPlus', 'HP', 7, 6),
(58, NULL, '4.7"x3.1"', 'Responsive, Multi-Touch', 'QuickTouch', 'Dell', 7, 7),
(59, NULL, '4.9"x3.2"', 'Smooth Glass Precision', 'PrecisionGlass', 'Logitech', 7, 8),
(60, NULL, '5.0"x3.0"', 'Click Anywhere', 'TouchClick', 'Lenovo', 7, 9),
(61, NULL, '5.1"x3.3"', 'Haptic Feedback', 'PadFeel', 'Microsoft', 7, 10);



INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, design, model, brand, id_type, id_supplier) VALUES -- Cámaras para laptops
(62, '720p', 'Integrated Webcam', 'HD Webcam 720p', 'Logitech', 8, 1),
(63, '1080p', 'Webcam', 'C920 HD Pro', 'Logitech', 8, 2),
(64, '4K', 'Webcam', 'Brio Ultra HD', 'Logitech', 8, 3),
(65, '720p', 'Integrated Webcam', 'HD Camera', 'HP', 8, 4),
(66, '1080p', 'Webcam', 'WEBCAM 1080p', 'Acer', 8, 5),
(67, '720p', 'Integrated Webcam', 'Built-in HD Camera', 'Dell', 8, 6),
(68, '1080p', 'Webcam', 'C922 Pro Stream', 'Logitech', 8, 7),
(69, '720p', 'Integrated Webcam', 'HP HD Webcam', 'HP', 8, 8),
(70, '1080p', 'Webcam', 'P920 HD Webcam', 'Lenovo', 8, 9),
(71, '720p', 'Integrated Webcam', 'HD Camera', 'Asus', 8, 10),
(72, '1080p', 'Webcam', 'C930e Business Webcam', 'Logitech', 8, 1),
(73, '720p', 'Integrated Webcam', 'HD Webcam', 'MSI', 8, 2),
(74, '1080p', 'Webcam', 'ASUS ROG Webcam', 'Asus', 8, 3),
(75, '720p', 'Integrated Webcam', 'EliteBook HD Webcam', 'HP', 8, 4),
(76, '1080p', 'Webcam', 'Logitech StreamCam', 'Logitech', 8, 5),
(77, '4K', 'Webcam', 'Brio 4K Pro', 'Logitech', 8, 6),
(78, '720p', 'Integrated Webcam', 'Acer HD Webcam', 'Acer', 8, 7),
(79, '1080p', 'Webcam', 'Lenovo 1080p Camera', 'Lenovo', 8, 8),
(80, '720p', 'Integrated Webcam', 'Toshiba Built-in Webcam', 'Toshiba', 8, 9),
(81, '1080p', 'Webcam', 'Razer Kiyo', 'Razer', 8, 10);


INSERT INTO MATERIAL_PHYSICAL (id_material, material_type, design, model, brand, id_type, id_supplier) VALUES -- Chasis para laptops
(82, 'Aluminum', 'Slim, High Durability', 'XPS 13 Chassis', 'Dell', 9, 1),
(83, 'Plastic', 'Compact, Lightweight', 'Inspiron Chassis', 'Dell', 9, 2),
(84, 'Aluminum', 'Premium, Sleek', 'MacBook Air Chassis', 'Apple', 9, 3),
(85, 'Magnesium Alloy', 'Ultra-thin, Reinforced', 'ThinkPad Chassis', 'Lenovo', 9, 4),
(86, 'Plastic', 'Ergonomic, Textured', 'Envy 13 Chassis', 'HP', 9, 5),
(87, 'Aluminum', 'Sleek, Modern', 'ZenBook Chassis', 'Asus', 9, 6),
(88, 'Plastic', 'Shockproof, Durable', 'Predator Chassis', 'Acer', 9, 7),
(89, 'Aluminum', 'Lightweight, High Performance', 'EliteBook Chassis', 'HP', 9, 8),
(90, 'Magnesium Alloy', 'Durable, Lightweight', 'Surface Laptop Chassis', 'Microsoft', 9, 9),
(91, 'Aluminum', 'Premium, Slim', 'Pavilion Chassis', 'HP', 9, 10),
(92, 'Aluminum', 'Thin, Light', 'Razer Blade Stealth Chassis', 'Razer', 9, 1),
(93, 'Plastic', 'Textured, Lightweight', 'Lenovo IdeaPad Chassis', 'Lenovo', 9, 2),
(94, 'Magnesium Alloy', 'Modern, Sleek', 'MacBook Pro Chassis', 'Apple', 9, 3),
(95, 'Aluminum', 'Luxury, Ultra-thin', 'Spectre x360 Chassis', 'HP', 9, 4),
(96, 'Plastic', 'Robust, Shock-resistant', 'Toughbook Chassis', 'Panasonic', 9, 5),
(97, 'Aluminum', 'Stylish, Slim', 'Acer Swift Chassis', 'Acer', 9, 6),
(98, 'Magnesium Alloy', 'Elegant, Lightweight', 'ASUS VivoBook Chassis', 'Asus', 9, 7),
(99, 'Aluminum', 'Premium, Ultra-light', 'Latitude Chassis', 'Dell', 9, 8),
(100, 'Plastic', 'Slim, Durable', 'Chromebook Chassis', 'HP', 9, 9),
(101, 'Aluminum', 'Rugged, Performance', 'Toughbook 55 Chassis', 'Panasonic', 9, 10);


----------------------- RECEIVED_MATERIAL -----------------------
INSERT INTO RECEIVED_MATERIAL (description, serial_number, id_supply, id_category, id_rotation, volume) 
VALUES ('procesador', 'DP-202403001', 1, 1, 1, 3.5);




----------------------- MATERIAL_LINK -----------------------
-- Rellenar la tabla MATERIAL_LINK con datos basados en los materiales existentes

-- Asociar materiales de hardware
INSERT INTO MATERIAL_LINK (id_supply, id_material_hardware, id_material_component, id_material_physical)
VALUES
(1, 1, NULL, NULL), -- Asociar el suministro 1 con el material de hardware 1
(2, 2, NULL, NULL), -- Asociar el suministro 2 con el material de hardware 2
(3, 3, NULL, NULL), -- Asociar el suministro 3 con el material de hardware 3
(1, 4, NULL, NULL); -- Asociar el suministro 1 con el material de hardware 4

-- Asociar materiales de componentes
INSERT INTO MATERIAL_LINK (id_supply, id_material_hardware, id_material_component, id_material_physical)
VALUES
(2, NULL, 1, NULL), -- Asociar el suministro 2 con el material de componente 1
(3, NULL, 2, NULL), -- Asociar el suministro 3 con el material de componente 2
(1, NULL, 3, NULL); -- Asociar el suministro 1 con el material de componente 3

-- Asociar materiales físicos
INSERT INTO MATERIAL_LINK (id_supply, id_material_hardware, id_material_component, id_material_physical)
VALUES
(1, NULL, NULL, 1), -- Asociar el suministro 1 con el material físico 1
(2, NULL, NULL, 2), -- Asociar el suministro 2 con el material físico 2
(3, NULL, NULL, 3); -- Asociar el suministro 3 con el material físico 3


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
INSERT INTO TRANSACTIONS (id_material, id_sub_warehouse, type, quantity) VALUES
(4, 1, 'inbound', 30);





-----------------------------------------------------------

INSERT INTO ROLE (id_role, `name`) VALUES
(1, 'Admin'),
(2, 'Usuario');

-- Usuario de prueba (Contraseña: 123456)
INSERT INTO USER (id_user, `name`, password_hash, email, id_role) VALUES
(1, 'Admin', SHA1('123456'), 'admin@example.com', 1),
(2, 'Usuario', SHA1('123456'), 'user@example.com', 2);


INSERT INTO WAREHOUSE (id_warehouse, `name`, `location`, `capacity`) VALUES
(1, 'Almacén Principal', 'Ciudad Industrial, Sector B', 5000);

INSERT INTO SUB_WAREHOUSE (id_sub_warehouse, `location`, `capacity`, `id_warehouse`, `id_category`) VALUES
(1, 'Sector B1', 1500, 1, 1),
(2, 'Sector B2', 2000, 1, 2),
(3, 'Sector B3', 1500, 1, 3);
INSERT INTO SUPPLIER (id_supplier, name, contact_info, address) VALUES
(1, 'TechSource Inc.', 'techsource@email.com', '500 Tech St, San Francisco'),
(2, 'ComponentPro', 'componentpro@email.com', '350 Silicon Ave, Austin'),
(3, 'EnergyMax Ltd.', 'energymax@email.com', '700 Energy Blvd, Houston');


INSERT INTO STATUS (id_status, description) VALUES
(1, 'Pendiente'),
(2, 'Entregado');


INSERT INTO CATEGORY (id_category, `name`, `description`) VALUES
(1, 'Hardware', 'Componentes físicos de computadoras y dispositivos electrónicos.'),
(2, 'Materiales Físicos', 'Dispositivos y componentes para el almacenamiento de datos.'),
(3, 'Componentes', 'Unidades de procesamiento como CPUs y GPUs.');

INSERT INTO SUPPLY (id_supply, quantity, id_supplier, id_status) VALUES
(1, 10, 1, 1), -- Suministro pendiente del proveedor 1
(2, 5, 2, 1);  -- Suministro pendiente del proveedor 2

INSERT INTO MATERIAL_TYPE (id_type, `name`) VALUES
(1, 'Procesador'),
(2, 'Memoria RAM'),
(3, 'Disco Duro'),
(4, 'Unidad De Procesamiento Gráfico (GPU)');



INSERT INTO MATERIAL_TYPE (id_type, `name`) VALUES
(1, 'Procesador'),
(2, 'Memoria RAM');

INSERT INTO MATERIAL_HARDWARE (id_material, model, brand, speed, cores, threads, cache_memory, power_consumption, id_type, id_supplier) VALUES
(1, 'Ryzen 5 3600', 'AMD', 3.6, 6, 12, '32MB', 65.00, 1, 1),
(2, 'Core i5-10400', 'Intel', 2.9, 6, 12, '12MB', 65.00, 1, 2);
INSERT INTO MATERIAL_COMPONENT (id_material, model, brand, chipset, form_factor, socket_type, RAM_slots, max_RAM, expansion_slots, capacity, voltage, id_type, id_supplier) VALUES
(3, 'Z490 AORUS MASTER', 'Gigabyte', 'Intel Z490', 'ATX', 'LGA 1200', 4, 64.00, 2, 450.00, 1.20, 1, 1),
(4, 'ROG Strix B550-F', 'Asus', 'AMD B550', 'ATX', 'AM4', 4, 64.00, 2, 380.00, 1.10, 2, 2);

INSERT INTO MATERIAL_PHYSICAL (id_material, resolution, size, design, material_type, sensitivity, connectivity) VALUES
(5, '1920x1080', '15.6 inches', 'Slim Bezel', 'IPS', 'High', 'HDMI, DisplayPort'),
(6, '2560x1440', '17.3 inches', 'Wide Bezel', 'OLED', 'Medium', 'HDMI, USB-C');


INSERT INTO MATERIAL_LINK (id_supply, id_material_hardware, id_material_component, id_material_physical) VALUES
(1, 1, NULL, NULL), -- Asociar el suministro 1 con el material de hardware 1
(2, NULL, 3, NULL), -- Asociar el suministro 2 con el material de componente 3
(3, NULL, NULL, 5); -- Asociar el suministro 3 con el material físico 5

-- Órdenes relacionadas con los suministros
INSERT INTO ORDERS (id_order, supply_quantity, id_supply) VALUES
(1, 10, 1), -- Orden para el suministro 1
(2, 5, 2),  -- Orden para el suministro 2
(3, 15, 3); -- Orden para el suministro 3

-- Actualización de la tabla SUPPLY para incluir id_order
UPDATE SUPPLY SET id_order = 1 WHERE id_supply = 1;
UPDATE SUPPLY SET id_order = 2 WHERE id_supply = 2;
UPDATE SUPPLY SET id_order = 3 WHERE id_supply = 3;

INSERT INTO TRANSACTIONS (id_material, id_sub_warehouse, type, quantity) VALUES
(1, 1, 'inbound', 10),
(3, 2, 'inbound', 5),
(5, 3, 'inbound', 8);











