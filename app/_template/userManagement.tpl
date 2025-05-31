<h2>Gestion des Utilisateurs</h2>
<div class="row">
    <div class="col-7">
        <table class="table">
            <tr>
                <th>#</th>
                <th>UUID</th>
                <th>ref</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Service</th>
            </tr>
        </table>
    </div>
    <div class="col-5">
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
            <div class="mb-3">
                <label for="typeAgent">Service : </label>
                <select class="form-control" name="typeAgent" id="typeAgent">
                    {LOOP:SQL("SELECT * FROM service")}
                    <option value="{#idService#}">{#libService#}</option>
                    {/LOOP}
                </select>
            </div>
       </form>
    </div>
</div>