document.addEventListener("DOMContentLoaded", function() {
    const telefoneInputs = document.querySelectorAll('input[name="telefone"], #telefoneCancel, #telefoneVerificar, #telefoneHistorico');
    telefoneInputs.forEach(input => {
        input.addEventListener("input", function(e) {
            let numero = e.target.value.replace(/\D/g, ""); // Remove caracteres não numéricos
            // Limita o número de dígitos a 11
            if (numero.length > 11) {
                numero = numero.substring(0, 11);
            }
            e.target.value = numero;
        });
    });
});


function mostrarNotificacao(mensagem) {
    const notificacao = document.getElementById("notificacao");
    notificacao.innerText = mensagem;
    notificacao.style.display = "block";
    setTimeout(() => {
        notificacao.style.display = "none";
    }, 5000);
}

function agendar() {
    const nome = document.getElementById("nome").value;
    const data = document.getElementById("data").value;
    const horario = document.getElementById("horario").value;
    const telefone = document.getElementById("telefone").value;
    fetch("agendar.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `nome=${nome}&data=${data}&horario=${horario}&telefone=${telefone}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("resultado").innerText = data;
        mostrarNotificacao("Agendamento realizado com sucesso!");
    });
}

function cancelarAgendamento() {
    const telefone = document.getElementById("telefoneCancel").value;
    fetch("cancelar.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `telefone=${telefone}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("resultado").innerText = data;
        mostrarNotificacao("Agendamento cancelado com sucesso!");
    });
}

function verificarAgendamentos() {
    const telefone = document.getElementById("telefoneVerificar").value;
    fetch("verificar.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `telefone=${telefone}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("resultado").innerText = data;
        mostrarNotificacao("Agendamentos verificados com sucesso!");
    });
}

function historicoAgendamentos() {
    const telefone = document.getElementById("telefoneHistorico").value;
    fetch("historico.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `telefone=${telefone}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("resultadoHistorico").innerText = data;
        mostrarNotificacao("Histórico de agendamentos exibido com sucesso!");
    });
}


