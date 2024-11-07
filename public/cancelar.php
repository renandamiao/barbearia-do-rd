<?php
session_start();
include '../config/db_connect.php';
date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['telefone'])) {
    $telefone = htmlspecialchars(trim($_POST['telefone']));

    try {
        
        $query = $pdo->prepare("SELECT * FROM agendamentos WHERE telefone = :telefone AND data >= CURDATE() ORDER BY data, horario");
        $query->execute(['telefone' => $telefone]);
        $agendamentos = $query->fetchAll(PDO::FETCH_ASSOC);

        
        if ($agendamentos) {
            $_SESSION['output'] = "<form method='POST' action='processar_cancelamento.php'>";
            $_SESSION['output'] .= "<h3 style='text-align: center; font-size: 18px; color: #333;'>Selecione os agendamentos para cancelar:</h3>";

            foreach ($agendamentos as $agendamento) {
                $_SESSION['output'] .= "<div class='checkbox-group' style='margin: 10px 0; display: flex; align-items: center;'>";
                $_SESSION['output'] .= "<input type='checkbox' class='custom-checkbox' name='agendamentos[]' value='" . htmlspecialchars($agendamento['id']) . "' style='width: 25px; height: 25px; border-radius: 50%; border: 2px solid #4CAF50; margin-right: 10px; cursor: pointer;'>";
                $_SESSION['output'] .= "<label style='font-size: 16px; color: #333;'>Data: " . htmlspecialchars($agendamento['data']) . " - Horário: " . htmlspecialchars($agendamento['horario']) . "</label>";
                $_SESSION['output'] .= "</div>";
            }

            
            $_SESSION['output'] .= "<div style='text-align: center; margin-top: 20px;'>";
            $_SESSION['output'] .= "<button type='submit' class='btn-primary' style='font-size: 16px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer;'>Cancelar Selecionados</button>";
            $_SESSION['output'] .= "</div>";
            $_SESSION['output'] .= "</form>";
        } else {
            $_SESSION['mensagem'] = "Nenhum agendamento encontrado para o número informado.";
        }
    } catch (Exception $e) {
        
        $_SESSION['mensagem'] = "Erro ao buscar agendamentos: " . htmlspecialchars($e->getMessage());
    }

    
    header("Location: feedback.php");
    exit;
} else {
    
    echo "<form method='POST' action=''>";
    echo "<label>Digite seu número de telefone:</label><br>";
    echo "<input type='text' name='telefone' required>";
    echo "<button type='submit' class='btn-primary'>Buscar Agendamentos</button>"; 
    echo "</form>";
}
?>
