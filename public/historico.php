<?php
session_start();
require '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefone = $_POST['telefone'];

    if (empty($telefone)) {
        $_SESSION['mensagem'] = "Por favor, insira um número de telefone.";
        header("Location: feedback.php");
        exit;
    }

    try {
        $sql = "SELECT * FROM historico_agendamentos WHERE telefone = :telefone ORDER BY data, horario";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['telefone' => $telefone]);
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($agendamentos) {
            $mensagem = "<h3>Histórico de Agendamentos</h3>";
            $mensagem .= "<table border='1' style='border-collapse: collapse; width: 100%;'>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Status</th>
                                <th>Registrado em</th>
                            </tr>";

            $dataAnterior = null;
            $horarioAnterior = null;

          
            $grupos = [];
            foreach ($agendamentos as $agendamento) {
                $chave = $agendamento['data'] . ' ' . $agendamento['horario'];
                $grupos[$chave][] = $agendamento;
            }

           
            foreach ($grupos as $chave => $agendamentosGrupo) {
                $dataAtual = $agendamentosGrupo[0]['data'];
                $horarioAtual = $agendamentosGrupo[0]['horario'];

                
                if ($dataAtual !== $dataAnterior) {
                    $mensagem .= "<tr style='background-color: #e0e0e0;'>
                                    <td colspan='4' style='text-align: center; font-weight: bold; padding: 8px;'>" . htmlspecialchars($dataAtual) . "</td>
                                  </tr>";
                    $dataAnterior = $dataAtual;
                }

                
                foreach ($agendamentosGrupo as $agendamento) {
                    $rowStyle = (count($agendamentosGrupo) > 1) ? "border-left: 3px solid #ff6347; background-color: #ffefef;" : ""; 

                    $mensagem .= "<tr style='$rowStyle'>
                                    <td></td>
                                    <td>" . htmlspecialchars($agendamento['horario']) . "</td>
                                    <td>" . htmlspecialchars($agendamento['status']) . "</td>
                                    <td>" . htmlspecialchars($agendamento['data_registro']) . "</td>
                                  </tr>";
                }
            }

            $mensagem .= "</table>";
        } else {
            $mensagem = "<h3>Histórico de Agendamentos</h3><p>Nenhum agendamento encontrado para o telefone: " . htmlspecialchars($telefone) . ".</p>";
        }

        $_SESSION['mensagem'] = $mensagem;
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "Erro ao verificar agendamentos. Por favor, tente novamente. " . htmlspecialchars($e->getMessage());
    }

    header("Location: feedback.php");
    exit;
} else {
    $_SESSION['mensagem'] = "Método não suportado.";
    header("Location: feedback.php");
    exit;
}
?>
