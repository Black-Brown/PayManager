// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.password-toggle');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.innerHTML = '<i class="fa-solid fa-eye"></i>';
    } else {
        passwordInput.type = 'password';
        toggleIcon.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
    }
}

// Mostrar alerta temporal (solo para mensajes del cliente)
function showAlert(message, type = 'error') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${type} show`;
    alertDiv.textContent = message;
    document.querySelector('.login-container').prepend(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Forgot password placeholder
document.addEventListener('DOMContentLoaded', () => {
    const forgot = document.querySelector('.forgot-password');
    if (forgot) {
        forgot.addEventListener('click', e => {
            e.preventDefault();
            showAlert('Funcionalidad de recuperación de contraseña en desarrollo.');
        });
    }
});

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function setFieldError(id, message) {
    const field = document.getElementById(id);
    const errorDiv = document.getElementById(id + 'Error');
    if (field) field.classList.add('error');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.add('show');
    }
}

function clearErrors() {
    document.querySelectorAll('.error-message').forEach(e => {
        e.classList.remove('show');
        e.textContent = '';
    });
    document.querySelectorAll('.form-input.error').forEach(i => i.classList.remove('error'));
}

// Form submit handling: rely on backend to return flash messages (renderWithFlash)
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    const button = document.getElementById('registerButton');

    if (!form) return;

    form.addEventListener('submit', function(e) {
        clearErrors();

        const name = document.getElementById('name').value || '';
        const email = document.getElementById('email').value || '';
        const password = document.getElementById('password').value || '';
        const passwordConfirm = document.getElementById('password_confirm').value || '';

        let ok = true;

        if (!name.trim()) {
            setFieldError('name', 'El nombre es requerido');
            ok = false;
        }

        if (!email.trim()) {
            setFieldError('email', 'El correo es requerido');
            ok = false;
        } else if (!validateEmail(email)) {
            setFieldError('email', 'Ingrese un correo válido');
            ok = false;
        }

        if (!password) {
            setFieldError('password', 'La contraseña es requerida');
            ok = false;
        } else if (password.length < 6) {
            setFieldError('password', 'La contraseña debe tener al menos 6 caracteres');
            ok = false;
        }

        if (password !== passwordConfirm) {
            setFieldError('password_confirm', 'Las contraseñas no coinciden');
            ok = false;
        }

        if (!ok) {
            e.preventDefault(); // prevent submit, show client-side errors
            return;
        }

        // If ok, allow native form submit so backend (AuthController) handles creation, flashes and redirect.
        // Optionally you can add disable button UX:
        if (button) {
            button.disabled = true;
            button.textContent = 'Creando...';
        }
    });

    // Remove error state while typing
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', () => {
            if (input.classList.contains('error')) {
                input.classList.remove('error');
                const err = document.getElementById(input.id + 'Error');
                if (err) err.classList.remove('show');
            }
        });
    });
});
