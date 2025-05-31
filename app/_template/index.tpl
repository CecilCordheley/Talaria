<h3>Bienvenue</h3>
<nav id="userNav" class="nav nav-pills nav-fill">
  <a class="nav-link active" data-target="userTicket" aria-current="page" href="#">Vos Tickets</a>
  <a class="nav-link"  data-target="userManger"  href="#">Gestion des utilisateurs</a>
  <a class="nav-link"  data-target="ticketManger"  href="#">Gestion des tickets</a>
  <a class="nav-link"  data-target="serviceManager" href="#">Gestion des services</a>
</nav>
<section data-name="userTicket">
  <h4>Vos tickets</h4>
  <table class="table">
      <thead>
          <tr>
              <th>Objet</th>
              <th>Date</th>
              <th>Service</th>
              <th>Priorité</th>
              <th></th>
          </tr>
      </thead>
      <tbody>
          {LOOP:ticket}
          <tr>
              <td>{#objetTicket#}</td>
              <td>{#dateTicket#}</td>
              <td>{#serviceTicket#}</td>
              <td>{#prioriteTicket#}</td>
              <td><a data-bs-toggle="modal" name="seeTicket" idTicket="{#idTicket#}" data-bs-target="#seeTicket" class="btn btn-primary" href="#">voir</a></td>
          </tr>
          {/LOOP}
      </tbody>
  </table>
</section>
<section data-name="userManger">
  <h4>Gestion des utilisateurs</h4>
  {var:managerUser}
</section>
<section data-name="ticketManger">
  <h4>Gestion des tickets du service {var:user.LibService}</h4>
  {var:managerTicket}
</section>
<section data-name="serviceManager">
  <h4>Gestion des services</h4>
</section>
<div class="modal" id="seeTicket" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Voir le ticket #<span data-ticket="idTicket"></span></h5>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <span>Objet</span>
          <span data-ticket="dateTicket"></span>
        </div>
        <div class="mb-3">
          <span>Objet</span>
          <span data-ticket="objetTicket"></span>
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
          <h4>Etat du tickets</h4>
          <ul data-ticket="states">

          </ul>
        </div>
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
                <input type="text" name="objetNewTicket" class="form-control" id="objetNewTicket" placeholder="objet du ticket">
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
              <label for="TypeTicket" class="form-label">Service</label>
              <select class="form-control" name="typeTicket" id="TypeTicket">
                <option value="NULL">Choisissez un type de ticket</option>
                {LOOP:SQL("SELECT * FROM type_ticket")}
                <option value="{#idTypeTicket#}">{#libTypeTicket#}</option>
                {/LOOP}
              </select>
            </div>
            <div class="mb-3">
                <label for="contentNewTicket" class="form-label">Contenu : </label>
                <textarea name="contentNewTicket" class="form-control" id="contentNewTicket"></textarea>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" id="addTicket" class="btn btn-primary">Envoyer</button>
        </div>
      </div>
    </div>
  </div>
  