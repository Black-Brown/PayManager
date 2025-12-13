-- Script para insertar conceptos de pago de Mensualidad
-- Mensualidades para diferentes niveles

INSERT INTO payment_concepts (name, type, amount, discount_applicable, frequency, active) VALUES
('Mensualidad Preescolar', 'Monthly', 1500.00, 1, 'Monthly', 1),
('Mensualidad Primaria', 'Monthly', 1800.00, 1, 'Monthly', 1),
('Mensualidad Secundaria', 'Monthly', 2200.00, 1, 'Monthly', 1),
('Mensualidad Bachillerato', 'Monthly', 2500.00, 1, 'Monthly', 1);
