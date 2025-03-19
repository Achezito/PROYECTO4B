-- Insertando en CATEGORY
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
VALUES ('Sector A', 200, 1, 1);

-- Insertando en SUB_WAREHOUSE_MATERIAL
INSERT INTO SUB_WAREHOUSE_MATERIAL (id_sub_warehouse, id_material, quantity)
VALUES (1, 1, 5);

-- Insertando en TRANSACTIONS
INSERT INTO TRANSACTIONS (id_material, id_sub_warehouse, type, quantity, transaction_date)
VALUES (1, 1, 'inbound', 5, '2025-03-18');

-- Insertando en STATUS
INSERT INTO STATUS (description) VALUES ('Pendiente');

-- Insertando en ORDERS
INSERT INTO ORDERS (order_date, id_status, id_supply, quantity)
VALUES ('2025-03-18', 1, 1, 10);

-- Insertando en CONDITION
INSERT INTO CONDITION (description) VALUES ('Nuevo');

-- Insertando en EQUIPMENT
INSERT INTO EQUIPMENT (name, type, id_condition)
VALUES ('Multímetro', 'Instrumento de medición', 1);

-- Insertando en MAINTENANCE
INSERT INTO MAINTENANCE (description, maintenance_date, id_equipment)
VALUES ('Revisión general', '2025-03-18', 1);