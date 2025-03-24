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
(14,'Altavoces');


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


----------------------- RECEIVED_MATERIAL -----------------------
INSERT INTO RECEIVED_MATERIAL (description, serial_number, id_supply, id_category, id_rotation, volume) 
VALUES ('procesador', 'DP-202403001', 1, 1, 1, 3.5);



----------------------- MATERIAL_LINK -----------------------
INSERT INTO MATERIAL_LINK (id_supply, id_material_hardware, id_material_component, id_material_physical) VALUES
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





























