<?php
// functions.php

function get_db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dbFile = __DIR__ . '/participants.sqlite';
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ensure table exists
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS participants (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                ip TEXT NOT NULL UNIQUE,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    return $pdo;
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function get_client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function has_ip_registered(string $ip): bool
{
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM participants WHERE ip = :ip");
    $stmt->execute([':ip' => $ip]);
    return $stmt->fetchColumn() > 0;
}

function add_participant(string $name, string $ip): bool
{
    $pdo = get_db();
    $stmt = $pdo->prepare("
        INSERT INTO participants (name, ip) 
        VALUES (:name, :ip)
    ");
    try {
        return $stmt->execute([
            ':name' => $name,
            ':ip'   => $ip
        ]);
    } catch (PDOException $e) {
        // likely unique constraint on IP
        return false;
    }
}

function get_participants(): array
{
    $pdo = get_db();
    $stmt = $pdo->query("
        SELECT id, name, ip, created_at
        FROM participants
        ORDER BY created_at ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function delete_participant(int $id): void
{
    $pdo = get_db();
    $stmt = $pdo->prepare("DELETE FROM participants WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

function delete_all_participants(): void
{
    $pdo = get_db();
    $pdo->exec("DELETE FROM participants");
}
