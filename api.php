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

// Rota para adicionar uma nova tarefa
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //realiza a decodificação de Json para uma array 
    $data = json_decode(file_get_contents('php://input'), true);

    //Verificar se a variável data não esta vazia
    if(empty($data['title'])){
        echo json_encode(['error' => 'O Título da tarefa não pode ser vazio']);
        exit;
    }

    $title = $data['title'];

    //Exeção e Erro
    try {
        //stmt = declaração
        $stmt = $connection->prepare('INSERT INTO tasks (title) VALUES (:title)');
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        $taskId = $connection->lastInsertId();
    } catch(PDOException $error){
        echo json_encode(['error' => $error->getMessage()]);
    }
}
