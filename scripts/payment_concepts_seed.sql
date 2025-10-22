-- Script para insertar conceptos de pago de ejemplo
-- Inscripción y Reinscripción

-- Conceptos de Inscripción
INSERT INTO payment_concepts (name, type, amount, discount_applicable, frequency, active) VALUES
('Inscripción Preescolar', 'Enrollment', 150.00, 1, 'One-time', 1),
('Inscripción Primaria', 'Enrollment', 200.00, 1, 'One-time', 1),
('Inscripción Secundaria', 'Enrollment', 250.00, 1, 'One-time', 1),
('Inscripción Bachillerato', 'Enrollment', 300.00, 1, 'One-time', 1);

-- Conceptos de Reinscripción
INSERT INTO payment_concepts (name, type, amount, discount_applicable, frequency, active) VALUES
('Reinscripción Preescolar', 'Reenrollment', 100.00, 1, 'One-time', 1),
('Reinscripción Primaria', 'Reenrollment', 120.00, 1, 'One-time', 1),
('Reinscripción Secundaria', 'Reenrollment', 150.00, 1, 'One-time', 1),
('Reinscripción Bachillerato', 'Reenrollment', 180.00, 1, 'One-time', 1);

-- Conceptos adicionales
INSERT INTO payment_concepts (name, type, amount, discount_applicable, frequency, active) VALUES
('Matrícula Anual', 'Enrollment', 500.00, 1, 'One-time', 1),
('Seguro Escolar', 'Enrollment', 50.00, 0, 'One-time', 1),
('Material Didáctico', 'Enrollment', 80.00, 1, 'One-time', 1),
('Uniforme Escolar', 'Enrollment', 120.00, 1, 'One-time', 1);
