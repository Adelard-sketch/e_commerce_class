<?php
require_once __DIR__ . '/db.php';

$sql = "CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'done') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Setup complete</title><link rel="stylesheet" href="style.css"></head><body><main class="shell"><section class="panel"><p class="eyebrow">Tasks App</p><h1>Setup complete</h1><p>Your database and tasks table are ready.</p><a class="button" href="index.php">Go to Tasks App</a></section></main></body></html>';
} else {
    echo "Error creating table: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
}

$conn->close();
