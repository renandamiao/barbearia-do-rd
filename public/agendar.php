<?php 
session_start(); 
include '../config/db_connect.php';
date_default_timezone_set('America/Sao_Paulo');


$nome = $_POST['nome'];
$dataSelecionada = $_POST['data'];
$horarioSelecionado = $_POST['horario'];
$telefone = $_POST['telefone'];

$dataAtual = date('Y-m-d');
$horaAtual = date('H:i:s');


if ($dataSelecionada < $dataAtual || ($dataSelecionada == $dataAtual && $horarioSelecionado <= $horaAtual)) {
    $_SESSION['mensagem'] = "Não é possível agendar para horários passados.";
    header("Location: feedback.php");
    exit;
}

$inicioManha = '08:00:00';
$fimManha = '11:00:00';
$inicioTarde = '14:00:00';
$fimTarde = '19:00:00';

if (!(($horarioSelecionado >= $inicioManha && $horarioSelecionado <= $fimManha) || 
      ($horarioSelecionado >= $inicioTarde && $horarioSelecionado <= $fimTarde))) {
    $_SESSION['mensagem'] = "Horário fora do expediente. Selecione outro horário.";
    header("Location: feedback.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $telefone = $_POST['telefone'];

    
    $dataTimestamp = strtotime($data);
    $hoje = date("Y-m-d");
    $diaSemana = date("N", $dataTimestamp); 

    if ($data < $hoje) {
        $_SESSION['mensagem'] = "Não é possível agendar para uma data passada.";
        header("Location: feedback.php");
        exit;
    } elseif ($diaSemana == 6 || $diaSemana == 7) { 
        $_SESSION['mensagem'] = "Não é possível agendar para finais de semana.";
        header("Location: feedback.php");
        exit;
    }

    try {
        
        $verificacaoQuery = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE telefone = ? AND data = ? AND horario = ?");
        $verificacaoQuery->execute([$telefone, $data, $horario]);
        $existeAgendamento = $verificacaoQuery->fetchColumn();

        if ($existeAgendamento > 0) {
            
            $_SESSION['mensagem'] = "Já existe um agendamento para este telefone na data e horário selecionados.";
        } else {
            
            $query = $pdo->prepare("INSERT INTO agendamentos (nome, data, horario, telefone) VALUES (?, ?, ?, ?)");
            $query->execute([$nome, $data, $horario, $telefone]);

            
            $historicoQuery = $pdo->prepare("INSERT INTO historico_agendamentos (nome, data, horario, telefone, status) VALUES (?, ?, ?, ?, 'concluído')");
            $historicoQuery->execute([$nome, $data, $horario, $telefone]);

           
            $_SESSION['mensagem'] = "Agendamento realizado com sucesso!";
        }
    } catch (Exception $e) {
    
        $_SESSION['mensagem'] = "Ocorreu um erro ao tentar realizar o agendamento. Por favor, tente novamente." . htmlspecialchars($e->getMessage());
    }

   
    header("Location: feedback.php");
    exit;
} 

