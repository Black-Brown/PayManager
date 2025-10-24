-- Script de ejemplo para probar el sistema de descuentos
-- Este script muestra cómo insertar conceptos que permiten descuentos

-- Actualizar conceptos existentes para permitir descuentos
UPDATE payment_concepts 
SET discount_applicable = 1 
WHERE type IN ('Enrollment', 'Reenrollment');

-- Insertar algunos conceptos de ejemplo con descuentos permitidos
INSERT INTO payment_concepts (name, type, amount, discount_applicable, frequency, active) VALUES
('Inscripción con Descuento Familiar', 'Enrollment', 200.00, 1, 'One-time', 1),
('Reinscripción por Pago Anticipado', 'Reenrollment', 120.00, 1, 'One-time', 1),
('Descuento por Hermanos', 'Enrollment', 150.00, 1, 'One-time', 1);

-- Ejemplo de cómo aplicar un descuento (esto se haría desde la interfaz web)
-- INSERT INTO discounts (payment_id, discount_amount, reason, applied_by_user_id) VALUES
-- (1, 25.00, 'Descuento por pago anticipado', 1);

-- Consulta para ver pagos con descuentos
-- SELECT 
--     p.id,
--     p.amount,
--     COALESCE(SUM(d.discount_amount), 0) as total_discounts,
--     p.amount - COALESCE(SUM(d.discount_amount), 0) as final_amount
-- FROM payments p
-- LEFT JOIN discounts d ON p.id = d.payment_id
-- GROUP BY p.id, p.amount;
