-- =======================================================
-- 1. ROLES Y PERMISOS (Acceso por Módulos / Sidebar)
-- =======================================================

-- 1. Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
);

-- 2. Permisos (Módulos del Sistema)
CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,         -- 'DASHBOARD','ROLES','USUARIOS','TIPOS_PLAZA','PARQUEO','TARIFAS'.
    descripcion VARCHAR(255)
);

-- 3. Asignación de Permisos a Roles
CREATE TABLE permisos_roles (
    rol_nombre VARCHAR(50) NOT NULL,
    permiso_nombre VARCHAR(50) NOT NULL,
    
    PRIMARY KEY (rol_nombre, permiso_nombre),
    FOREIGN KEY (rol_nombre) REFERENCES roles(nombre) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (permiso_nombre) REFERENCES permisos(nombre) ON DELETE CASCADE ON UPDATE CASCADE
);


-- =======================================================
-- 2. USUARIOS DEL SISTEMA E HISTORIAL
-- =======================================================

-- 4. Usuarios (Empleados del Parqueo)
CREATE TABLE usuarios (
    documento_id VARCHAR(15) PRIMARY KEY,
    rol_name VARCHAR(50) NOT NULL DEFAULT 'OPERADOR',
    username VARCHAR(20) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(15),
    password_hash VARCHAR(100) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    
    FOREIGN KEY (rol_name) REFERENCES roles(nombre) ON UPDATE CASCADE
);

-- 5. Historial de IPs (Auditoría de Accesos)
CREATE TABLE iphys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_username VARCHAR(20) NOT NULL,
    rol_name VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    fecha_acceso DATETIME DEFAULT CURRENT_TIMESTAMP(),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    
    FOREIGN KEY (usuario_username) REFERENCES usuarios(username) ON UPDATE CASCADE,
    FOREIGN KEY (rol_name) REFERENCES roles(nombre) ON UPDATE CASCADE
);


-- =======================================================
-- 3. GESTIÓN DE ESPACIOS Y TARIFAS
-- =======================================================

-- 6. Tipos / Categorías de Plaza de Parqueo
CREATE TABLE tipo_plaza (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,       -- ej: 'AUTO', 'MOTO', 'CAMIONETA'
    precio_hora DECIMAL(8, 2) NOT NULL,
    capacidad_total INT NOT NULL,
    espacios_ocupados INT NOT NULL DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
);


-- =======================================================
-- 4. OPERACIONES DE PARQUEO Y COBROS
-- =======================================================

-- 7. Registro de Ingreso / Salida de Vehículos (Ticket)
CREATE TABLE parking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) NOT NULL UNIQUE,        -- Código único generado (ej: 'PRK-8F2K')
    placa VARCHAR(15) NOT NULL,
    tipo_plaza_nombre VARCHAR(50) NOT NULL,
    usuario_ingreso_username VARCHAR(20) NOT NULL,
    fecha_hora_ingreso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    fecha_hora_salida DATETIME NULL,
    estado ENUM('ACTIVO', 'FINALIZADO') NOT NULL DEFAULT 'ACTIVO',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),

    FOREIGN KEY (tipo_plaza_nombre) REFERENCES tipo_plaza(nombre) ON UPDATE CASCADE,
    FOREIGN KEY (usuario_ingreso_username) REFERENCES usuarios(username) ON UPDATE CASCADE,
    INDEX idx_parking_estado (estado),
    INDEX idx_parking_placa (placa)
);

-- 8. Liquidación y Cobro de Tarifa
CREATE TABLE tarifas_cobros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parking_codigo VARCHAR(30) NOT NULL UNIQUE,
    usuario_cobro_username VARCHAR(20) NOT NULL,
    minutos_totales INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    descuento DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    monto_total DECIMAL(10, 2) NOT NULL,
    fecha_hora_pago DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),

    FOREIGN KEY (parking_codigo) REFERENCES parking(codigo) ON UPDATE CASCADE,
    FOREIGN KEY (usuario_cobro_username) REFERENCES usuarios(username) ON UPDATE CASCADE,
    INDEX idx_cobros_fecha (fecha_hora_pago)
);