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

    public function logout(): Response
    {
        $this->auth->logout(); // Limpia sesión del framework
        return $this->success([], 'Sesión cerrada correctamente.', 200, '/login');
    }
}
