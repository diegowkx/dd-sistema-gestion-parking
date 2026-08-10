-- =======================================================
-- 1. ROLES Y PERMISOS
-- =======================================================

-- 1. Roles
INSERT INTO roles (nombre, activo) VALUES
('ADMIN', 1),
('SUPERVISOR', 1),
('OPERADOR', 1);

-- 2. Permisos
INSERT INTO permisos (nombre, descripcion) VALUES
('DASHBOARD', 'Acceso a estadísticas y métricas generales'),
('ROLES', 'Gestión de roles y asignación de permisos'),
('USUARIOS', 'Administración de usuarios del sistema'),
('TIPOS_PLAZA', 'Configuración de tipos de vehículos y tarifas base'),
('PARQUEO', 'Control de entradas y salidas de vehículos'),
('TARIFAS', 'Gestión y cobro de tickets de estacionamiento');

-- 3. Permisos asignados a Roles
INSERT INTO permisos_roles (rol_nombre, permiso_nombre) VALUES
-- ADMIN tiene acceso total
('ADMIN', 'DASHBOARD'),
('ADMIN', 'ROLES'),
('ADMIN', 'USUARIOS'),
('ADMIN', 'TIPOS_PLAZA'),
('ADMIN', 'PARQUEO'),
('ADMIN', 'TARIFAS'),
-- SUPERVISOR gestiona operaciones y reportes
('SUPERVISOR', 'DASHBOARD'),
('SUPERVISOR', 'PARQUEO'),
('SUPERVISOR', 'TARIFAS'),
-- OPERADOR solo registra entradas, salidas y cobros
('OPERADOR', 'PARQUEO'),
('OPERADOR', 'TARIFAS');


-- =======================================================
-- 2. USUARIOS DEL SISTEMA E HISTORIAL
-- =======================================================

-- 4. Usuarios
-- Contraseña para todos los usuarios: "clave"
-- Hash: $2y$10$VNgHI7pLLJc8otgTE/MMX.vLCVejzBT4mWskGyQ5AhA5vj2LSTZHG
INSERT INTO usuarios (documento_id, rol_name, username, nombres, apellidos, correo, telefono, password_hash, activo) VALUES
('70123456', 'ADMIN', 'admin_juan', 'Juan', 'Pérez Gómez', 'juan.perez@parqueo.com', '987654321', '$2y$10$VNgHI7pLLJc8otgTE/MMX.vLCVejzBT4mWskGyQ5AhA5vj2LSTZHG', 1),
('70987654', 'OPERADOR', 'op_carlos', 'Carlos', 'López Ruiz', 'carlos.lopez@parqueo.com', '912345678', '$2y$10$VNgHI7pLLJc8otgTE/MMX.vLCVejzBT4mWskGyQ5AhA5vj2LSTZHG', 1),
('70555444', 'SUPERVISOR', 'sup_maria', 'María', 'Rodríguez Silva', 'maria.rodriguez@parqueo.com', '955511223', '$2y$10$VNgHI7pLLJc8otgTE/MMX.vLCVejzBT4mWskGyQ5AhA5vj2LSTZHG', 1);

-- 5. Historial de IPs (Auditoría)
INSERT INTO iphys (usuario_username, rol_name, ip_address, fecha_acceso) VALUES
('admin_juan', 'ADMIN', '192.168.1.10', '2026-08-10 08:00:00'),
('op_carlos', 'OPERADOR', '192.168.1.15', '2026-08-10 08:15:00'),
('sup_maria', 'SUPERVISOR', '192.168.1.12', '2026-08-10 09:00:00');


-- =======================================================
-- 3. GESTIÓN DE ESPACIOS Y TARIFAS
-- =======================================================

-- 6. Tipos de Plaza
INSERT INTO tipo_plaza (nombre, precio_hora, capacidad_total, espacios_ocupados, activo) VALUES
('MOTO', 2.50, 20, 1, 1),
('AUTO', 5.00, 50, 2, 1),
('CAMIONETA', 8.00, 15, 0, 1);


-- =======================================================
-- 4. OPERACIONES DE PARQUEO Y COBROS
-- =======================================================

-- 7. Registro de Tickets (Parking)
-- Se incluyen registros FINALIZADOS (ya salieron y pagaron) y ACTIVOS (aún en parqueo)
INSERT INTO parking (codigo, placa, tipo_plaza_nombre, usuario_ingreso_username, fecha_hora_ingreso, fecha_hora_salida, estado) VALUES
-- Registros finalizados
('PRK-8F2K', 'ABC-123', 'AUTO', 'op_carlos', '2026-08-10 08:30:00', '2026-08-10 10:30:00', 'FINALIZADO'),
('PRK-9M3L', 'XYZ-789', 'MOTO', 'op_carlos', '2026-08-10 09:00:00', '2026-08-10 12:00:00', 'FINALIZADO'),
('PRK-3P4Q', 'TRK-456', 'CAMIONETA', 'admin_juan', '2026-08-10 10:00:00', '2026-08-10 11:30:00', 'FINALIZADO'),

-- Registros activos (Vehículos actualmente dentro)
('PRK-5X1Y', 'BCD-234', 'AUTO', 'op_carlos', '2026-08-10 11:00:00', NULL, 'ACTIVO'),
('PRK-7Z2A', 'EFG-567', 'AUTO', 'op_carlos', '2026-08-10 12:15:00', NULL, 'ACTIVO'),
('PRK-1B8C', 'MOT-999', 'MOTO', 'op_carlos', '2026-08-10 13:00:00', NULL, 'ACTIVO');

-- 8. Liquidación y Cobro (Solo para los que tienen estado FINALIZADO)
INSERT INTO tarifas_cobros (parking_codigo, usuario_cobro_username, minutos_totales, subtotal, descuento, monto_total, fecha_hora_pago) VALUES
('PRK-8F2K', 'op_carlos', 120, 10.00, 0.00, 10.00, '2026-08-10 10:30:00'),
('PRK-9M3L', 'op_carlos', 180, 7.50, 0.50, 7.00, '2026-08-10 12:00:00'),
('PRK-3P4Q', 'sup_maria', 90, 12.00, 0.00, 12.00, '2026-08-10 11:30:00');