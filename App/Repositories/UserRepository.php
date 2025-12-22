<?php

namespace App\Repositories;

use App\Models\User;
use JosueIsOffline\Framework\Database\DB;

class UserRepository
{
  protected string $table = "users";

  public function getAll()
  {
    return DB::table($this->table)
      ->select()
      ->get();
  }

  public function getById(int $id): ?array
  {
    $user = new User();
    return $user->query()->where('id', $id)->first() ?? null;
  }

  public function getByEmail(string $email): ?array
  {
    $user = new User();
    return $user->query()->where('email', $email)->first();
  }

  public function getAllWithRole(): array
  {
    return DB::raw("
        SELECT u.id, u.name, u.email, u.active, r.name AS role_name
        FROM users u
        JOIN roles r ON r.id = u.role_id
        ORDER BY u.id DESC
    ")->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function update(int $id, array $data)
  {
    return DB::table($this->table)
      ->where('id', $id)
      ->update([
        'name' => $data['name'] ?? '',
        'email' => $data['email'] ?? '',
        'password' => $data['password'] ?? null,
        'role_id' => $data['role_id'] ?? 1,
        'active' => $data['active'] ?? true,
      ]);
  }

  public function create(array $data)
  {
    return DB::table($this->table)
      ->insert([
        'name' => $data['name'] ?? '',
        'email' => $data['email'] ?? '',
        'password' => $data['password'] ?? null,
        'role_id' => $data['role_id'] ?? 1,
        'active' => $data['active'] ?? true,
      ]);
  }

  public function destroy(int $id)
  {
    return DB::table($this->table)
      ->where('id', $id)
      ->delete();
  }
}
