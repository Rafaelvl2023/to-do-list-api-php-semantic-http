<?php

require 'database.php';

// rota para buscar todas as tarefas
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    try {
        $statement = $connection->query('SELECT * FROM tasks');
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($tasks);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}


