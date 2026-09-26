<?php
// Bloqueo de seguridad usando la función nativa de AIONCMS
if (!isLoggedIn()) {
    echo '<div class="server-info-container" style="padding: 50px; text-align: center; border: 1px solid #333; background: #000;">';
    echo '<h2 style="color: #ff3333;">[ ACCESO RESTRINGIDO ]</h2>';
    echo '<p style="color: #0f0; font-family: monospace;">Nivel de autorización insuficiente.</p>';
    echo '<p style="color: #aaa;">Debes <a href="'.module_url('login', true).'" style="color: #fff; text-decoration: underline;">iniciar sesión</a> para acceder a este enlace de la red.</p>';
    echo '</div>';
    return; // Evita que cargue el resto de la página
}
?>
<div class="page-header-block info"></div>
<div class="server-info-container" style="padding: 20px;">

    <h2>Conexión a la TIERRA</h2>
    <p style="color: #666; font-style: italic;">Estableciendo enlace encriptado...</p>

    <div id="terminal-container" style="background-color: #000; padding: 20px; font-family: monospace; color: #0f0; min-height: 400px; max-height: 500px; overflow-y: auto; border: 1px solid #333;">
        <div id="terminal-history">
            <p style="color: #0f0; margin: 0;">> ENLACE ESTABLECIDO</p>
            <p style="color: #0f0; margin: 0;">> ESPERANDO INPUT DEL USUARIO...</p>
        </div>

        <div style="margin-top: 15px; display: flex;">
            <span style="color: #0f0; margin-right: 10px;">root@tierra:~#</span>
            <input type="text" id="terminal-input" style="background: transparent; border: none; color: #0f0; font-family: monospace; flex-grow: 1; outline: none; width: 100%;" autocomplete="off" autofocus />
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const CLOUDFLARE_LLM_URL = "https://llm-colombianage.eveblack.workers.dev/api/chat";

    const inputField = document.getElementById("terminal-input");
    const historyContainer = document.getElementById("terminal-history");
    const terminalContainer = document.getElementById("terminal-container");

    inputField.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            const message = inputField.value.trim();
            if (message === "") return;

            // Add user message to history
            const userP = document.createElement("p");
            userP.style.color = "#fff"; // User text in white
            userP.style.margin = "0";
            userP.textContent = "> " + message;
            historyContainer.appendChild(userP);

            inputField.value = "";
            terminalContainer.scrollTop = terminalContainer.scrollHeight;

            // Add loading message
            const loadingP = document.createElement("p");
            loadingP.style.color = "#0f0";
            loadingP.style.margin = "0";
            loadingP.textContent = "Enviando transmisión...";
            historyContainer.appendChild(loadingP);
            terminalContainer.scrollTop = terminalContainer.scrollHeight;

            // Send to LLM
            fetch(CLOUDFLARE_LLM_URL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                historyContainer.removeChild(loadingP);

                const replyP = document.createElement("p");
                replyP.style.color = "#0f0";
                replyP.style.margin = "0";
                // Assumes response has a "reply" or "response" field, fallback to raw text if needed
                replyP.textContent = data.reply || data.response || "TRANSMISIÓN RECIBIDA: " + JSON.stringify(data);
                historyContainer.appendChild(replyP);

                terminalContainer.scrollTop = terminalContainer.scrollHeight;
            })
            .catch(error => {
                historyContainer.removeChild(loadingP);

                const errorP = document.createElement("p");
                errorP.style.color = "#f00";
                errorP.style.margin = "0";
                errorP.textContent = "ERROR DE CONEXIÓN: " + error.message;
                historyContainer.appendChild(errorP);

                terminalContainer.scrollTop = terminalContainer.scrollHeight;
            });
        }
    });
});
</script>
<br /><br />
