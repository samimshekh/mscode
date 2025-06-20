<?php
namespace Logic\Schema;

class news {
    public array $columns = [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'title' => 'VARCHAR(255) NOT NULL',
        'body' => 'TEXT',
        'created_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
    ];

    public string $engine  = 'InnoDB';
    public string $charset = 'utf8mb4';
}