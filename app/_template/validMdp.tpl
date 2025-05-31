<div class="row">
    <h2>Connexion</h2>
    <div class="col-6">
        <form action="./updatePassword" method="POST">
            <div class="mb-3">
                <label for="mailchecker" class="form-label">Votre mail</label>
                <input type="email" name="mailchecker" class="form-control" id="mailchecker" placeholder="name@example.com">
            </div>
            <div class="mb-3">
                <label for="pwdChecker" class="form-label">Votre mot de passe:</label>
                <input type="password" name="pwdChecker" class="form-control" id="pwdChecker">
            </div>
             <div class="mb-3">
                <label for="passWordChecker" class="form-label">répétez votre mot de passe:</label>
                <input type="password" name="pwdChecker" class="form-control" id="passWordChecker">
            </div>
            <div class="mb-3">
                <button class="btn btn-primary">Modiifer le mot de passe</button>
            </div>
        </form>
    </div>
</div>