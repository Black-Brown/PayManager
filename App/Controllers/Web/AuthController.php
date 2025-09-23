<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use JosueIsOffline\Framework\Auth\AuthService;
use App\Models\User;

class AuthController extends AbstractController
{
    private AuthService $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new AuthService();
    }

    public function loginForm(): Response
    {
        if ($this->auth->check()) {
            return $this->redirect('/');
        }

        return $this->render('login.html.twig');
    }

    public function login(): Response
    {
        $data = $this->request->getAllPost();

        if (empty($data['email']) || empty($data['password'])) {
            return $this->renderWithFlash('login.html.twig', [
                'error' => 'Email y contraseña son requeridos.',
                'old' => $data
            ]);
        }

        if ($this->auth->attempt($data['email'], $data['password'])) {
            $user = $this->auth->user(); // Devuelve el usuario logueado
            $this->auth->login($user);    // Guarda en sesión interna del framework

            if (!empty($data['remember'])) {
                $this->auth->setRememberToken();
            }

            $redirectTo = $_SESSION['intended_url'] ?? '/';
            unset($_SESSION['intended_url']);

            return $this->success([], 'Bienvenido de vuelta!', 200, $redirectTo);
        }

        return $this->renderWithFlash('login.html.twig', [
            'error' => 'Credenciales incorrectas.',
            'old' => ['email' => $data['email']]
        ]);
    }

    public function registerForm(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register(): Response
    {
        $data = $this->request->getAllPost();

        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return $this->renderWithFlash('register.html.twig', [
                'error' => 'Todos los campos son obligatorios.',
                'old' => $data
            ]);
        }

        $existing = User::where('email', $data['email']);
        if (!empty($existing)) {
            return $this->renderWithFlash('register.html.twig', [
                'error' => 'El correo ya está registrado.',
                'old' => $data
            ]);
        }

        // Crear el usuario
        $userModel = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role_id' => 1,
            'active' => 1
        ]);

        // Loguear automáticamente usando AuthService
        $this->auth->login($userModel);

        return $this->success([], 'Registro exitoso. Bienvenido!', 201, '/');
    }

    public function logout(): Response
    {
        $this->auth->logout(); // Limpia sesión del framework
        return $this->success([], 'Sesión cerrada correctamente.', 200, '/login');
    }
}
