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
<div class="page-header-block "></div>

<!-- Estilos específicos para simular un libro antiguo de atreia -->
<style>
.book-container {
    background-color: #12100e;
    background-image: radial-gradient(circle, #241d17 0%, #0c0a08 100%);
    border: 3px solid #5a452a;
    box-shadow: inset 0 0 40px rgba(0,0,0,0.8), 0 10px 25px rgba(0,0,0,0.6);
    border-radius: 6px;
    padding: 30px;
    color: #d8c7a7;
    font-family: "Georgia", "Times New Roman", serif;
    max-width: 900px;
    margin: 0 auto;
}

.book-title {
    text-align: center;
    color: #e6cb88;
    font-size: 28px;
    letter-spacing: 2px;
    border-bottom: 2px solid #5a452a;
    padding-bottom: 15px;
    margin-bottom: 25px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.9);
}

.lore-chapter {
    background: linear-gradient(to right, #2c2217, #19120c);
    border-left: 4px solid #c5a059;
    border-top: 1px solid #483723;
    border-bottom: 1px solid #483723;
    color: #e6cb88;
    padding: 12px 18px;
    margin-bottom: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.lore-chapter:hover {
    background: linear-gradient(to right, #3d3021, #241c13);
    color: #fff;
    padding-left: 24px;
}

.lore-content {
    background-color: #080706;
    border: 1px solid #36291a;
    border-top: none;
    padding: 25px;
    display: none;
    color: #c9bc9c;
    margin-bottom: 15px;
    line-height: 1.8;
    font-size: 15px;
    box-shadow: inset 0 0 15px rgba(0,0,0,0.9);
}

.lore-content p {
    text-indent: 25px;
    margin: 0 0 15px 0;
}
</style>

<div class="server-info-container" style="padding: 20px;">
    <div class="book-container">
        
        <div class="book-title">📖 ARCHIVOS DEL CÓDEX: HISTORIA DE ATREIA</div>

        <div id="lore-accordion">
            <div class="lore-chapter">Introducción</div>
            <div class="lore-content">
                "Las cosas han cambiado, <?php echo $_SESSION['username']; ?>.<br><br>
                Te metiste en una máquina de criogenización junto a tu pareja justo antes del desastre.<br>
                El maldito Pedro Sánchez empezó una guerra nuclear con Estados Unidos, y Trump se equivocó y tiró una bomba atómica en Marruecos.<br><br>
                Descubrimos este nuevo planeta, empezamos de cero, y hay algo en el aire que permite que hagamos magia.<br><br>
                Este nuevo planeta se llama Atreia"
            </div>

            <div class="lore-chapter">Atreia</div>
            <div class="lore-content">
                "Este planeta es diferente a la tierra.<br>
                Nuestra capa de ozono no existe, pero hay una gran burbuja mágica que sostiene el oxigeno.<br>
                De hecho, ni hay oxigeno, hay magia.<br><br>
                Particulas de magia que permiten a nuestro cuerpo sobrevivir sin lo que nosotros conocemos por oxigeno.<br><br>
                Dichas particulas convierten a los humanos y cualquier ser vivo en ente viviente con la capacidad de canalizar y absorver dichas particulas, para otros fines.<br><br>
                Los cienttificos empezaron a investigar con que fines podrían usarlos."
            </div>

            <div class="lore-chapter">Diferencias</div>
            <div class="lore-content">
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
            
            <div class="lore-chapter">La separación</div>
            <div class="lore-content">
                "Unos querian seguir el camino de la tecnología y ciencia, y veían el camino militar como algo en lo que no se debería usar, algo peligroso.<br>
                Sobre todo en usarlos en cuerpos humanos.<br><br>
                Pero eso no les detuvo, hasta que tras experimetnos mas exhaustivos, empezaron a acabar con vidas de estudiantes en las academias, pues era parte del proceso, segun ellos.<br><br>
                Eso hizo que los mas amantes de la tecnología y ciencia, enfurecieran, jovenes con futuros brillantes muertos y carbonizados para aprender a usar un hechizo que solo creaba destrucción.<br><br>
                Así empezo la separación, la humanidad se separó en Elyos, y Asmodians.<br><br>
                Elyos, inteligentes, Asmodians, guerreros expertos, libraron una guerra de facciones que partió atreia en dos y hizo despertar a otras facciones que permanecian dormidas."
            </div>
            
            <div class="lore-chapter">Rozando la extinción</div>
            <div class="lore-content">
                "En apenas 3 meses, una guerra empezó.<br>
                Todo el progreso que hicieron juntos, los humanos lo rompieron.<br>
                Ciudades destruidas.<br>
                Libros, academias, calles, casas, familias.<br>
                Todo destruido por la supervivencia de una de las dos facciones.<br><br>
                Aunque los Asmodians eran fuertes, los Elyos tenian armas de fuego, potenciar herramientas para uso de batalla, algo que ellos estudiaron antes de que todo sucediera, para confrontar a los Asmodians.<br>
                Los Asmodians robaron tecnologias y avances, todo quedando en un empate sin fin de guerra, y se dió, una paz temporal.<br><br>
                Una paz temporal, entre los unicos que sobrevivieron, los hispanohablantes.<br>
                Todos somos humanos, todos usamos lo mismo, investiguemos juntos en nuestras partes del planeta, y compartamos nuestros descubrimientos.<br>
                Con mas de la mitad de la ultima humanidad destruida, se eligieron a los 12 Señores de los Cumulos, los 12 humanos mas fuertes que quedaban, 6 Eyos, y 6 Asmodianos, creando el Consejo de la Ultima Humanidad,<br>
                Para poder elegir en voto igual y decidido, que hacer, como hacerlo y donde.<br>
                Así que ambas facciones, representadas por el consejo, tomaron un interes común y dijeron:<br>
                Atreia Este es para vosotros,<br>
                Atreia Oeste es para nosotros.<br><br>
                Y así, fueron a lo que nunca antes hicieron desde que llegaron, explorar, y colonizar todo el planeta."
            </div>
                        
            <div class="lore-chapter">No estamos solos</div>
            <div class="lore-content">
                "Así fue como la humanidad se dio cuenta de algo importante que obvió,<br>
                No estamos solos.<br><br>
                Muchas otras facciones de monstruos que ya habitaban el planeta y vigilaban a los humanos desde hace mucho, surgieron a intentar acabar lo que ellos mismos casi consiguen, la destrucción.<br><br>
                Pero una facción fue la mas ruidosa; Balaur."
            </div>
                        
            <div class="lore-chapter">Okupas</div>
            <div class="lore-content">
                "Es una raza ancestral de poderosos dragones surgida originalmente para gobernar el planeta.<br>
                Sin embargo, se corrompieron por su propio ansia de poder y también, cayeron en guerras entre ellos.<br><br>
                Pero ellos fueron mas inteligentes que los humanos,<br>
                Pues ellos,<br>
                Se unieron para atacarnos a ambas partes a la vez.<br>
                Pues ellos eran dueños del planeta, por derecho.<br>
                Y nosotros,<br>
                Solo vinimos a okuparlo porque el nuestro se destruyó."
            </div>

            <div class="lore-chapter">La Torre de la eternidad</div>
            <div class="lore-content">
                "Explorando, nos dimos cuenta que en cierto punto del planeta, surgía muchas particulas magicas.<br>
                Hasta el punto que alteraban nuestras herramientas y los hechizos que aprendimos, ya no funcionaban.<br>
                El aire se condensaba, costaba respirar, así fue como encontramos la torre.<br><br>
                Asi la llamaron, la torre de la eternidad, el nucleo del planeta, lo que sostenia el planeta unido y generaba las particulas mágicas.<br><br>
                Sin esa torre, el planeta se destruiría, y los humanos también, gracias a esa torre podiamos vivir, respirar, protegernos y usar la magia a nuestro antojo en nuestro día a día.<br><br>
                Y los balaur, lo sabian."
            </div>

            <div class="lore-chapter">El camino a la locura</div>
            <div class="lore-content">
                "Los balaur, como la raza superior para proteger y gobernar el planeta, se corrompieron por una insaciable ansia de poder absoluto.<br>
                No les bastaba con reinar sobre las demás criaturas; querían subyugar al propio 'Dios' del planeta, la torre.<br>
                Controlar la torre significaba adueñarse de la fuente de las particulas de todo el planeta, lo que les otorgaría un poder divino e ilimitado para moldear Atreia a su antojo y destruir a cualquiera que se les opusiera.<br>
                Y así fue, como los balaur, ansiosos de poder, atacaron la torre con todas sus fuerzas."
            </div>

            <div class="lore-chapter">Gran Cataclismo</div>
            <div class="lore-content">
                "Tras 5 años de una cruenta guerra de desgaste entre los Balaur y los humanos,<br>
                en un intento desesperado por lograr la paz, el Señor Israel propuso un tratado de paz con los Balaur, todo el Consejo se puso de acuerdo, y los Balaur también se abrieron a negociar.<br>
                Sin embargo, durante las negociaciones dentro de la barrera de la torre, la tensión estalló: uno de los líderes Balaur fue asesinado bajo circunstancias misteriosas.<br>
                Creyéndose traicionados, los Balaur atacaron con furia ciega, logrando destruir el núcleo de la Torre de la Eternidad.<br><br>
                La destrucción de la torre desató una inmensa inestabilidad energética que comenzó a desintegrar el planeta.<br>
                Para salvar el mundo, dos de los Señores de los Cúmulos sacrificaron sus propias vidas para proyectar una barrera mágica que detuvo la destrucción absoluta,<br>
                pero el daño ya estaba hecho: la sección central de la torre se convirtió en un campo de escombros flotantes conocido como el Abismo, y el planeta quedó fragmentado para siempre en dos mitades:<br>
                La luminosa Elysea y la oscura Asmodae, dando origen al odio eterno entre Elyos y Asmodianos, echandose las culpas uno al otro de quien fué que ataco a los balaur,<br>
                originando la muerte de 2 de los 12 Señores del Cumulo, de los humanos mas fuertes jamas conocidos, dejandoles debiles, contra los balaur, los cuales ganaron más fuerza que nunca.<br>
                Y este suceso es lo que llamamos El Gran Cataclismo."
            </div>

            <div class="lore-chapter">El Presente lleno de traiciones</div>
            <div class="lore-content">
                "Y así fue cuando despiertas sin saber por qué, de tu capsula perdida.<br>
                Con los recuerdos de uno de los Señores que murieron en tu cerebro.<br>
                Es como si el mismo se hubiera inyectado en ti, para que lograras algo mas que una falsa paz.<br>
                Tal vez, ¿Destapar una mentira?<br>
                ¿Quien mató a Israel?<br>
                ¿Porqué lo hizo?<br><br>
                Casualmente en tu capsula, había una terminal que trajiste de la tierra, una terminal que teoricamente debías usar si encontrabas un planeta habitable, para que el resto de naves te siguieran.<br>
                Pero te das cuenta que tu no debias tener eso en tus cosas.<br>
                Algo fue mal, algo no debió pasar así, alguien queria que cuando se pudiera avisar al resto,<br>
                Fuera demasiado tarde."
            </div>
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
