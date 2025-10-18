<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use JosueIsOffline\Framework\Database\DB;
use App\Models\User;
use App\Models\Role;

class UserController extends AbstractController
{
    public function index(): Response
    {
        $users = User::all();

        foreach ($users as &$user) {
            $role = Role::find($user['role_id']);
            $user['role_name'] = $role ? $role->name : 'Sin rol';
        }

        return $this->render('users/index.html.twig', [
            'users' => $users,
            'page_title' => 'Lista de Usuarios',
            'active_menu' => 'users'
        ]);
    }

    public function createForm(): Response
    {
        $roles = Role::all();

        return $this->render('users/create.html.twig', [
            'roles' => $roles,
            'page_title' => 'Crear Usuario',
            'active_menu' => 'users'
        ]);
    }

    public function store(): Response
    {
        $data = $this->request->getAllPost();

        // Validación básica
        if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['role_id'])) {
            $roles = Role::all();
            return $this->renderWithFlash('users/create.html.twig', [
                'error' => 'Nombre, correo, contraseña y rol son obligatorios.',
                'old' => $data,
                'roles' => $roles
            ]);
        }

        // Validar que las contraseñas coincidan
        if ($data['password'] !== ($data['password_confirm'] ?? '')) {
            $roles = Role::all();
            return $this->renderWithFlash('users/create.html.twig', [
                'error' => 'Las contraseñas no coinciden.',
                'old' => $data,
                'roles' => $roles
            ]);
        }

        // Verificar si el correo ya está registrado
        $existingUser = DB::table('users')
        ->where('email', $data['email'])
        ->first();
        if ($existingUser) {
            $roles = Role::all();
            return $this->renderWithFlash('users/create.html.twig', [
                'error' => 'Ya existe un usuario con ese correo electrónico. Intenta con otro.',
                'old' => $data,
                'roles' => $roles
            ]);
        }

        // Encriptar y limpiar
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password_confirm']); 

        $user = User::create($data);

        return $this->success([
            'id' => $user->id
        ], 'Usuario creado correctamente.', 201, '/users');
    }


    public function editForm(int $id): Response
    {
        $user = User::find($id);
        if (!$user) {
            return $this->error('Usuario no encontrado', 404);
        }

        $roles = Role::all();
        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'roles' => $roles,
            'page_title' => 'Editar Usuario',
            'active_menu' => 'users'
        ]);
    }

    public function update(int $id): Response
    {
        $user = User::find($id);
        if (!$user) {
            return $this->error('Usuario no encontrado', 404);
        }

        $data = $this->request->getAllPost();

        if (empty($data['name']) || empty($data['email']) || empty($data['role_id'])) {
            $roles = Role::all();
            return $this->renderWithFlash('users/edit.html.twig', [
                'error' => 'Nombre, correo y rol son obligatorios.',
                'user' => $user,
                'roles' => $roles
            ]);
        }

        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        unset($data['password_confirm']);
        $user->update($data);

        return $this->success([], 'Usuario actualizado correctamente.', 200, '/users');
    }

    public function destroy(int $id): Response
    {
        $user = User::find($id);
        if (!$user) {
            return $this->error('Usuario no encontrado', 404);
        }

        $user->delete($id);
        return $this->success([], 'Usuario eliminado.', 200, '/users');
    }
}
