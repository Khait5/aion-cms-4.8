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
            <p style="color: #f39c12; margin: 0;">> SISTEMA DE SEGURIDAD ACTIVADO</p>
            <p style="color: #f39c12; margin: 0;">> INTRODUZCA CÓDIGO DE ACCESO EXTERIOR:</p>
        </div>

        <div style="margin-top: 15px; display: flex;">
            <span id="terminal-prompt" style="color: #fff; margin-right: 10px;"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'daeva'; ?>@atreia (AUTH):</span>
            <input type="text" id="terminal-input" style="background: transparent; border: none; color: #fff; font-family: monospace; flex-grow: 1; outline: none; width: 100%;" autocomplete="off" autofocus />
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const CLOUDFLARE_LLM_URL = "https://llm-colombianage.eveblack.workers.dev/api/chat";
    const USERNAME = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'daeva'; ?>";
    const CORRECT_CODE = "0305";

    let isAuthenticated = false; 
    let isBusy = false; // Bloquea el input mientras escribe la secuencia

    const inputField = document.getElementById("terminal-input");
    const historyContainer = document.getElementById("terminal-history");
    const terminalContainer = document.getElementById("terminal-container");
    const promptSpan = document.getElementById("terminal-prompt");

    // Función auxiliar para simular efecto máquina de escribir con retraso de 1s al terminar cada línea
    function typeWriterSequence(sequenceArray, index = 0, callback) {
        if (index >= sequenceArray.length) {
            if (callback) callback();
            return;
        }

        const item = sequenceArray[index];
        const p = document.createElement("p");
        p.style.color = item.color;
        p.style.margin = "0";
        historyContainer.appendChild(p);

        let charIdx = 0;
        const speed = 15; // Velocidad de escritura letra a letra (cuanto menor, más rápido)

        function typeChar() {
            if (charIdx < item.text.length) {
                p.textContent += item.text.charAt(charIdx);
                charIdx++;
                terminalContainer.scrollTop = terminalContainer.scrollHeight;
                setTimeout(typeChar, speed);
            } else {
                // Al terminar la línea actual, espera exactamente 1 segundo (1000ms) antes de la siguiente
                setTimeout(function() {
                    typeWriterSequence(sequenceArray, index + 1, callback);
                }, 1000);
            }
        }

        typeChar();
    }

    inputField.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            if (isBusy) return; // Si está imprimiendo la secuencia, ignora el enter

            const inputVal = inputField.value.trim();
            if (inputVal === "") return;

            // PASO 1: Control de acceso por código
            if (!isAuthenticated) {
                const userP = document.createElement("p");
                userP.style.color = "#fff"; 
                userP.style.margin = "0";
                userP.textContent = USERNAME + "@atreia (AUTH): " + inputVal;
                historyContainer.appendChild(userP);

                inputField.value = "";
                terminalContainer.scrollTop = terminalContainer.scrollHeight;

                if (inputVal === CORRECT_CODE) {
                    isAuthenticated = true;
                    isBusy = true; // Bloqueamos la terminal durante la secuencia animada
                    inputField.disabled = true; 

                    promptSpan.textContent = USERNAME + "@atreia:"; 

                    const successSeq = [
                        { text: "> CÓDIGO CORRECTO. Autorización concedida.", color: "#0f0" },
                        { text: "> Estableciendo enlace encriptado...", color: "#0f0" },
                        { text: "> ENLACE ESTABLECIDO", color: "#0f0" },
                        { text: "> Buscando señales...", color: "#0f0" },
                        { text: "> (1) SEÑAL ENCONTRADA", color: "#0f0" },
                        { text: "> ENLACE ACEPTADO con usuario:A?1?p servidor:t?er?a", color: "#0f0" }
                    ];

                    // Ejecutamos la secuencia animada y al terminar reactivamos el input
                    typeWriterSequence(successSeq, 0, function() {
                        isBusy = false;
                        inputField.disabled = false;
                        inputField.focus();
                    });

                } else {
                    const errP = document.createElement("p");
                    errP.style.color = "#ff3333";
                    errP.style.margin = "0";
                    errP.textContent = "> ERROR: Código incorrecto. Acceso denegado. No se establecerá ningún enlace.";
                    historyContainer.appendChild(errP);
                }

                terminalContainer.scrollTop = terminalContainer.scrollHeight;
                return; 
            }

            // PASO 2: Funcionamiento normal del chat (Una vez autenticado)
            const message = inputVal;

            const userP = document.createElement("p");
            userP.style.color = "#fff"; 
            userP.style.margin = "0";
            userP.textContent = USERNAME + "@atreia: " + message;
            historyContainer.appendChild(userP);

            inputField.value = "";
            terminalContainer.scrollTop = terminalContainer.scrollHeight;

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

                const replyP = document.createElement("p");
                replyP.style.color = "#f00"; // Respuesta de la IA en rojo
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
