<?php 
session_start();
include '../config/db_connect.php';
date_default_timezone_set('America/Sao_Paulo');


$dataSelecionada = isset($_POST['data']) ? $_POST['data'] : date('Y-m-d');


$diaSemana = date('N', strtotime($dataSelecionada));
if ($diaSemana > 5 || strtotime($dataSelecionada) < strtotime(date('Y-m-d'))) {
    $horariosDisponiveis = [];  
} else {
    
    $queryHorarios = $pdo->prepare("SELECT horario FROM horarios_disponiveis WHERE dentro_expediente = 1 ORDER BY horario");
    $queryHorarios->execute();
    $todosHorarios = $queryHorarios->fetchAll(PDO::FETCH_COLUMN);

   
    $queryOcupados = "SELECT horario FROM agendamentos WHERE data = :data AND status = 'concluído'";
    $stmtOcupados = $pdo->prepare($queryOcupados);
    $stmtOcupados->execute(['data' => $dataSelecionada]);
    $horariosOcupados = $stmtOcupados->fetchAll(PDO::FETCH_COLUMN);

    
    $horariosDisponiveis = array_diff($todosHorarios, $horariosOcupados);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['horario'])) {
    $currentDateTime = new DateTime();
    $dataHoraSelecionada = new DateTime($dataSelecionada . ' ' . $_POST['horario']);

    $interval = $currentDateTime->diff($dataHoraSelecionada);

    if ($dataHoraSelecionada < $currentDateTime || ($interval->days == 0 && $interval->h == 0 && $interval->i < 30)) {
        $_SESSION['mensagem'] = "Agendamento inválido! Não é permitido agendar com menos de 30 minutos de antecedência.";
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbearia do RD - Agendamentos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="mensagem-sucesso">
            <?php echo htmlspecialchars($_SESSION['mensagem']); ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    
    <div class="container">
        <h2>Barbearia do RD - Agendamento</h2>
        <div class="info">
            <p><strong>Duração do corte:</strong> 30 minutos</p>
            <p><strong>Preço:</strong> R$ 25 </p>
            <p><strong>Pagamento:</strong> No local</p>
            <p><strong>Horários de Trabalho:</strong> Segunda a Sexta, das 08:00 às 11:00 e das 14:00 às 19:00</p>
            <p><strong>Para mais informações, entre em contato:</strong> Fone (xx) xxxxxxxxx </p>

        </div>

        
        <form method="POST">
            <label for="data"><strong>Selecione uma data para verificar horários:</strong></label>
            <input type="date" name="data" id="data" value="<?php echo htmlspecialchars($dataSelecionada); ?>" required min="<?php echo date('Y-m-d'); ?>">
            <button type="submit">Verificar Horários</button>
        </form>

        
        <h3>Agendar Serviço</h3>
        <form id="agendarForm" method="POST" action="agendar.php">
            <input type="text" name="nome" placeholder="Digite seu nome" required>
            <input type="hidden" name="data" value="<?php echo htmlspecialchars($dataSelecionada); ?>">
            <select name="horario" required>
                <option value="" disabled selected>Escolha o horário</option>
                <?php foreach ($horariosDisponiveis as $horario): ?>
                    <option value="<?php echo htmlspecialchars($horario); ?>"><?php echo date('H:i', strtotime($horario)); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="telefone" id="telefone" placeholder="Digite seu número de telefone (DD + número)" required pattern="\d{2}\d{8,9}" title="Formato: DD + 8 ou 9 dígitos">
            <button type="submit">Agendar</button>
        </form>

        
        <h3>Cancelar Agendamento</h3>
        <form id="cancelarForm" method="POST" action="cancelar.php">
            <input type="text" id="telefoneCancel" name="telefone" placeholder="Digite seu número de telefone para cancelar" required pattern="\d{2}\d{8,9}" title="Formato: DD + 8 ou 9 dígitos">
            <button type="submit">Cancelar Agendamento</button>
        </form>

        <h3>Verificar Agendamentos</h3>
        <form id="verificarForm" method="POST" action="verificar.php">
            <input type="text" id="telefoneVerificar" name="telefone" placeholder="Digite seu número de telefone para verificar" required pattern="\d{2}\d{8,9}" title="Formato: DD + 8 ou 9 dígitos">
            <button type="submit">Verificar Agendamentos</button>
        </form>

        <h3>Histórico de Agendamentos</h3>
        <form id="historicoForm" method="POST" action="historico.php">
            <input type="text" id="telefoneHistorico" name="telefone" placeholder="Digite seu número de telefone para ver o histórico" required pattern="\d{2}\d{8,9}" title="Formato: DD + 8 ou 9 dígitos">
            <button type="submit">Ver Histórico</button>
        </form>

        <div id="resultado"></div>
        <div id="resultadoHistorico"></div>
    </div>

    
</body>
</html>
