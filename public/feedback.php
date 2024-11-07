<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
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
    background-color: rgba(255, 255, 255, 0.95); 
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); 
    width: 90%;
    max-width: 500px;
    border: 1px solid #ddd; 
}

.feedback-container h3, .feedback-container p {
    color: #333; 
}


.feedback-container table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.feedback-container th, .feedback-container td {
    padding: 10px;
    border: 1px solid #ddd; 
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
            background-color: #4CAF50; 
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #45a049; 
        }
    </style>
</head>
<body class="background-imagem1">

<div class="feedback-container">
    <?php
    
    if (isset($_SESSION['mensagem']) && $_SESSION['mensagem'] !== "") {
        echo $_SESSION['mensagem']; 
        unset($_SESSION['mensagem']); 
    }

    
    if (isset($_SESSION['output'])) {
        echo $_SESSION['output']; 
        unset($_SESSION['output']); 
    }
    ?>

    
    <a href="index.php">Voltar ao Agendamento</a>
</div>

</body>
</html>