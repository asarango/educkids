<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
?>
<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/basic/ckeditor.js"></script>



<div class="row" style="margin-bottom: 10px">
    <h5 style="margin-top: 10px; text-align: start;color:#0a1f8f">5.- INFORME PSICOPEDAGÓGICO</h5>
    <div class="col-lg-12 col-md-12 scroll-400" >
        <?php
        foreach ($opciones5 as $opcion) {
            ?>
            <div class="card shadow" style="padding: 15px; margin-left: 25px;margin-right: 25px; margin-bottom: 15px">
                <?= Html::beginForm(['update-sections', 'id' => $opcion['id']], 'post') ?>

                <label><strong><?= $opcion['codigo'] . '-' . $opcion['nombre'] ?></strong></label>
                <!--EDITOR DE TEXTO KARTIK-->
                <textarea name="contenido" id="editor<?= $opcion['id'] ?>"><?= $opcion['contenido'] ?></textarea>
                <script>
                    CKEDITOR.replace('editor<?= $opcion['id'] ?>', {
                        customConfig: '/ckeditor_settings/config.js'
                    });
                </script>

                <?= Html::input('hidden', 'id', $opcion['id']) ?>


                <div style="text-align: end; margin: 2px">
                    <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success submit my-text-medium']) ?>

                </div>
                <?= Html::endForm() ?>
            </div>
            <?php
        }
        ?>



    </div>
</div>



<script>
    function ajaxUpdateSections(id, obj) {
        var contenido = obj.value;
        alert(contenido);
//        var url = '<?= Url::to(['update-sections']) ?>';
//        var params = {
//            id: id,
//            contenido: contenido
//        };
//        
//        $.ajax({
//            data: params,
//            url: url,
//            type = 'POST',
//            beforeSend: function(){},
//            success: function(){
//                alert('funciono');
//            }
//        });
    }
</script>
