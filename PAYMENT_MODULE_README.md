# Módulo de Registro de Pagos de Inscripción/Reinscripción

Este módulo permite gestionar los pagos de inscripción y reinscripción de estudiantes en el sistema PayManager.

## Características Principales

### 🎯 Funcionalidades
- **Registro de Pagos**: Crear nuevos pagos de inscripción y reinscripción
- **Gestión de Conceptos**: Diferentes tipos de conceptos de pago (Inscripción, Reinscripción)
- **Aplicación de Descuentos**: Descuentos aplicables según el concepto
- **Múltiples Métodos de Pago**: Efectivo, Transferencia, Cheque, Tarjeta
- **Reportes**: Generación de reportes por período
- **Historial Completo**: Seguimiento de todos los pagos realizados

### 📊 Información del Pago
- Estudiante asociado
- Concepto de pago (Inscripción/Reinscripción)
- Monto original y final (después de descuentos)
- Fecha de pago y mes correspondiente
- Método de pago utilizado
- Referencia/comprobante
- Notas adicionales
- Usuario que registró el pago

## Estructura de Archivos

```
App/
├── Models/
│   ├── Payment.php              # Modelo principal de pagos
│   ├── PaymentConcept.php       # Modelo de conceptos de pago
│   └── Discount.php             # Modelo de descuentos
├── Controllers/Web/
│   └── PaymentController.php    # Controlador principal
routes/Web/
└── payment.php                   # Rutas del módulo
views/pages/payments/
├── index.html.twig              # Lista de pagos
├── create.html.twig             # Formulario de registro
├── show.html.twig               # Detalle del pago
├── edit.html.twig               # Edición de pago
└── report.html.twig             # Reportes
scripts/
└── payment_concepts_seed.sql    # Conceptos de ejemplo
```

## Rutas Disponibles

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/payments` | Lista todos los pagos |
| GET | `/payments/create` | Formulario de nuevo pago |
| POST | `/payments` | Crear nuevo pago |
| GET | `/payments/{id}` | Ver detalle del pago |
| GET | `/payments/{id}/edit` | Formulario de edición |
| POST | `/payments/{id}/update` | Actualizar pago |
| GET | `/payments/{id}/delete` | Eliminar pago |
| POST | `/payments/{id}/discount` | Aplicar descuento |
| GET | `/payments/student/{id}` | Pagos por estudiante |
| GET | `/payments/report` | Reporte de pagos |

## Base de Datos

### Tablas Principales

#### `payments`
- `id`: Identificador único
- `student_id`: ID del estudiante (FK)
- `concept_id`: ID del concepto de pago (FK)
- `amount`: Monto del pago
- `payment_date`: Fecha del pago
- `corresponding_month`: Mes correspondiente
- `payment_method`: Método de pago
- `reference`: Referencia/comprobante
- `notes`: Notas adicionales
- `registered_by_user_id`: Usuario que registró (FK)

#### `payment_concepts`
- `id`: Identificador único
- `name`: Nombre del concepto
- `type`: Tipo (Enrollment/Reenrollment)
- `amount`: Monto base
- `discount_applicable`: Si permite descuentos
- `frequency`: Frecuencia del pago
- `active`: Estado activo

#### `discounts`
- `id`: Identificador único
- `payment_id`: ID del pago (FK)
- `discount_amount`: Monto del descuento
- `reason`: Razón del descuento
- `applied_by_user_id`: Usuario que aplicó (FK)

## Uso del Sistema

### 1. Registrar un Pago
1. Ir a "Registro de Pagos" en el menú
2. Hacer clic en "Registrar Pago"
3. Seleccionar el estudiante
4. Elegir el tipo de concepto (Inscripción/Reinscripción)
5. Seleccionar el concepto específico
6. Completar los datos del pago
7. Guardar

### 2. Aplicar Descuentos
1. Ver el detalle del pago
2. Si el concepto permite descuentos, hacer clic en "Aplicar Descuento"
3. Ingresar el monto y la razón del descuento
4. Confirmar

### 3. Generar Reportes
1. Ir a "Reportes" en el menú de pagos
2. Seleccionar el período deseado
3. Ver el resumen y detalles de pagos

## Conceptos de Pago Predefinidos

El sistema incluye conceptos de ejemplo para:
- **Inscripción**: Preescolar, Primaria, Secundaria, Bachillerato
- **Reinscripción**: Preescolar, Primaria, Secundaria, Bachillerato
- **Adicionales**: Matrícula Anual, Seguro Escolar, Material Didáctico, Uniforme Escolar

## Seguridad

- Todas las rutas requieren autenticación (`auth` middleware)
- Validación de datos en el servidor
- Prevención de descuentos que excedan el monto del pago
- Auditoría de quién registra cada pago

## Personalización

### Agregar Nuevos Conceptos
```sql
INSERT INTO payment_concepts (name, type, amount, discount_applicable, frequency, active) 
VALUES ('Nuevo Concepto', 'Enrollment', 100.00, 1, 'One-time', 1);
```

### Modificar Métodos de Pago
Editar el formulario en `views/pages/payments/create.html.twig` y `edit.html.twig`

## Soporte

Para dudas o problemas con el módulo de pagos, revisar:
1. Los logs del sistema
2. La estructura de la base de datos
3. Los permisos de usuario
4. La configuración del framework
