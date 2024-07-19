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

    //Exceção e Erro
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

//Rota para marcar uma tarefa como concluída
if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    $data = json_decode(file_get_contents('php://input'), true);

    //Verifica se o ID não esta vazio
    if(empty($data['id'])){
        echo json_encode(['error' => 'O ID não pode ser vazio']);
        exit;
    }

    $taskId = $data['id'];

    //Exceção e Erro
    try {
        //stmt = declaração
        $stmt = $connection->prepare('UPDATE tasks SET completed = 1 WHERE id = : id');
        $stmt->bindParam(':id', $taskId);
        $stmt->execute();
        echo json_encode(['sucess' => true]);
    } catch(PDOException $error){
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// Rota para deletar uma tarefa
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['id'])) {
        echo json_encode(['error' => 'O ID da tarefa é obrigatório']);
        exit;
    }

    $taskId = $data['id'];

    try {
        $stmt = $connection->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->bindParam(':id', $taskId);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch(PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}