<div class="row">
    <div class="col-7">
        <h3>Les licences</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>UUID</th>
                    <th>AGENT</th>
                    <th>DATE</th>
                </tr>
            </thead>
            <tbody>
                {LOOP:licence}
                <tr>
                    <td>{#idLicence#}</td>
                    <td>{#uuidLicence#}</td>
                    <td>
                        <a href="seeAgent_{#agent#}">{#refAgent#}</a>
                    </td>
                    <td>{#dateAttribution#}</td>
                </tr>
                {/LOOP}
            </tbody>
        </table>
        <button data-bs-toggle="modal" data-bs-target="#newLicence" class="btn btn-success">Nouvelle licence</button>
    </div>
    <div class="col-5">
        <h3>Utiliser une licence</h3>
        <form action="useLicence" method="POST">
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
            <div class="mb-3">
                <label for="comment" class="form-label">Commentaire</label>
                <textarea class="form-control" name="licence_comment" id="comment"></textarea>
            </div>
            <button class="btn btn-success">Valider</button>
        </form>

    </div>
</div>
<div class="modal fade" id="newLicence" tabindex="-1" aria-labelledby="newLicence" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="modal-title fs-5" id="newLicence">Nouvelle Licence</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="agentNewLicence" class="form-label">Agent</label>
                    <select class="form-control" name="agent" id="agentNewLicence">
                        <option value="NULL">Choisissez un agent</option>
                        {LOOP:SQL("SELECT * FROM agent_tbl WHERE typeAgent<>3")}
                            {:IF {#idAgent#}!{var:user.idAgent}}
                            <option value="{#idAgent#}">{#NomAgent#} {#PrenomAgent#}</option>
                            {:/IF}
                            {/LOOP}
                    </select>
                </div>
                <div class="mb-3">
                    <label for="NbLicence" class="form-label">Example range</label>
                    <input label-display="displayRange" value="1" type="range" class="form-range" min="1" max="5"
                        step="1" id="NbLicence">
                    <span id="displayRange"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="createLicence" class="btn btn-primary"
                    data-bs-dismiss="modal">Valider</button>
            </div>
        </div>
    </div>
</div>
<script>
    const value = document.querySelector("#displayRange");
    const input = document.querySelector("#NbLicence");
    value.textContent = input.value;
    input.addEventListener("input", (event) => {
        value.textContent = event.target.value;
    });
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
            })
        });
        switch (this.value) {
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