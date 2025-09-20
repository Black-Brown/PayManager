-- Insertar roles
INSERT INTO roles (nombre, descripcion) VALUES
('admin', 'Administrador del sistema'),
('usuario', 'Usuario estándar');

-- Insertar grados académicos
INSERT INTO grados (nombre, nivel, orden) VALUES
('Inicial A', 'Inicial', 1),
('Primaria 1', 'Primaria', 2);

-- Insertar usuarios
INSERT INTO usuarios (nombre, email, password, rol_id, activo, remember_token) VALUES
('Juan Pérez', 'juan@example.com', '$2y$10$EjemploHashJuan', 1, 1, NULL),
('Ana Gómez', 'ana@example.com', '$2y$10$EjemploHashAna', 2, 1, NULL);

-- Insertar alumnos
INSERT INTO alumnos (id_ministerio, nombre, apellido, fecha_nacimiento, genero, grado_id, nombre_representante, telefono_representante, activo) VALUES
('MIN001', 'Carlos', 'Ramírez', '2015-06-10', 'M', 1, 'Luis Ramírez', '8091234567', 1);

-- Insertar conceptos de pago
INSERT INTO conceptos_pago (nombre, tipo, monto, aplica_descuento, periodicidad, activo) VALUES
('Inscripción 2025', 'Inscripción', 1500.00, 1, 'Único', 1),
('Mensualidad Septiembre', 'Mensualidad', 1000.00, 1, 'Mensual', 1);

-- Insertar pagos
INSERT INTO pagos (alumno_id, concepto_id, monto, fecha_pago, mes_correspondiente, metodo_pago, referencia, observaciones, usuario_registro_id) VALUES
(1, 1, 1500.00, CURDATE(), '2025-09-01', 'Efectivo', 'REC001', 'Pago completo', 1);
