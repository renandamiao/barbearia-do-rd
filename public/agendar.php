<?php 
session_start(); // Iniciar a sessão para armazenar a mensagem
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $telefone = $_POST['telefone'];

    // Verificar se a data é no futuro e em um dia útil
    $dataTimestamp = strtotime($data);
    $hoje = date("Y-m-d");
    $diaSemana = date("N", $dataTimestamp); // 1 = segunda-feira, 7 = domingo

    if ($data < $hoje) {
        $_SESSION['mensagem'] = "Não é possível agendar para uma data passada.";
        header("Location: feedback.php");
        exit;
    } elseif ($diaSemana == 6 || $diaSemana == 7) { // 6 = sábado, 7 = domingo
        $_SESSION['mensagem'] = "Não é possível agendar para finais de semana.";
        header("Location: feedback.php");
        exit;
    }

    try {
        // Verificar se já existe um agendamento com os mesmos dados
        $verificacaoQuery = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE telefone = ? AND data = ? AND horario = ?");
        $verificacaoQuery->execute([$telefone, $data, $horario]);
        $existeAgendamento = $verificacaoQuery->fetchColumn();

        if ($existeAgendamento > 0) {
            // Se já existir, definir uma mensagem de erro
            $_SESSION['mensagem'] = "Já existe um agendamento para este telefone na data e horário selecionados.";
        } else {
            // Inserir o agendamento
            $query = $pdo->prepare("INSERT INTO agendamentos (nome, data, horario, telefone) VALUES (?, ?, ?, ?)");
            $query->execute([$nome, $data, $horario, $telefone]);

            // Registrar no histórico com status "concluído"
            $historicoQuery = $pdo->prepare("INSERT INTO historico_agendamentos (nome, data, horario, telefone, status) VALUES (?, ?, ?, ?, 'concluído')");
            $historicoQuery->execute([$nome, $data, $horario, $telefone]);

            // Definir mensagem de sucesso
            $_SESSION['mensagem'] = "Agendamento realizado com sucesso!";
        }
    } catch (Exception $e) {
        // Em caso de erro, definir uma mensagem de erro
        $_SESSION['mensagem'] = "Ocorreu um erro ao tentar realizar o agendamento. Por favor, tente novamente." . htmlspecialchars($e->getMessage());
    }

    // Redirecionar para feedback.php
    header("Location: feedback.php");
    exit;
} 
