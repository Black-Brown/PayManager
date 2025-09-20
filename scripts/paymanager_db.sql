CREATE DATABASE paymanager_db;
USE paymanager_db;

-- Tabla de roles de usuario
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de grados académicos
CREATE TABLE grados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    nivel VARCHAR(20) NOT NULL, -- Reemplazo de ENUM
    orden INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de campamentos
CREATE TABLE campamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    costo DECIMAL(10, 2) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de actividades extraescolares
CREATE TABLE actividades_extra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    costo_mensual DECIMAL(10, 2) NOT NULL,
    instructor VARCHAR(100),
    horario TEXT,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de usuarios del sistema
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT
);

-- Tabla de alumnos
CREATE TABLE alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ministerio VARCHAR(50) UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    genero VARCHAR(1), -- Reemplazo de ENUM
    grado_id INT NOT NULL,
    nombre_representante VARCHAR(100),
    telefono_representante VARCHAR(20),
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (grado_id) REFERENCES grados(id) ON DELETE RESTRICT
);

-- Tabla de conceptos de pago
CREATE TABLE conceptos_pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL, -- Reemplazo de ENUM
    monto DECIMAL(10, 2) NOT NULL,
    aplica_descuento TINYINT(1) DEFAULT 0,
    periodicidad VARCHAR(20) DEFAULT 'Único', -- Reemplazo de ENUM
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de pagos registrados
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    concepto_id INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_pago DATE NOT NULL,
    mes_correspondiente DATE NOT NULL,
    metodo_pago VARCHAR(20) DEFAULT 'Efectivo', -- Reemplazo de ENUM
    referencia VARCHAR(100),
    observaciones TEXT,
    usuario_registro_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
    FOREIGN KEY (concepto_id) REFERENCES conceptos_pago(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_registro_id) REFERENCES usuarios(id) ON DELETE RESTRICT
);

-- Tabla de descuentos aplicados
CREATE TABLE descuentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pago_id INT NOT NULL,
    monto_descuento DECIMAL(10, 2) NOT NULL,
    motivo TEXT,
    usuario_aplico_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pago_id) REFERENCES pagos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_aplico_id) REFERENCES usuarios(id) ON DELETE RESTRICT
);

-- Tabla de inscripciones a actividades extraescolares
CREATE TABLE alumno_actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    actividad_id INT NOT NULL,
    fecha_inscripcion DATE NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
    FOREIGN KEY (actividad_id) REFERENCES actividades_extra(id) ON DELETE CASCADE,
    UNIQUE KEY unique_alumno_actividad (alumno_id, actividad_id)
);

-- Tabla de inscripciones a campamentos
CREATE TABLE alumno_campamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    campamento_id INT NOT NULL,
    fecha_inscripcion DATE NOT NULL,
    pagado TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
    FOREIGN KEY (campamento_id) REFERENCES campamentos(id) ON DELETE CASCADE
);

-- Índices para rendimiento
CREATE INDEX idx_alumnos_grado ON alumnos(grado_id);
CREATE INDEX idx_alumnos_activo ON alumnos(activo);
CREATE INDEX idx_pagos_alumno ON pagos(alumno_id);
CREATE INDEX idx_pagos_mes ON pagos(mes_correspondiente);
CREATE INDEX idx_pagos_concepto ON pagos(concepto_id);
