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

    <h2>TERMINAL</h2>
    <p style="color: #666; font-style: italic;"></p>

    <div id="terminal-container" style="background-color: #000; padding: 20px; font-family: monospace; color: #0f0; min-height: 400px; max-height: 500px; overflow-y: auto; border: 1px solid #333;">
        <div id="terminal-history">
            <p style="color: #0f0; margin: 0;">> Estableciendo enlace encriptado...</p>
            <p style="color: #0f0; margin: 0;">> ENLACE ESTABLECIDO</p>
            <p style="color: #0f0; margin: 0;">> Buscando señales...</p>
            <p style="color: #0f0; margin: 0;">> (1) SEÑAL ENCONTRADA</p>
            <p style="color: #0f0; margin: 0;">> ENLACE ACEPTADO con usuario:A?1?p servidor:t?er?a</p>
        </div>

        <div style="margin-top: 15px; display: flex;">
            <!-- He cambiado el color del span a blanco (#fff) para que se mantenga blanco -->
            <span id="terminal-prompt" style="color: #fff; margin-right: 10px;"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'daeva'; ?>@atreia:</span>
            <!-- He cambiado el color del input a blanco (#fff) para que lo que escribas y lo que se mantenga sea blanco -->
            <input type="text" id="terminal-input" style="background: transparent; border: none; color: #fff; font-family: monospace; flex-grow: 1; outline: none; width: 100%;" autocomplete="off" autofocus />
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const CLOUDFLARE_LLM_URL = "https://llm-colombianage.eveblack.workers.dev/api/chat";
    const USERNAME = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'daeva'; ?>";

    const inputField = document.getElementById("terminal-input");
    const historyContainer = document.getElementById("terminal-history");
    const terminalContainer = document.getElementById("terminal-container");

    inputField.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            const message = inputField.value.trim();
            if (message === "") return;

            // 1. Mensaje del Usuario (en blanco)
            const userP = document.createElement("p");
            userP.style.color = "#fff"; 
            userP.style.margin = "0";
            userP.textContent = USERNAME + "@atreia: " + message;
            historyContainer.appendChild(userP);

            inputField.value = "";
            terminalContainer.scrollTop = terminalContainer.scrollHeight;

            // Mensaje de carga (en verde intermitente/sistema)
            const loadingP = document.createElement("p");
            loadingP.style.color = "#0f0";
            loadingP.style.margin = "0";
            loadingP.textContent = "Esperando transmisión...";
            historyContainer.appendChild(loadingP);
            terminalContainer.scrollTop = terminalContainer.scrollHeight;

            // Enviar al LLM
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

                // 2. Mensaje de la IA (Cambiado a color ROJO #f00)
                const replyP = document.createElement("p");
                replyP.style.color = "#f00";
                replyP.style.margin = "0";
                
                const llmResponse = data.response || data.reply || "TRANSMISIÓN RECIBIDA: " + JSON.stringify(data);
                replyP.textContent = "A?1?p@t?er?a: " + llmResponse;
                
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
