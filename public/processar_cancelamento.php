<?php
include '../config/db_connect.php';
session_start();
date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['agendamentos'])) {
    $agendamentosParaCancelar = $_POST['agendamentos'];

    try {
        foreach ($agendamentosParaCancelar as $id) {
            
            $insertQuery = $pdo->prepare("
                INSERT INTO historico_agendamentos (nome, telefone, data, horario, status, data_registro)
                SELECT nome, telefone, data, horario, 'cancelado', NOW() 
                FROM agendamentos
                WHERE id = :id
            ");
            $insertQuery->execute(['id' => $id]);

            
            $deleteQuery = $pdo->prepare("DELETE FROM agendamentos WHERE id = :id");
            $deleteQuery->execute(['id' => $id]);
        }

        $_SESSION['mensagem'] = "<h3>Cancelamento de Agendamentos</h3><p>Os agendamentos selecionados foram cancelados com sucesso e os horários liberados.</p>";
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "<h3>Erro no Cancelamento</h3><p>Erro ao cancelar os agendamentos. Por favor, tente novamente. Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    $_SESSION['mensagem'] = "<h3>Erro no Cancelamento</h3><p>Nenhum agendamento selecionado para cancelamento.</p>";
}


header("Location: feedback.php");
exit;
?>