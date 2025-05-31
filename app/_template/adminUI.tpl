<div class="row">
  <div class="col-5">
    <h3>Managers</h3>
    <table class="table">
      <tr>
        <th>#</th>
        <th>UUID</th>
        <th>Ref</th>
        <th>Actions</th>
      </tr>
      {LOOP:SQL("SELECT * FROM agent_tbl WHERE typeAgent=2")}
      <tr>
        <td>{#idAgent#}</td>
        <td>{#uuidAgent#}</td>
        <td>{#refAgent#}</td>
        <td>
          <a href="#" idAgent="{#idAgent#}" name="seeAgent" data-bs-toggle="modal" data-bs-target="#seeManager"
            class="btn btn-primary">Voir</a>
        </td>
      </tr>
      {/LOOP}
    </table>
    <button data-bs-toggle="modal" data-bs-target="#newManager" class="btn btn-success">Nouveau Manager</button>
  </div>
  <div class="offset-1 col-5">

    <section>
      <h3>Services</h3>
      <table class="table">
        <tr>
          <th>#</th>
          <th>Nom du Service</th>
          <th>Actions</th>
        </tr>
        {LOOP:SQL("SELECT * FROM service WHERE isActif=1")}
        <tr>
          <td>{#idService#}</td>
          <td>{#libService#}</td>
          <td>
            <a data-bs-toggle="modal" name="seeService" idService="{#idService#}" data-bs-target="#seeService" href="#" class="btn btn-primary">Voir</a>
            <a href="#" idService="{#idService#}" name="delService" class="btn btn-danger">Supprimer</a>
          </td>
        </tr>
        {/LOOP}
      </table>
      <button data-bs-toggle="modal" data-bs-target="#newService" class="btn btn-success">Nouveau Service</button>
    </section>
    <section>
      <h3>Type de Ticket</h3>
      <table id="typeTicketTable" class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Libellé</th>
          </tr>
        </thead>
        <tbody>
          {LOOP:SQL("SELECT * FROM type_ticket")}
          <tr>
            <td>{#idTypeTicket#}</td>
            <td>{#libTypeTicket#}</td>
          </tr>
          {/LOOP}
        </tbody>
      </table>
      <div class="form">
        <div class="input-group mb-3">
          <input type="text" aria-label="Libellé Type" class="form-control" id="LibTypeTicket" name="libTypeTicket"
            placeholder="Libellé du Type de ticket">
          <button class="btn btn-success" type="button" id="addTypeTicket">Ajouter</button>
        </div>
      </div>
    </section>
  </div>
</div>
<!--Modals-->
<div class="modal fade" id="seeService" tabindex="-1" aria-labelledby="seeService" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="seeService">Voir le Service #<span data-service="idService"></span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label for="updateLibService" class="form-label">Nom du service : </label>
            <span id="updateLibService" data-service="libService" class="editable"></span>
          </div>
          <div class="mb-3">
            <label for="updateDescService" class="form-label">Description du service : </label>
            <p id="updateDescService" data-service="desc_service" class="editable"></p>
          </div>
          <div class="mb-3">
            <label for="updateDescService" class="form-label">Nombre de ticket d'origine : </label>
            <span id="updateDescService" data-service="ticketFrom"></span>
          </div>
          <div class="mb-3">
            <label for="updateDescService" class="form-label">Nombre de ticket destinaitaire : </label>
            <span id="updateDescService" data-service="ticketTo"></span>
          </div>
          <div class="mb-3">
            <label for="updateDescService" class="form-label">Peut créer des tickets : </label>
            <b id="updateDescService" data-service="create_enable" class="editable"></b>
          </div>
          <div class="mb-3">
            <label for="updateDescService" class="form-label">Peut mettre à jour les tickets : </label>
            <b id="updateDescService" data-service="update_enable" class="editable"></b>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="updateService" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="newService" tabindex="-1" aria-labelledby="newService" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newService">Nouveau Service</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="frmNewService" method="POST" action="./newService">
          <div class="mb-3">
            <label for="nomService" class="form-label">Nom du Service : </label>
            <input type="text" name="nomService" class="form-control" id="nomService" placeholder="Nom du Service">
          </div>
           <div class="mb-3">
            <label for="refService" class="form-label">Ref Service : </label>
            <input type="text" maxlength="5" name="refService" class="form-control" id="refService" placeholder="Nom du Service">
          </div>
          <div class="mb-3">
            <label for="descService" class="form-label">Description du Service : </label>
            <input type="text" name="descService" class="form-control" id="descService"
              placeholder="description du Service">
          </div>
          <div class="mn-3">
            <label class="form-label" for="serviceParent">Est un prestataire de :</label>
            <select class="form-control" name="service_parent" id="serviceParent">
              <option value="NULL">Séléctionnez un service</option>
              {LOOP:SQL("SELECT * FROM service WHERE parent_service IS NULL")}
              <option value="{#idService#}">{#libService#}</option>
              {/LOOP}
            </select>
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" name="createEnable" type="checkbox" value="" id="createEnable">
              <label class="form-check-label" for="createEnable">
                Création
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" name="updateEnable" type="checkbox" value="" id="updateEnable">
              <label class="form-check-label" for="updateEnable">
                Mise à jour
              </label>
            </div>
          </div>
          <div class="mb-3">
            <label for="selectedManager">Choisir un manager</label>
            <select name="selectedManager" id="selectedManager" class="form-control">
              <option value="null">Séléctionner un Manager</option>
              {LOOP:SQL("SELECT * FROM agent_tbl WHERE typeAgent=2")}
              <option value="{#idAgent#}">{#NomAgent#} {#PrenomAgent#}</option>
              {/LOOP}
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="addService" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="seeManager" tabindex="-1" aria-labelledby="seeManager" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="seeManager">Manager #<span data-agent="idAgent"></span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="./newAgent">
          <div class="mb-3">
            <label for="nomAgent" class="form-label">Nom : </label>
            <span id="UpdatenomAgent" data-agent="NomAgent" class="editable"></span>
          </div>
          <div class="mb-3">
            <label for="PrenomAgent" class="form-label">Prénom : </label>
            <span data-agent="PrenomAgent" class="editable"></span>
          </div>
          <div class="mb-3">
            <label for="mailAgent" class="form-label">Mail : </label>
            <span data-agent="mailAgent" class="editable"></span>
          </div>
          <div class="mb-3">
            <label for="refAgent" class="form-label">Ref : </label>
            <span data-agent="refAgent"></span>
          </div>
          <div class="mb-3">
            <label class="form-label">Valdité : </label>
            <span data-agent="validiteMdp"></span>
          </div>
          <div class="mb-3">
            <label for="service" class="form-label">Service : </label>
            <span data-agent="service" class="editable"></span>
          </div>
          <div class="mb-3" id="managerList">
            <label for="MainManager" class="form-label">Manager : </label>
            
          </div>
          <div class="mb-3">
            <label for="blockAgent" class="form-label">Blocage : </label>
            <span id="blockAgent" data-agent="blockAgent" class="editable"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="updateAgent" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="newManager" tabindex="-1" aria-labelledby="newManager" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newManager">Nouveau Manager</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="frmNewAgent" method="POST" action="./newAgent">
          <div class="mb-3">
            <label for="nomAgent" class="form-label">Nom : </label>
            <input type="text" name="nomAgent" class="form-control" id="nomAgent" placeholder="Nom de l'Agent">
          </div>
          <div class="mb-3">
            <label for="PrenomAgent" class="form-label">Prénom : </label>
            <input type="text" name="prenomAgent" class="form-control" id="PrenomAgent" placeholder="Prénom de l'Agent">
          </div>
          <div class="mb-3">
            <label for="mailAgent" class="form-label">Mail : </label>
            <input type="mail" name="mailAgent" class="form-control" id="mailAgent" placeholder="Mail de l'Agent">
          </div>
          <div class="mb-3">
            <label for="refAgent" class="form-label">ref agent</label>
            <input type="text" size="5" maxlength="5" name="refAgent" class="form-control" id="refAgent"
              placeholder="Ref agent">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="addManager" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>