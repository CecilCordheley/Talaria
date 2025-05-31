 "SELECT * FROM agent_tbl WHERE service={var:user.service}"
 <table class="table">
      <thead>
          <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Priorité</th>
          </tr>
      </thead>
      <tbody>
          {LOOP:SQL("SELECT * FROM agent_tbl WHERE service={var:user.service}")}
          <tr>
              <td>{#idAgent#}</td>
              <td>{#NomAgent#}</td>
              <td>{#PrenomAgent#}</td>
              <td>{#mailAgent#}</td>
          </tr>
          {/LOOP}
      </tbody>
  </table>
  <button data-bs-toggle="modal" data-bs-target="#newAgent" class="btn btn-primary">Nouvel agent</button>
  <div class="modal" id="newAgent" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nouvel Agent</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="./newAgent">
            <div class="mb-3">
                <label for="nomAgent" class="form-label">Nom : </label>
                <input type="text" name="nomAgent" class="form-control" id="nomAgent" placeholder="Nom de l'Agent">
            </div>
            <div class="mb-3">
                <label for="PrenomAgent" class="form-label">Prénom : </label>
                <input type="text" name="PrenomAgent" class="form-control" id="PrenomAgent" placeholder="Prénom de l'Agent">
            </div>
            <div class="mb-3">
                <label for="mailAgent" class="form-label">Mail : </label>
                <input type="mail" name="mailAgent" class="form-control" id="mailAgent" placeholder="Mail de l'Agent">
            </div>
            <div class="mb-3">
                <label for="refAgent" class="form-label">Prénom</label>
                <input type="text" size="5" maxlength="5" name="mailAgent" class="form-control" id="mailAgent" placeholder="Ref agent">
            </div>
            <div class="mb-3">
                <label for="typeAgent">Type : </label>
                <select class="form-control" name="typeAgent" id="typeAgent">
                    {LOOP:SQL("SELECT * FROM type_agent")}
                    <option value="{#idTypeAgent#}">{#libTypeAgent#}</option>
                    {/LOOP}
                </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" id="addAgent" class="btn btn-primary">Envoyer</button>
        </div>
      </div>
    </div>
  </div>
  