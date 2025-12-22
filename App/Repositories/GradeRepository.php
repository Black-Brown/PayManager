<?php

namespace App\Repositories;

use JosueIsOffline\Framework\Database\DB;

class GradeRepository
{
  protected string $table = "grades";

  public function getAll()
  {
    return DB::table($this->table)
      ->select()
      ->get();
  }

  public function getLevels(): array
  {
    $rows = DB::raw("
        SELECT COLUMN_TYPE
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = ?
          AND COLUMN_NAME = ?
    ", [$this->table, 'level'])->fetchAll(\PDO::FETCH_ASSOC);

    $type = $rows[0]['COLUMN_TYPE'] ?? '';
    if ($type === '') return [];

    if (!preg_match("/^enum\((.*)\)$/", $type, $m)) return [];

    return str_getcsv($m[1], ',', "'");
  }


  public function FindById(int $id)
  {
    return DB::table($this->table)
      ->where('id', $id)
      ->first();
  }

  public function create(array $data)
  {
    return DB::table($this->table)
      ->insert([
        'name' => $data['name'] ?? '',
        'level' => $data['level'] ?? null,
        'grade_order' => $data['grade_order'] ?? '',
      ]);
  }

  public function update(int $id, array $data)
  {
    return DB::table($this->table)
      ->where('id', $id)
      ->update([
        'name' => $data['name'] ?? '',
        'level' => $data['level'] ?? null,
        'grade_order' => $data['grade_order'] ?? null,
      ]);
  }

  public function destroy(int $id)
  {
    return DB::table($this->table)
      ->where('id', $id)
      ->delete();
  }
}
