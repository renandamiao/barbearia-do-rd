<?php
require '../config/db_connect.php';
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['telefone'])) {
    $telefone = $_POST['telefone'];

    try {
        
        $sql = "SELECT * FROM agendamentos WHERE telefone = :telefone AND data >= CURDATE() ORDER BY data, horario";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['telefone' => $telefone]);
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($agendamentos) {
            
            $mensagem = "<h3>Próximos Agendamentos</h3>";
            $mensagem .= "<table border='1'>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                            </tr>";
            foreach ($agendamentos as $agendamento) {
                $mensagem .= "<tr>
                                <td>" . htmlspecialchars($agendamento['data']) . "</td>
                                <td>" . htmlspecialchars($agendamento['horario']) . "</td>
                              </tr>";
            }
            $mensagem .= "</table>";
        } else {
            $mensagem = "<h3>Próximos Agendamentos</h3><p>Nenhum agendamento encontrado para o telefone: " . htmlspecialchars($telefone) . ".</p>";
        }

        
        $_SESSION['mensagem'] = $mensagem;
    } catch (Exception $e) {
        
        $_SESSION['mensagem'] = "<h3>Erro na Verificação</h3><p>Erro ao verificar agendamentos: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    $_SESSION['mensagem'] = "<h3>Erro na Verificação</h3><p>Por favor, insira um número de telefone.</p>";
}


header("Location: feedback.php");
exit;
?>
