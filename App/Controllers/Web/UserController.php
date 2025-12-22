<?php

namespace App\Controllers\Web;

use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;

class UserController extends AbstractController
{

  protected UserRepository $userRepo;
  protected RoleRepository $rolRepo;

  public function __construct()
  {
    parent::__construct();
    $this->userRepo = new UserRepository();
    $this->rolRepo = new RoleRepository();
  }

  public function index(): Response
  {
    $users = $this->userRepo->getAllWithRole();

    return $this->render('users/index.html.twig', [
      'users' => $users,
      'page_title' => 'Lista de Usuarios',
      'active_menu' => 'users'
    ]);
  }

  public function createForm(): Response
  {
    $roles = $this->rolRepo->getAll();
    return $this->render('users/create.html.twig', [
      'roles' => $roles,
      'page_title' => 'Crear Usuario',
      'active_menu' => 'users'
    ]);
  }

  public function store(): Response
  {
    $roles = $this->rolRepo->getAll();

    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $rolId = (int)($_POST['role_id']);
    $active = isset($_POST['active']) ? (int)$_POST['active'] : 1;


    if ($name === '' || $email === '' || $password === '' || $rolId === '') {
      return $this->renderWithFlash('users/create.html.twig', [
        'error' => "Todos los campos son obligatorios.",
        'old' => $_POST,
        'roles' => $roles
      ]);
    }

    if ($password !== $passwordConfirm ?? '') {
      return $this->renderWithFlash('users/create.html.twig', [
        'error' => "Las contraseñas no coinciden.",
        'old' => $_POST,
        'roles' => $roles
      ]);
    }

    $exitingUser = $this->userRepo->getByEmail($email);
    if ($exitingUser) {
      return $this->renderWithFlash('users/create.html.twig', [
        'error' => "Ya existe un usuario con este correo electronico, Intenta con otro.",
        'old' => $_POST,
        'roles' => $roles
      ]);
    }

    $this->userRepo->create([
      'name' => $name,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT),
      'role_id' => $rolId,
      'active' => $active
    ]);

    return $this->success([], 'Usuario creado', 200, '/users');
  }


  public function editForm(int $id): Response
  {
    $user = $this->userRepo->getById($id);
    if (!$user) {
      return $this->renderWithFlash('users/edit.html.twig', [
        'error' => 'Usuario no encontrado'
      ], 404);
    }

    $roles = $this->rolRepo->getAll();
    return $this->render('users/edit.html.twig', [
      'user' => $user,
      'roles' => $roles,
      'page_title' => 'Editar Usuario',
      'active_menu' => 'users'
    ]);
  }

  public function update(int $id): Response
  {
    $roles = $this->rolRepo->getAll();

    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $rolId = (int)($_POST['role_id']);
    $active = isset($_POST['active']) ? (int)$_POST['active'] : 1;

    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($name === '' || $email === '' || $rolId <= 0) {
      return $this->renderWithFlash('users/edit.html.twig', [
        'error' => "Nombre, correo y rol son obligatorios.",
        'old' => $_POST,
        'roles' => $roles
      ]);
    }

    if ($password !== $passwordConfirm ?? '') {
      return $this->renderWithFlash('users/edit.html.twig', [
        'error' => "Las contraseñas no coinciden.",
        'old' => $_POST,
        'roles' => $roles
      ]);
    }

    $this->userRepo->update($id, [
      'name' => $name,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT),
      'role_id' => $rolId,
      'active' => $active
    ]);

    return $this->success([], 'Usuario actualizado.', 200, '/users');
  }

  public function destroy(int $id): Response
  {
    $user = $this->userRepo->getById($id);
    if (!$user) {
      return $this->renderWithFlash('users/index.html.twig', [
        'error' => 'Usuario no encontrado'
      ], 404);
    }

    $this->userRepo->destroy($id);
    return $this->success([], 'Usuario eliminado.', 200, '/users');
  }
}
