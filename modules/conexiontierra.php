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
    let isBusy = false; 

    const inputField = document.getElementById("terminal-input");
    const historyContainer = document.getElementById("terminal-history");
    const terminalContainer = document.getElementById("terminal-container");
    const promptSpan = document.getElementById("terminal-prompt");

    // Función avanzada para la secuencia de arranque
    function runStartupSequence(callback) {
        const p1 = document.createElement("p");
        p1.style.color = "#0f0"; p1.style.margin = "0"; historyContainer.appendChild(p1);
        typeText(p1, "> CÓDIGO CORRECTO. Autorización concedida.", 15, function() {
            setTimeout(function() {
                const p2 = document.createElement("p");
                p2.style.color = "#0f0"; p2.style.margin = "0"; historyContainer.appendChild(p2);
                typeText(p2, "> Estableciendo enlace encriptado...", 15, function() {
                    setTimeout(function() {
                        const p3 = document.createElement("p");
                        p3.style.color = "#0f0"; p3.style.margin = "0"; historyContainer.appendChild(p3);
                        typeText(p3, "> ENLACE ESTABLECIDO", 15, function() {
                            setTimeout(function() {
                                const p4 = document.createElement("p");
                                p4.style.color = "#0f0"; p4.style.margin = "0"; historyContainer.appendChild(p4);
                                typeText(p4, "> Buscando señales", 15, function() {
                                    // Añadir los 3 puntos uno a uno cada 1 segundo
                                    setTimeout(function() {
                                        p4.textContent += ".";
                                        terminalContainer.scrollTop = terminalContainer.scrollHeight;
                                        setTimeout(function() {
                                            p4.textContent += ".";
                                            terminalContainer.scrollTop = terminalContainer.scrollHeight;
                                            setTimeout(function() {
                                                p4.textContent += ".";
                                                terminalContainer.scrollTop = terminalContainer.scrollHeight;
                                                setTimeout(function() {
                                                    const p5 = document.createElement("p");
                                                    p5.style.color = "#0f0"; p5.style.margin = "0"; historyContainer.appendChild(p5);
                                                    typeText(p5, "> (1) SEÑAL ENCONTRADA", 15, function() {
                                                        setTimeout(function() {
                                                            // LIMPIAR TODO EL HISTORIAL ANTES DE ESCRIBIR EL MENSAJE FINAL
                                                            historyContainer.innerHTML = '';
                                                            
                                                            const finalP = document.createElement("p");
                                                            finalP.style.color = "#0f0";
                                                            finalP.style.margin = "0";
                                                            historyContainer.appendChild(finalP);
                                                            
                                                            // Escribir la línea final letra a letra
                                                            typeText(finalP, "> ENLACE ACEPTADO con usuario:A?1?p servidor:t?er?a", 15, function() {
                                                                if (callback) callback();
                                                            });
                                                        }, 1000);
                                                    });
                                                }, 1000);
                                            }, 1000);
                                        }, 1000);
                                    }, 1000);
                                });
                            }, 1000);
                        });
                    }, 1000);
                });
            }, 1000);
        });
    }

    function typeText(element, text, speed, callback) {
        let charIdx = 0;
        function type() {
            if (charIdx < text.length) {
                element.textContent += text.charAt(charIdx);
                charIdx++;
                terminalContainer.scrollTop = terminalContainer.scrollHeight;
                setTimeout(type, speed);
            } else {
                if (callback) callback();
            }
        }
        type();
    }

    inputField.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            if (isBusy) return; 

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
                    isBusy = true; 
                    inputField.disabled = true; 

                    promptSpan.textContent = USERNAME + "@atreia:"; 

                    runStartupSequence(function() {
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
