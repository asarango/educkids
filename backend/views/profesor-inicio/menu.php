<ol class="list-group list-group-numbered" style="margin-top: 5px; margin-bottom: 5px">
    <?php
    foreach ($clases as $clase) {
        ?>
        <li class="list-group-item list-group-item-action  d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold"><?= $clase['materia'] ?></div>
                <?= $clase['curso'] ?>
            </div>
            <a href="#" onclick="muestra_detalle_curso(<?= $clase['clase_id'] ?>)">
               <span class="badge bg-primary rounded-pill zoom"><?= $clase['paralelo'] ?></span>
            </a>            
        </li>
        <?php
    }
    ?>
</ol>

<!----------------------------->
<script>
    function muestra_detalle_curso(claseId){
        muestra_detalle(claseId, 'bloques');
    }
</script>