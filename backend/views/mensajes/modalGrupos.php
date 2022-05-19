<!-- Button trigger modal -->
<button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#modalGrupos">
  Selección de grupos
</button>

<!-- Modal -->
<div class="modal fade" id="modalGrupos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <text>Enviar notificación a grupos:</text>
            <select name="a_quien" id="" class="form-control" onchange="elije_quien(this)">
              <option selected>Seleccione a quién se envía...</option>
              <option value="PADRE_FAMILIA">PADRE_FAMILIA</option>
              <option value="ESTUDIANTES">ESTUDIANTES</option>
              <option value="DOCENTES">DOCENTES</option>
            </select>

            <input name="prueba" onkeypress="seleccionar_grupos(this)" class="form-control">
            <div class="" id="div-grupos">
                
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


