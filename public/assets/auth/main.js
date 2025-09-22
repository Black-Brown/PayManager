// Referencias a elementos del DOM
const loginForm = document.getElementById('loginForm');
const loginButton = document.getElementById('loginButton');
const loadingSpinner = document.getElementById('loadingSpinner');
const buttonText = document.getElementById('buttonText');
const alertContainer = document.getElementById('alertContainer');
const alertMessage = document.getElementById('alertMessage');

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}

// Mostrar alerta
function showAlert(message, type = 'error') {
    alertMessage.textContent = message;
    alertContainer.className = `alert ${type} show`;
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideAlert();
    }, 5000);
}

// Ocultar alerta
function hideAlert() {
    alertContainer.classList.remove('show');
}

// Validar campo
function validateField(fieldId, value, errorMessage) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.getElementById(fieldId + 'Error');
    
    if (!value.trim()) {
        field.classList.add('error');
        errorDiv.textContent = errorMessage;
        errorDiv.classList.add('show');
        return false;
    } else {
        field.classList.remove('error');
        errorDiv.classList.remove('show');
        return true;
    }
}

// Validar email
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Limpiar errores
function clearErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    const errorInputs = document.querySelectorAll('.form-input.error');
    
    errorMessages.forEach(error => error.classList.remove('show'));
    errorInputs.forEach(input => input.classList.remove('error'));
}

// Estado de carga
function setLoading(loading) {
    if (loading) {
        loginButton.disabled = true;
        loadingSpinner.style.display = 'inline-block';
        buttonText.textContent = 'Iniciando Sesión...';
    } else {
        loginButton.disabled = false;
        loadingSpinner.style.display = 'none';
        buttonText.textContent = 'Iniciar Sesión';
    }
}

// Manejar envío del formulario
loginForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Ocultar alertas previas
    hideAlert();
    clearErrors();
    
    // Obtener valores
    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // Validaciones
    let isValid = true;
    
    if (!validateField('nombre', nombre, 'El nombre de usuario es requerido')) {
        isValid = false;
    }
    
    if (!validateField('email', email, 'El correo electrónico es requerido')) {
        isValid = false;
    } else if (!validateEmail(email)) {
        const emailField = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        emailField.classList.add('error');
        emailError.textContent = 'Por favor ingrese un correo electrónico válido';
        emailError.classList.add('show');
        isValid = false;
    }
    
    if (!validateField('password', password, 'La contraseña es requerida')) {
        isValid = false;
    }
    
    if (!isValid) {
        return;
    }
    
    // Iniciar estado de carga
    setLoading(true);
    
    try {
        // Preparar datos para envío
        const formData = new FormData();
        formData.append('action', 'login');
        formData.append('nombre', nombre);
        formData.append('email', email);
        formData.append('password', password);
        
        console.log('Enviando datos:', {
            action: 'login',
            nombre: nombre,
            email: email,
            password: '***'
        });
        
        // CORRECCIÓN: Ruta correcta del controlador web
        const response = await fetch('/app/controllers/web/authController.php', {
            method: 'POST',
            body: formData
        });
        
        console.log('Response status:', response.status);
        
        // Obtener respuesta como texto primero
        const responseText = await response.text();
        console.log('Raw response:', responseText);
        
        // Intentar parsear como JSON
        let data;
        try {
            data = JSON.parse(responseText);
            console.log('Parsed data:', data);
        } catch (parseError) {
            console.error('Error parsing JSON:', parseError);
            showAlert('Error: Respuesta inválida del servidor. Revisa la consola para más detalles.', 'error');
            return;
        }
        
        if (data.success) {
            showAlert('¡Login exitoso! Redirigiendo...', 'success');
            
            // Guardar remember token si está marcado
            if (document.getElementById('remember').checked) {
                localStorage.setItem('rememberUser', JSON.stringify({
                    nombre: nombre,
                    email: email
                }));
            }
            
            // Redireccionar al dashboard
            setTimeout(() => {
                window.location.href = '/dashboard.php';
            }, 1500);
            
        } else {
            showAlert(data.message || 'Error al iniciar sesión', 'error');
        }
        
    } catch (error) {
        console.error('Error completo:', error);
        showAlert('Error de conexión. Por favor intente nuevamente.', 'error');
    } finally {
        setLoading(false);
    }
});

// Cargar datos recordados al cargar la página
window.addEventListener('load', function() {
    const rememberedUser = localStorage.getItem('rememberUser');
    if (rememberedUser) {
        try {
            const userData = JSON.parse(rememberedUser);
            document.getElementById('nombre').value = userData.nombre || '';
            document.getElementById('email').value = userData.email || '';
            document.getElementById('remember').checked = true;
        } catch (error) {
            console.error('Error loading remembered user:', error);
        }
    }
});

// Limpiar errores al escribir
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            this.classList.remove('error');
            const errorDiv = document.getElementById(this.id + 'Error');
            if (errorDiv) {
                errorDiv.classList.remove('show');
            }
        }
        hideAlert();
    });
});

// Manejar forgot password
document.querySelector('.forgot-password').addEventListener('click', function(e) {
    e.preventDefault();
    showAlert('Funcionalidad de recuperación de contraseña en desarrollo.', 'error');
});