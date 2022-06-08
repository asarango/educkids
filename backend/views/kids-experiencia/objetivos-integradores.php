<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalObjetivo">
  Launch demo modal
</button>



    </div>
</div>


<!-- Muestra DataTable -->
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="table responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                       <th>OBJETIVO</th> 
                       <th>ACCION</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($objetivosSeleccionados as $seleccionado){
                        ?>
                        <tr>
                            <td><?='<strong>'.$seleccionado->objetivo->codigo.'</strong>'.$seleccionado->objetivo->detalle  ?></td>
                            <td><a type="button" onclick="elimina_objetivo(<?=$seleccionado->id?>)" class="link" >Eliminar</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Modal Agrega Objetivo-->
<div class="modal fade" id="modalObjetivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar objetivo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="table responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                       <th style="text-align:center" >OBJETIVO</th> 
                       <th style="text-align:center" >ACCION</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($objetivosDisponibles as $disponible){
                            ?>
                            <tr>
                                <td><?='<strong>'.$disponible['codigo'].'</strong>'.$disponible['detalle']?></td>
                                <?php $id = $disponible['id'];  ?>
                                <td><a type="button" class="link" onclick="inserta_objetivo(<?=$id?>)" >Insertar</a></td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>



<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script>
function inserta_objetivo(id){
    alert(id);
    var url = "<?= Url::to(['kids-experiencia/micro']) ?>";
    var microId = "<?= $micro['id'] ?>";
    var params = {
        objetivo_id : id,
        micro_id : microId,
        bandera : 'objetivo'
    };

    $.ajax({
        url: url,
        data: params,
        type: 'POST',
        beforeSend: function(){},
        success: function(){}
    });
}

function elimina_objetivo(id){
    var url = "<?= Url::to(['kids-experiencia/eliminaObjetivo']) ?>";
    var params = {
        id: id
    };

    $.ajax({
        url: url,
        data: params,
        type: 'POST',
        beforeSend: function(){},
        success: function(){}
    });
}
</script>