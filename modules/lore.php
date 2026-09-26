<?php
/**
 * AionCMS Lore
 */
?>
<div class="page-header-block info"></div>
<div class="server-info-container" style="padding: 20px;">

    <h2>Lore del Juego</h2>

    <div id="lore-accordion">
        <h3 class="lore-chapter" style="cursor: pointer; background: #222; padding: 10px; margin-bottom: 5px; color: #d6be93;">
            Capítulo 1
        </h3>
        <div class="lore-content" style="padding: 10px; display: none; background: #111; color: #ddd; margin-bottom: 15px;">
            <p>"Las cosas han cambiado, [%username]. Te metiste en una máquina de criogenización junto a tu pareja justo antes del desastre. El maldito Pedro Sánchez empezó una guerra nuclear con Estados Unidos, y Trump se equivocó y tiró una bomba atómica en Marruecos. Descubrimos este nuevo planeta, empezamos de cero, y hay algo en el aire que permite que hagamos magia."</p>
        </div>

        <!-- More chapters can be added here following the same structure -->
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
