<div class="row">
    <div class="col-6">
        <h2>Gestion des utilisateur du service</h2>
        <table class="table">
            <tr>
                <th>#</th>
                <th>UUID</th>
                <th>REF</th>
                <th>Nom Prénom</th>
                 <th>Service</th>
                <th>Actions</th>
            </tr>
            {LOOP:userList}
            <tr>
                <td>{#idAgent#}</td>
                <td>{#uuidAgent#}</td>
                <td>{#refAgent#}</td>
                <td>{#NomAgent#} {#PrenomAgent#}</td>
                 <td>{#libService#}</td>
                <td>actions</td>
            </tr>
            {/LOOP}
        </table>
        <button data-bs-toggle="modal" data-bs-target="#newAgent" class="btn btn-success">Nouvel Agent</button>
    </div>
    <div class="col-6">
        <h2>Gestion des tickets</h2>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" data-nav-target="ticketFrom" aria-current="page" href="#">Origine</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-nav-target="ticketTo" href="#">Destination</a>
            </li>
        </ul>
        <section id="ticketFrom">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Objet</th>
                        <th>Destination</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {LOOP:ticketFrom}
                    <tr ticket={#idTicket#}>
                        <td>{#idTicket#}</td>
                        <td>{#objetTicket#}</td>
                        <td>{#libService#}</td>
                        <td>{#lastStatut#}</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#seeTicket" class="btn btn-primary"
                                idTicket="{#idTicket#}" name="seeTicket">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="#" class="btn btn-success" name="validTicket">
                                <i class="fa-solid fa-square-check"></i>
                            </a>
                            <a href="#" class="btn btn-danger" name="rejectTicket">
                                <i class="fa-solid fa-rectangle-xmark"></i>
                            </a>
                        </td>
                    </tr>
                    {/LOOP}
                </tbody>
            </table>
        </section>
        <section id="ticketTo">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Objet</th>
                        <th>Destination</th>
                        <th>Status</th>
                        <th>Assignation</th>
                    </tr>
                </thead>
                <tbody>
                    {LOOP:ticketTo}
                    <tr ticket={#idTicket#}>
                        <td>{#idTicket#}</td>
                        <td>{#objetTicket#}</td>
                        <td>{#libService#}</td>
                        <td>{#lastStatut#}</td>
                        <td>
                            {:IF {#agentResponsable#}="NULL"}
                            <select idTicket="{#idTicket#}" name="assignAgent" class="form-control">
                                <option value="NULL">Assigner un agent</option>
                                {LOOP:agentAssign}
                                <option value="{#idAgent#}">{#refAgent#}</option>
                                {/LOOP}
                            </select>
                            {:ELSE:}
                            <span>{#refAgent#}</span>
                            {:/IF}
                        </td>
                    </tr>
                    {/LOOP}
                </tbody>
            </table>
        </section>
    </div>
    <div id="console" class="alert alert-dark" role="alert">
        A simple dark alert—check it out!
    </div>
</div>
<div class="modal fade" id="Licence" tabindex="-1" aria-labelledby="Licence" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="modal-title fs-5" id="Licence">Licences<span
                        class="badge text-bg-secondary">{var:user.licences}</span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <table class="table">
                            <tr>
                                <th>UUID</th>
                                <th colspan="2">Activée</th>
                            </tr>
                            {LOOP:licences}
                            <tr>
                                <td>{#uuidLicence#}</td>
                                <td>{#estActive#}</td>
                                <td><button class="btn btn-primary" name="useLicence" idLicence="{#uuidLicence#}">Utiliser</button></td>
                            </tr>
                            {/LOOP}
                        </table>
                    </div>
                    <div class="col-5" id="useLicenceFrm">
                        <div class="mb-3">
                            <label class="form-label" for="TypeCible">Type :</label>
                            <select name="type_cible" id="TypeCible" class="form-control">
                                <option value="NULL">Séléctionner une cible</option>
                                {LOOP:TypeCible}
                                <option value="{#name#}">{#name#}</option>
                                {/LOOP}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idCible" class="form-label">Cible : </label>
                            <select name="id_cible" id="idCible" class="form-control">
                                <option value="NULL">Séléctionner une cible</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <h4>Action</h4>
                            <label for="actionLicence" class="form-label">Action</label>
                            <select name="action_licence" id="actionLicence" class="form-control">
                                <option value="NULL">Séléctionner une type de cible</option>

                            </select>
                        </div>
                        <div class="mb-3" id="LicenceParams">
                            <h4>Paramètre</h4>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Commentaire</label>
                            <textarea class="form-control" name="licence_comment" id="comment"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="useLicence" class="btn btn-primary" data-bs-dismiss="modal">Utiliser</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="newAgent" tabindex="-1" aria-labelledby="newAgent" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newAgent">Nouvel Agent</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmNewAgent" method="POST" action="./newAgent">
                    <div class="mb-3">
                        <label for="nomAgent" class="form-label">Nom : </label>
                        <input type="text" name="nomAgent" class="form-control" id="nomAgent"
                            placeholder="Nom de l'Agent">
                    </div>
                    <div class="mb-3">
                        <label for="PrenomAgent" class="form-label">Prénom : </label>
                        <input type="text" name="prenomAgent" class="form-control" id="PrenomAgent"
                            placeholder="Prénom de l'Agent">
                    </div>
                    <div class="mb-3">
                        <label for="mailAgent" class="form-label">Mail : </label>
                        <input type="mail" name="mailAgent" class="form-control" id="mailAgent"
                            placeholder="Mail de l'Agent">
                    </div>
                    <div class="mb-3">
                        <label for="refAgent" class="form-label">Référence</label>
                        <input type="text" size="5" maxlength="5" name="refAgent" class="form-control" id="refAgent"
                            placeholder="Ref agent">
                    </div>
                    {:IF {var:service_parent}=1}
                    <div class="mb-3">
                        <label for="idservice" class="form-label">Service</label>
                        <select name="select_service" id="idservice" class="form-select">
                            <option value="NULL">Séléctionnez un service</option>
                            {LOOP:SQL("SELECT * FROM service WHERE idService={var:user.service} OR parent_service={var:user.service}")}
                            <option value="{#idService#}">{#libService#}</option>
                            {/LOOP}
                        </select>
                    </div>
                    {:/IF}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="addAgent" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="seeTicket" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Voir le ticket #<span data-ticket="idTicket"></span></h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span>Date</span>
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
            </div>
            <div class="modal-footer">
                <button name="updateTicket" class="btn btn-primary">Mettre à jour</button>
                <button name="validTicket" class="btn btn-success">Valider</button>
                <button class="btn btn-danger" name="rejectTicket">Refuser</button>
            </div>
        </div>
    </div>
</div>
<script>
   function addParam(param){
    let container=document.getElementById("LicenceParams");
    param.forEach(p=>{
        let line=document.createElement("div");
        line.setAttribute("name","licence_param");
        let label=document.createElement("label");
        label.classList.add("form-label");
        label.innerText=p.name;
        let input=document.createElement("input");
        input.classList.add("form-control");
        input.setAttribute("licenceparam_name",p.name);
        input.type=p.type;
        line.appendChild(label);
        line.append(input);
        container.appendChild(line);
    })
   }
     document.querySelector("#TypeCible").onchange = function () {
        let sel = document.querySelector("#idCible");
        sel.innerHTML = "";
        let actionLicence = document.querySelector('#actionLicence');
        getLicenceActionByType(this.value, (data) => {
            actionLicence.innerHTML = "";
            data.forEach(act => {
                let opt = document.createElement("option");
                opt.value = act.name;
                opt.innerText = act.name;
                actionLicence.appendChild(opt);
                actionLicence.onchange=function(){
                    if(act.param!=undefined){
                        addParam(act.param);
                    }
                }
            })
        });
        switch (this.value) {
            case "ticket":{
                getTicket(1,{var:user.service},(data)=>{
                     data.forEach(ticket => {
                        let opt = document.createElement('option');
                        opt.value = ticket.idTicket;
                        opt.innerText = ticket.RefTicket;
                        sel.appendChild(opt);
                    })
                },(error)=>{
                    console.error(error);
                })
                break;
            }
            case "agent": {
                getAllAgent((data) => {
                    data.forEach(agent => {
                        let opt = document.createElement('option');
                        opt.value = agent.idAgent;
                        opt.innerText = agent.refAgent;
                        sel.appendChild(opt);
                    })
                })
                break;
            }
            case "service": {
                getServices((data) => {
                    data.forEach(service => {
                        let opt = document.createElement('option');
                        opt.value = service.idService;
                        opt.innerText = service.libService;
                        sel.appendChild(opt);
                    })
                }, (error) => {
                    console.error(error);
                })
            }
        }
    }
</script>