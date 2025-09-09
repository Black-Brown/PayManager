<?php

require('../config/database.php');

class SQLExecutor
{
    private PDO $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getDB();
    }

    public function ejecutarArchivoSQL(string $ruta): void
    {
        if (!file_exists($ruta)) {
            fwrite(STDERR, "❌ Archivo no encontrado: $ruta\n");
            exit(1);
        }

        $sql = file_get_contents($ruta);
        $sentencias = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));

        foreach ($sentencias as $query) {
            try {
                $this->conn->exec($query);
                echo "✅ Ejecutado:\n$query\n\n";
            } catch (Throwable $e) {
                echo "⚠️ Error en:\n$query\n→ {$e->getMessage()}\n\n";
            }
        }
    }
}

// Ejemplo de uso
$executor = new SQLExecutor();
$executor->ejecutarArchivoSQL(__DIR__ . '/../scripts/paymanager_db.sql');
