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
    <?php 
    
    if (isset($_GET['mensagem'])): ?>
        <div class="mensagem-sucesso">
            <?php echo htmlspecialchars($_GET['mensagem']); ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Barbearia do RD - Agendamento</h2>
        <div class="info">
            <p><strong>Duração do corte:</strong> 30 minutos</p>
            <p><strong>Preço:</strong> R$ 35 </p>
            <p><strong>Pagamento:</strong> No local</p>
            <p><strong>Horários de Trabalho:</strong> Segunda a Sexta, das 08:00 às 11:00 e das 14:00 às 19:00</p>
            <p><strong>Para mais informações, entre em contato:</strong> Fone (xx) xxxxxxxxx </p>
        </div>

        <?php
        
        try {
            include '../config/db_connect.php';
        } catch (PDOException $e) {
            die("Erro na conexão: " . htmlspecialchars($e->getMessage()));
        }

        
        $todosHorarios = [
            '08:00:00', '08:30:00', '09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00',
            '14:00:00', '14:30:00', '15:00:00', '15:30:00', '16:00:00', '16:30:00',
            '17:00:00', '17:30:00', '18:00:00', '18:30:00', '19:00:00',
        ];
        
        $dataSelecionada = isset($_POST['data']) ? $_POST['data'] : date('Y-m-d');

        
        $query = "SELECT horario FROM agendamentos WHERE data = :data";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['data' => $dataSelecionada]);
        $horariosOcupados = $stmt->fetchAll(PDO::FETCH_COLUMN);

       
        $horariosDisponiveis = array_diff($todosHorarios, $horariosOcupados);
        ?>

       
        <form method="POST">
            <label for="data"><strong>Selecione uma data para verificar horários:</strong></label>
            <input type="date" name="data" id="data" value="<?php echo htmlspecialchars($dataSelecionada); ?>" required>
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
            <input type="hidden" name="acao" value="agendar"> 
            <button type="submit">Agendar</button>
        </form>

        
        <h3>Cancelar Agendamento</h3>
        <form id="cancelarForm" method="POST" action="cancelar.php">
            <input type="text" id="telefoneCancel" name="telefone" placeholder="Digite seu número de telefone para cancelar" required pattern="\d{2}\d{8,9}" title="Formato: DD + 8 ou 9 dígitos">
            <input type="hidden" name="acao" value="cancelar"> 
            <button type="submit">Cancelar Agendamento</button>
        </form>

        
        <h3>Verificar Agendamentos</h3>
        <form id="verificarForm" method="POST" action="verificar.php">
            <input type="text" id="telefoneVerificar" name="telefone" placeholder="Digite seu número de telefone para verificar" required pattern="\d{2}\d{8,9}" title="Formato: DD + 8 ou 9 dígitos">
            <input type="hidden" name="acao" value="verificar"> 
            <button type="submit">Verificar Agendamentos</button>
        </form>

       
        <h3>Histórico de Agendamentos</h3>
        <form id="historicoForm" method="POST" action="historico.php">
            <input type="text" id="telefoneHistorico" name="telefone" placeholder="Digite seu número de telefone para ver o histórico" required pattern="\d{2}\d{8,9}" title="Formato: DD + 8 ou 9 dígitos">
            <input type="hidden" name="acao" value="historico"> 
            <button type="submit">Ver Histórico</button>
        </form>

        
        <div id="resultado"></div>
        <div id="resultadoHistorico"></div>
    </div>
</body>
</html>
