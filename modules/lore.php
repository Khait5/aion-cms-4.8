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

    <h2>Lore del Juego</h2>

    <div id="lore-accordion">
        <h3 class="lore-chapter" style="cursor: pointer; background: #222; padding: 10px; margin-bottom: 5px; color: #d6be93;">
            Introducción
        </h3>
        <div class="lore-content" style="padding: 15px; display: none; background: #111; color: #ddd; margin-bottom: 15px; line-height: 1.6;">
            "Las cosas han cambiado, <?php echo $_SESSION['username']; ?>.<br><br>
            Te metiste en una máquina de criogenización junto a tu pareja justo antes del desastre.<br>
            El maldito Pedro Sánchez empezó una guerra nuclear con Estados Unidos, y Trump se equivocó y tiró una bomba atómica en Marruecos.<br><br>
            Descubrimos este nuevo planeta, empezamos de cero, y hay algo en el aire que permite que hagamos magia.<br><br>
            Este nuevo planeta se llama Atreia"
        </div>

        <h3 class="lore-chapter" style="cursor: pointer; background: #222; padding: 10px; margin-bottom: 5px; color: #d6be93;">
            Atreia
        </h3>
        <div class="lore-content" style="padding: 15px; display: none; background: #111; color: #ddd; margin-bottom: 15px; line-height: 1.6;">
            "Este planeta es diferente a la tierra.<br>
            Nuestra capa de ozono no existe, pero hay una gran burbuja mágica que sostiene el oxigeno.<br>
            De hecho, ni hay oxigeno, hay magia.<br><br>
            Particulas de magia que permiten a nuestro cuerpo sobrevivir sin lo que nosotros conocemos por oxigeno.<br><br>
            Dichas particulas convierten a los humanos y cualquier ser vivo en ente viviente con la capacidad de canalizar y absorver dichas particulas, para otros fines.<br><br>
            Los cienttificos empezaron a investigar con que fines podrían usarlos."
        </div>

        <h3 class="lore-chapter" style="cursor: pointer; background: #222; padding: 10px; margin-bottom: 5px; color: #d6be93;">
            Diferencias
        </h3>
        <div class="lore-content" style="padding: 15px; display: none; background: #111; color: #ddd; margin-bottom: 15px; line-height: 1.6;">
            "Y así empezaron los problemas.<br><br>
            Empezamos a descubrir que estas particulas eran mas útiles de lo que pensabamos.<br>
            Podrian sustituir a la electricidad, a la mismisma luz, a ingredientes de comida, a combustible, hasta a las hormonas, podían prevenir y bloquear cancer y enfermedades graves.<br>
            Fue un avance cientifico y tecnológico que jamás pudimos esperar.<br><br>
            Pero no todos pensaron igual.<br><br>
            Un grupo, empezó a investigar estas particulas en sus cuerpos, en como canalizarlas, como usarlas como energia pero en sus propios cuerpos.<br>
            Si podia dar luz a una lampara, o dar fuego a una hoguera, ¿porque no canalizarla en el cuerpo humano?<br>
            Y así, se creo la magia de combate.<br><br>
            Creamos instituciones de magia, magos, guerreros, todo tipo de clases de combate que podían canalizar estas particulas en combate.<br><br>
            Pero poco despues, la población se dividió en dos."
        </div>
        
         <h3 class="lore-chapter" style="cursor: pointer; background: #222; padding: 10px; margin-bottom: 5px; color: #d6be93;">
            La separación
        </h3>
        <div class="lore-content" style="padding: 15px; display: none; background: #111; color: #ddd; margin-bottom: 15px; line-height: 1.6;">
            "Unos querian seguir el camino de la tecnología y ciencia, y veían el camino militar como algo en lo que no se debería usar, algo peligroso.<br>
            Sobre todo en usarlos en cuerpos humanos.<br><br>
            Pero eso no les detuvo, hasta que tras experimetnos mas exhaustivos, empezaron a acabar con vidas de estudiantes en las academias, pues era parte del proceso, segun ellos.<br><br>
            Eso hizo que los mas amantes de la tecnología y ciencia, enfurecieran, jovenes con futuros brillantes muertos y carbonizados para aprender a usar un hechizo que solo creaba destrucción.<br><br>
            Así empezo la separación, la humanidad se separó en Elyos, y Asmodians.<br><br>
            Elyos, inteligentes, Asmodians, guerreros expertos, libraron una guerra de facciones que partió atreia en dos y hizo despertar a otras facciones que permanecian dormidas."
        </div>
        
         <h3 class="lore-chapter" style="cursor: pointer; background: #222; padding: 10px; margin-bottom: 5px; color: #d6be93;">
            Rozando la extinción.
        </h3>
        <div class="lore-content" style="padding: 15px; display: none; background: #111; color: #ddd; margin-bottom: 15px; line-height: 1.6;">
            "En apenas 3 meses, una guerra empezó.<br>
            Todo el progreso que hicieron juntos, los humanos lo rompieron.<br>
            Ciudades destruidas.<br>
            Libros, academias, calles, casas, familias.<br>
            Todo destruido por la supervivencia de una de las dos facciones.<br><br>
            Aunque los Asmodians eran fuertes, los Elyos tenian armas de fuego, potenciar herramientas para uso de batalla, algo que ellos estudiaron antes de que todo sucediera, para confrontar a los Asmodians.<br>
            Los Asmodians robaron tecnologias y avances, todo quedando en un empate sin fin de guerra.<br><br>
            Así que ambas facciones, tomaron un interes común y dijeron:<br>
            Atreia Este es para vosotros,<br>
            Atreia Oeste es para nosotros.<br><br>
            Y así fue.<br>
            Con casi mas de la mitad de la humanidad destruida,<br>
            Fueron a lo que nunca antes hicieron desde que llegaron, explorar, y colonizar todo el planeta."
        </div>
                    
         <h3 class="lore-chapter" style="cursor: pointer; background: #222; padding: 10px; margin-bottom: 5px; color: #d6be93;">
            No estamos solos.
        </h3>
        <div class="lore-content" style="padding: 15px; display: none; background: #111; color: #ddd; margin-bottom: 15px; line-height: 1.6;">
            "Así fue como la humanidad se dio cuenta de algo importante que obvió,<br>
            No estamos solos.<br><br>
            Muchas otras facciones de monstruos que ya habitaban el planeta y vigilaban a los humanos desde hace mucho, surgieron a intentar acabar lo que ellos mismos casi consiguen, la destrucción.<br><br>
            Pero una facción fue la mas ruidosa; Balaur."
        </div>
                    
         <h3 class="lore-chapter" style="cursor: pointer; background: #222; padding: 10px; margin-bottom: 5px; color: #d6be93;">
            Okupas
        </h3>
        <div class="lore-content" style="padding: 15px; display: none; background: #111; color: #ddd; margin-bottom: 15px; line-height: 1.6;">
            "Es una raza ancestral de poderosos dragones surgida originalmente para gobernar el planeta.<br>
            Sin embargo, se corrompieron por su propio ansia de poder y también, cayeron en guerras entre ellos.<br><br>
            Pero ellos fueron mas inteligentes que los humanos,<br>
            Pues ellos,<br>
            Se unieron para atacarnos a ambas partes a la vez.<br>
            Pues ellos eran dueños del planeta, por derecho.<br>
            Y nosotros,<br>
            Solo vinimos a okuparlo porque el nuestro se destruyó."
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var chapters = document.querySelectorAll(".lore-chapter");
    chapters.forEach(function(chapter) {
        chapter.addEventListener("click", function() {
            var content = this.nextElementSibling;

            // If the clicked one is already visible, hide it
            if(content.style.display === "block") {
                content.style.display = "none";
                return;
            }

            // Hide all contents
            var allContents = document.querySelectorAll(".lore-content");
            allContents.forEach(function(c) {
                c.style.display = "none";
            });

            // Show the clicked one
            content.style.display = "block";
        });
    });
});
</script>
<br /><br />
