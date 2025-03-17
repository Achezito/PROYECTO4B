use inventario;

-- Crear roles

INSERT INTO ROLE (`name`) VALUES ('Admin'), ('Usuario');

-- Crear usuario de prueba (Contraseña: 123456)
INSERT INTO USER (`name`, password_hash, email, id_role) 
VALUES ('Admin', SHA1('123456'), 'admin@example.com', 1);


SELECT 
    u.id_user,
    u.name,
    u.password_hash,
    u.email,
    r.name as role_name
    FROM user as u
    INNER JOIN `role` as r on r.id_role = u.id_role
    WHERE u.name = 'Admin';