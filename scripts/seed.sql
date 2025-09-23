-- Insert roles
INSERT INTO roles (name, description) VALUES
('admin', 'System administrator'),
('user', 'Standard user');

-- Insert academic grades
INSERT INTO grades (name, level, grade_order) VALUES
('Initial A', 'Initial', 1),
('Primary 1', 'Primary', 2);

-- Insert users
INSERT INTO users (name, email, photo_url, password, role_id, active, remember_token) VALUES
('John Perez', 'john@example.com', 'https://example.com/photos/john.jpg', '$2y$10$ExampleHashJohn', 1, 1, NULL),
('Anna Gomez', 'anna@example.com', 'https://example.com/photos/anna.jpg', '$2y$10$ExampleHashAnna', 2, 1, NULL),

-- Insert students
INSERT INTO students (ministry_id, first_name, last_name, birth_date, gender, grade_id, guardian_name, guardian_phone, active) VALUES
('MIN001', 'Carlos', 'Ramirez', '2015-06-10', 'M', 1, 'Luis Ramirez', '8091234567', 1);

-- Insert payment concepts
INSERT INTO payment_concepts (name, type, amount, discount_applicable, frequency, active) VALUES
('Enrollment 2025', 'Enrollment', 1500.00, 1, 'One-time', 1),
('September Tuition', 'Tuition', 1000.00, 1, 'Monthly', 1);

-- Insert payments
INSERT INTO payments (student_id, concept_id, amount, payment_date, corresponding_month, payment_method, reference, notes, registered_by_user_id) VALUES
(1, 1, 1500.00, CURDATE(), '2025-09-01', 'Cash', 'REC001', 'Full payment', 1);
