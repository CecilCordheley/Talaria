<h2 class="">Importer des agents (CSV) {var:user.service}</h2>
<form id="import-form" enctype="multipart/form-data">
    <input class="form-control" type="file" name="csvFile" accept=".csv" required />
    <button class="btn btn-primary" type="submit">Charger</button>
</form>

<div id="result"></div>
<script>
    var result = document.getElementById("result");
    document.getElementById("import-form").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("import.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const table = document.createElement("table");
                    table.classList.add("table");
                    table.border = 1;

                    const thead = document.createElement("thead");
                    const headRow = document.createElement("tr");
                    Object.keys(data.agents[0]).forEach(k => {
                        const th = document.createElement("th");
                        th.textContent = k;
                        headRow.appendChild(th);
                    });
                    thead.appendChild(headRow);
                    table.appendChild(thead);

                    const tbody = document.createElement("tbody");
                    data.agents.forEach(agent => {
                        const row = document.createElement("tr");
                        Object.values(agent).forEach(val => {
                            const td = document.createElement("td");
                            td.textContent = val;
                            row.appendChild(td);
                        });
                        const chck = document.createElement("td");
                        chck.innerHTML = `<input type='checkbox' class='import-check'>`;
                        row.appendChild(chck);
                        tbody.appendChild(row);
                    });
                    table.appendChild(tbody);

                    result.innerHTML = "<h2>Aperçu des agents :</h2>";
                    result.appendChild(table);
                    const btn = document.createElement("button");
                    btn.textContent = "Importer les agents sélectionnés";
                    btn.className = "btn btn-primary mt-3";
                    btn.onclick = function () {
                        const rows = document.querySelectorAll("tbody tr");
                        let imported = 0;

                        rows.forEach(row => {
                            const checked = row.querySelector(".import-check").checked;
                            if (checked) {
                                let nom = row.cells[0];
                                let prenom = row.cells[1];
                                let mail = row.cells[2];
                                let ref = row.cells[3];
                                let type = "3";
                               let service = {var:user.service};
                                createAgent(nom.innerText, prenom.innerText, ref.innerText, mail.innerText, type, service, function (data) {
                                    if (data)
                                        row.classList.add("table-success");
                                    imported++;
                                })
                            }
                        });

                        alert(`${imported} agent(s) importé(s) avec succès.`);
                    };

                    result.appendChild(btn);
                } else {
                    result.textContent = data.error;
                }
            })
            .catch(err => {
                console.error("Erreur AJAX :", err);
                result.innerHTML = `<div class="alert alert-danger" role="alert">${err}</div>`;
            });
    });
</script>