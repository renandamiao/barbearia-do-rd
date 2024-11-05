<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação - Barbearia do RD</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .feedback-container {
    background-color: rgba(255, 255, 255, 0.95); /* Aumenta a opacidade para mais visibilidade */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Sombra mais forte para destacar */
    width: 90%;
    max-width: 500px;
    border: 1px solid #ddd; /* Borda suave */
}

.feedback-container h3, .feedback-container p {
    color: #333; /* Cor do texto para visibilidade */
}

/* Tabela de histórico */
.feedback-container table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.feedback-container th, .feedback-container td {
    padding: 10px;
    border: 1px solid #ddd; /* Borda entre as células */
    text-align: center;
}

.feedback-container th {
    background-color: #4CAF50;
    color: #fff;
    font-weight: bold;
}

.feedback-container td {
    background-color: #fff;
    color: #333;
}

a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50; /* Cor de fundo */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #45a049; /* Cor ao passar o mouse */
        }
    </style>
</head>
<body class="background-imagem1">

<div class="feedback-container">
    <?php
    // Exibir mensagem de feedback, se existir
    if (isset($_SESSION['mensagem']) && $_SESSION['mensagem'] !== "") {
        echo $_SESSION['mensagem']; // Exibe a mensagem com HTML interpretado
        unset($_SESSION['mensagem']); // Limpa a mensagem da sessão após exibir
    }

    // Exibir o formulário de cancelamento, se existir
    if (isset($_SESSION['output'])) {
        echo $_SESSION['output']; // Exibe o HTML do formulário
        unset($_SESSION['output']); // Limpa o conteúdo após exibir
    }
    ?>

    <!-- Botão para voltar ao agendamento ou à página inicial -->
    <a href="index.php">Voltar ao Agendamento</a>
</div>

</body>
</html>