<div class="row">
  <div class="col-6">
    <section id="userTicket">
      <table id="userTicketTbl" class="table">
        <thead>
          <tr>
            <th>Objet</th>
            <th>Date</th>
            <th>Service destinataire</th>
            <th>Priorité</th>
            <th>Etat</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {LOOP:userTicket}
          <tr>
            <td>{#objetTicket#}</td>
            <td>{#dateTicket#}</td>
            <td>{#serviceTicket#}</td>
            <td>{#prioriteTicket#}</td>
            <td>{#lastStatut#}</td>
            <td><a name="seeTicket" idTicket="{#idTicket#}" class="btn btn-primary" href="#">voir</a></td>
          </tr>
          {/LOOP}
        </tbody>
      </table>
    </section>
    <button data-bs-toggle="modal" data-bs-target="#newTicket" class="btn btn-success">Nouveau Ticket</button>
  </div>
  {:IF {var:service.update_enable}=1}
  <div class="col-6">
    <section id="serviceTicket">
      <table id="serviceTicketTtbl" class="table">
        <thead>
          <tr>
            <th>Objet</th>
            <th>Date</th>
            <th>Service origine</th>
            <th>Priorité</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {LOOP:serviceTicket}
          <tr>
            <td>{#objetTicket#}</td>
            <td>{#dateTicket#}</td>
            <td>{#serviceTicket#}</td>
            <td>{#prioriteTicket#}</td>
            <td>
              <a data-bs-toggle="modal" data-bs-target="#manageTicket" name="manageTicket" idTicket="{#idTicket#}"
                class="btn btn-primary" href="#">voir</a>
              {:IF {#agentResponsable#}={var:user.idAgent}}
              <a data-bs-toggle="modal" data-bs-target="#manageTicket" name="handleTicket" idTicket="{#idTicket#}"
                class="btn btn-primary" href="#">Gérer</a>
              {:/IF}
            </td>
          </tr>
          {/LOOP}
        </tbody>
      </table>
    </section>
  </div>
  {:/IF}
</div>
<div class="modal fade" id="ServiceTicketMdl" tabindex="-1" aria-labelledby="ServiceTicketMdl" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ServiceTicketMdl">Tickets du service</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>Reférence</th>
              <th>Objet</th>
              <th>Date</th>
              <th>Service Destination</th>
              <th>Priorité</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {LOOP:serviceTicketFrom}
            <tr>
              <td>{#RefTicket#}</td>
              <td>{#objetTicket#}</td>
              <td>{#dateTicket#}</td>
              <td>{#serviceTicket#}</td>
              <td>{#prioriteTicket#}</td>
              <td>{#lastStatut#}</td>
              <td>
                <a data-bs-toggle="modal" data-bs-target="#manageTicket" name="manageTicket" idTicket="{#idTicket#}"
                  class="btn btn-primary" href="#">voir</a>
                {:IF {#agentResponsable#}={var:user.idAgent}}
                <a data-bs-toggle="modal" data-bs-target="#manageTicket" name="handleTicket" idTicket="{#idTicket#}"
                  class="btn btn-primary" href="#">Gérer</a>
                {:/IF}
              </td>
            </tr>
            {/LOOP}
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="addService" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
      </div>
    </div>
  </div>
</div>
<div id="seeTicket" class="offset-1 col-5">
  <h2>Voir le ticket</h2>
  <div class="mb-3">
    <span>date</span>
    <span data-ticket="dateTicket"></span>
  </div>
  <div class="mb-3">
    <span>Objet</span>
    <span data-ticket="objetTicket" class="editable"></span>
  </div>
  <div class="mb-3">
    <span>Service</span>
    <span data-ticket="libService"></span>
  </div>
  <div class="mb-3">
    <span>type</span>
    <span data-ticket="libType"></span>
  </div>
  <div class="mb-3">
    <h4>Données</h4>
    <ul data-ticket="dataTicket">

    </ul>
    <div class="input-group">
      <input type="text" id="data-name" aria-label="Nom" placeholder="Nom" class="form-control">
      <input type="text" id="data-value" aria-label="value" placeholder="valeur" class="form-control">
      <button class="btn btn-outline-secondary" id="addData" type="button">ajouter</button>
    </div>
  </div>
  <div class="mb-3">
    <span>Contenu</span>
    <p data-ticket="contentTicket" class="editable"></p>
  </div>
  <div class="mb-3">
    <h4>Etat du tickets</h4>
    <ul data-ticket="states">

    </ul>
  </div>
  <button class="btn btn-primary" name="updateTicket">Update</button>
</div>
</div>
<div class="modal fade" id="manageTicket" tabindex="-1" aria-labelledby="manageTicket" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

        <h1 class="modal-title fs-5" id="manageTicket">Gérer le ticket #<span manage-ticket="idTicket"></span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <span>date</span>
          <span manage-ticket="dateTicket"></span>
        </div>
        <div class="mb-3">
          <span>Objet</span>
          <span manage-ticket="objetTicket"></span>
        </div>
        <div class="mb-3">
          <span>Contenu : </span>
          <span manage-ticket="contentTicket"></span>
        </div>
        <div class="mb-3">
          <h4>Données</h4>
          <ul manage-ticket="dataTicket">
          </ul>
        </div>
        <div class="mb-3">
          <h4>Etat du tickets</h4>
          <ul manage-ticket="states">

          </ul>
        </div>
        <div class="mb-3" id="lastStateCompoment">
          <label class="form-label" for="StateTicket">Etat final :</label>
          <select class="form-control" name="lastState" id="StateTicket">
            <option value="NULL">Choisissez un état</option>
            <option value="4">Traité</option>
            <option value="5">Echoué</option>
          </select>
          <textarea class="form-control" name="commentFail" id="commentFail"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="validState" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="newTicket" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nouveau Ticket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="./newTicket">
          <div class="mb-3">
            <label for="objetNewTicket" class="form-label">Objet</label>
            <input type="text" maxlength="25" name="objetNewTicket" class="form-control" id="objetNewTicket"
              placeholder="objet du ticket">
          </div>
          <div class="mb-3">
            <label for="Service" class="form-label">Service</label>
            <select class="form-control" name="service" id="Service">
              <option value="NULL">Choisissez un service</option>
              {LOOP:SQL("SELECT * FROM service")}
              <option value="{#idService#}">{#libService#}</option>
              {/LOOP}
            </select>
          </div>
          <div class="mb-3">
            <label for="TypeTicket" class="form-label">Catégorie</label>
            <select class="form-control" name="typeTicket" id="TypeTicket">
              <option value="NULL">Choisissez un type de ticket</option>
              {LOOP:SQL("SELECT * FROM type_ticket")}
              <option value="{#idTypeTicket#}">{#libTypeTicket#}</option>
              {/LOOP}
            </select>
          </div>
          <div class="mb-3">
            <h4>Données</h4>
            <ul id="dataTicket">

            </ul>
            <div class="input-group">
              <input type="text" id="data-name" aria-label="Nom" placeholder="Nom" class="form-control">
              <input type="text" id="data-value" aria-label="value" placeholder="valeur" class="form-control">
              <button class="btn btn-outline-secondary" id="addDataNewTicket" type="button">ajouter</button>
            </div>
          </div>
          <div class="mb-3">
            <label for="contentNewTicket" class="form-label">Contenu : </label>
            <textarea name="contentNewTicket" class="form-control" id="contentNewTicket"></textarea>
          </div>
          <span class="result"></span>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="addTicket" class="btn btn-primary">Envoyer</button>
      </div>
    </div>
  </div>
</div>