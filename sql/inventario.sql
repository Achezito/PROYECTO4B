-- Active: 1720557520067@@127.0.0.1@3306@inventario



create database inventario;


USE inventario;

ALTER DATABASE wms SET OFFLINE;

-- Tabla de categorías de materiales
CREATE TABLE CATEGORY (
    id_category INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Tabla de estados
CREATE TABLE STATUS (
    id_status INT PRIMARY KEY AUTO_INCREMENT,
    `description` VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE ROTATION (
    id_rotation INT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL
);


-- Tabla de tipos de materiales
CREATE TABLE MATERIAL_TYPE (
    id_type INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL
);


-- Tabla de proveedores
CREATE TABLE SUPPLIER (
    id_supplier INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    contact_info VARCHAR(255),
    `address` VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE SUPPLY (
    id_supply INT AUTO_INCREMENT PRIMARY KEY,
    id_order INT,
    quantity INT,
    id_supplier INT,
    id_status INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_supplier) REFERENCES SUPPLIER(id_supplier),
    FOREIGN KEY (id_status) REFERENCES STATUS(id_status),
    FOREIGN KEY (id_order) REFERENCES ORDERS(id_order)
);


-- Tabla de materiales de hardware (especializada)
CREATE TABLE MATERIAL_HARDWARE (
    id_material INT PRIMARY KEY AUTO_INCREMENT,
    model VARCHAR(255),
    brand VARCHAR(100),
    power_consumption DECIMAL(5,2),
    frecuency DECIMAL(5,2),
    vram DECIMAL(5,2),
    speed DECIMAL(5,2),
    cores INT,
    threads INT,
    cache_memory VARCHAR(50),
    tipo VARCHAR(50),
    capacity DECIMAL(5,2),
    read_speed DECIMAL(5,2),
    write_speed DECIMAL(5,2),
    id_type int,
    id_supplier int,
    FOREIGN KEY (id_type) REFERENCES MATERIAL_TYPE(id_type),
    FOREIGN KEY (id_supplier) REFERENCES SUPPLIER(id_supplier)
);

-- Tabla de materiales físicos (especializada)
CREATE TABLE MATERIAL_PHYSICAL (
    id_material INT PRIMARY KEY,
    model VARCHAR(255),
    brand VARCHAR(100),
    resolution VARCHAR(50),
    size VARCHAR(50),
    design VARCHAR(100),
    material_type VARCHAR(50),
    sensitivity VARCHAR(50),
    connectivity VARCHAR(50),
    id_type int,
    id_supplier int,
    FOREIGN KEY (id_type) REFERENCES MATERIAL_TYPE(id_type),
    FOREIGN KEY (id_supplier) REFERENCES SUPPLIER(id_supplier)
);

-- Tabla de materiales componentes (especializada)
CREATE TABLE MATERIAL_COMPONENT (
    id_material INT PRIMARY KEY,
    model VARCHAR(255),
    brand VARCHAR(100),
    chipset VARCHAR(100),
    form_factor VARCHAR(50),
    socket_type VARCHAR(50),
    RAM_slots INT,
    max_RAM DECIMAL(5,2),
    expansion_slots INT,
    capacity DECIMAL(5,2),
    voltage DECIMAL(5,2),
    quantity INT,
    audio_channels INT,
    id_type int,
    id_supplier int,
    FOREIGN KEY (id_type) REFERENCES MATERIAL_TYPE(id_type),
    FOREIGN KEY (id_supplier) REFERENCES SUPPLIER(id_supplier)
);

alter table MATERIAL_COMPONENT CHANGE socket_type type VARCHAR(50)

-- Tabla de materiales (general)
CREATE TABLE RECEIVED_MATERIAL (
    id_material INT PRIMARY KEY AUTO_INCREMENT,
    `description` VARCHAR(255),
    serial_number VARCHAR(255),
    id_supply INT,
    id_category INT,
    id_rotation INT,
    volume DECIMAL(5,2),
    id_material_hardware INT,
    id_material_component INT,
    id_material_physical INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_supply) REFERENCES SUPPLY(id_supply),
    FOREIGN KEY (id_rotation) REFERENCES ROTATION(id_rotation),
    FOREIGN KEY (id_category) REFERENCES CATEGORY(id_category),
    FOREIGN KEY (id_material_hardware) REFERENCES MATERIAL_HARDWARE(id_material),
    FOREIGN KEY (id_material_component) REFERENCES MATERIAL_COMPONENT(id_material),
    FOREIGN KEY (id_material_physical) REFERENCES MATERIAL_PHYSICAL(id_material)
);


CREATE TABLE MATERIAL_LINK (
    id_supply INT PRIMARY KEY,
    id_material_hardware INT,
    id_material_component INT,
    id_material_physical INT,
    FOREIGN KEY (id_supply) REFERENCES SUPPLY(id_supply),
    FOREIGN KEY (id_material_hardware) REFERENCES MATERIAL_HARDWARE(id_material),
    FOREIGN KEY (id_material_component) REFERENCES MATERIAL_COMPONENT(id_material),
    FOREIGN KEY (id_material_physical) REFERENCES MATERIAL_PHYSICAL(id_material)
);


-- Tabla de almacenes
CREATE TABLE WAREHOUSE (
    id_warehouse INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `location` VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Tabla de subalmacenes
CREATE TABLE SUB_WAREHOUSE (
    id_sub_warehouse INT PRIMARY KEY AUTO_INCREMENT,
    `location` VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    id_warehouse INT,
    id_category INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_warehouse) REFERENCES WAREHOUSE(id_warehouse),
    FOREIGN KEY (id_category) REFERENCES CATEGORY(id_category)
);

-- Relación entre subalmacenes y materiales
CREATE TABLE SUB_WAREHOUSE_MATERIAL (
    id_sub_warehouse INT,
    id_material INT,
    quantity INT NOT NULL,
    PRIMARY KEY (id_sub_warehouse, id_material),
    FOREIGN KEY (id_sub_warehouse) REFERENCES SUB_WAREHOUSE(id_sub_warehouse),
    FOREIGN KEY (id_material) REFERENCES RECEIVED_MATERIAL (id_material)
);

-- Tabla de transacciones
CREATE TABLE TRANSACTIONS (
    id_transaction INT PRIMARY KEY AUTO_INCREMENT,
    id_material INT,
    id_sub_warehouse INT,
    `type` ENUM('inbound', 'outbound') NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_material) REFERENCES RECEIVED_MATERIAL(id_material),
    FOREIGN KEY (id_sub_warehouse) REFERENCES SUB_WAREHOUSE(id_sub_warehouse)
);



-- Tabla de órdenes
CREATE TABLE ORDERS (
    id_order INT PRIMARY KEY AUTO_INCREMENT,
    id_status INT DEFAULT 1,
    supply_quantity INT ,
    confirmation INT DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_status) REFERENCES STATUS(id_status),
    FOREIGN KEY (confirmation) REFERENCES STATUS(id_status)
);




-- Tabla de roles
CREATE TABLE ROLE (
    id_role INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de usuarios
CREATE TABLE USER (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    id_role INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES ROLE(id_role)
);

/* no se usan de momento


-- Tabla de condiciones
CREATE TABLE `CONDITION` (
    id_condition INT PRIMARY KEY AUTO_INCREMENT,
    `description` VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de equipos
CREATE TABLE EQUIPMENT (
    id_equipment INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `type` VARCHAR(50),
    id_condition INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_condition) REFERENCES `CONDITION`(id_condition)
);

-- Tabla de mantenimiento
CREATE TABLE MAINTENANCE (
    id_maintenance INT PRIMARY KEY AUTO_INCREMENT,
    `description` VARCHAR(255),
    maintenance_date DATE NOT NULL,
    id_equipment INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_equipment) REFERENCES EQUIPMENT(id_equipment)
);
*/
