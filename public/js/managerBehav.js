
import { initComponentLib, resetTab } from './component-lib.js';
import { compomentLib } from './component-lib.js';
var currentTicket = null;
var currentLicence = null;
function init() {
    resetTab();
    setNavButton(compomentLib.nav);
}

function setAssignAgent() {
    let idAgent = this.value;
    if (idAgent != "NULL") {
        assignAgent(idAgent, this.getAttribute("idTicket"), (data) => {
            let span = document.createElement("span");
            span.innerText = data.agent.refAgent;
            this.replaceWith(span);
        }, (error) => {
            console.error(error);
        })
    }
}
function setAddDataBtn() {
    if (currentTicket == null) {
        alert("No ticket selected !");
        return;
    }
    let dataName = document.getElementById("data-name");
    let dataValue = document.getElementById("data-value");
    if (dataName.value == "" && dataValue.value == "") {
        return;
    }
    let compoment = document.querySelector(`[data-ticket=dataTicket]`);
    let statEl = document.createElement("li");
    statEl.innerHTML = `<span>${dataName.value}</span><span>${dataValue.value}</span>`;
    compoment.appendChild(statEl);
    dataName.value = "";
    dataValue.value = "";
}
function setValidTicket() {
    if (currentTicket == null) {
        alert("no ticket selected");
        return;
    }
    let line = document.querySelectorAll("tr[ticket='" + currentTicket.idTicket + "'] td")[3];
    if (currentTicket.states[currentTicket.states.length - 1].Etat_Ticket_idEtatTicket == 1)
        changeStateTicket(currentTicket.idTicket, 2, "", function (data) {
            if (data == "Ok") {
                line.innerText = "Validé";
            }
        })
    else {
        const el = document.getElementById("console");
        if (el) {
            el.style.display = "block";
            el.innerHTML = "Vous ne pouvez plus valider ce ticket";

            setTimeout(() => {
                el.style.display = "none";
            }, 5000)
        }
    }
    return false;
}
function setAddAgentBtn() {
    let nom = document.querySelector("[name=nomAgent]");
    let prenom = document.querySelector("[name=prenomAgent]");
    let mail = document.querySelector("[name=mailAgent]");
    let ref = document.querySelector("[name=refAgent]");
    let type = "3";
    let service = "null";
    let servSlect = document.querySelector("[name=select_service]");
    if (servSlect != undefined)
        service = servSlect.value;
    else
        service = getService();
    console.log(nom.value, prenom.value, ref.value, mail.value);
    createAgent(nom.value, prenom.value, ref.value, mail.value, type, service, function (data) {
        if (data)
            document.querySelector("#frmNewAgent").reset();
    })
}
function requalifTicket(id) {
    //get service
    let s = document.querySelector("[requalif-data=service]")?.getAttribute("requalif-value");
    let p = document.querySelector("[requalif-data=priority]")?.getAttribute("requalif-value");
    if (p != undefined && s != undefined)
        requalif(id, s, p, (data) => {
            alert("Le ticket a été requalifié");
        });
    else
        console.error("can't get service or priority");
}
function useExceptionLicence(uuidLicence) {

}
function setRequalifField() {

    document.querySelectorAll("[requalif-data]").forEach(field => {
        field.onclick = function () {
            const data = this.getAttribute("requalif-data");
            const oldValue = this.getAttribute("requalif-value"); // valeur actuelle
            const originalText = this.textContent.trim(); // texte visible actuel
            const select = document.createElement("select");
            select.classList.add("form-control");

            if (data === "service") {
                getServices((services) => {
                    services.forEach(service => {
                        const option = document.createElement("option");
                        option.value = service.idService;
                        option.textContent = service.libService;
                        if (option.value === oldValue) {
                            option.selected = true; // pré-sélectionner la valeur actuelle
                        }
                        select.appendChild(option);
                    });
                });
            } else {
                let prio = ["basse", "normale", "haute"];
                prio.forEach(p => {
                    const option = document.createElement("option");
                    option.value = p;
                    option.textContent = p;
                    if (option.value === oldValue) {
                        option.selected = true; // pré-sélectionner la valeur actuelle
                    }
                    select.appendChild(option);
                })
            }

            select.onblur = function () {
                const selectedValue = select.value;
                const selectedText = select.options[select.selectedIndex].text;

                if (selectedValue === oldValue) {
                    // Aucune modification — on restaure l'affichage
                    const restored = document.createElement("td");
                    restored.setAttribute("requalif-data", data);
                    restored.setAttribute("requalif-value", oldValue);
                    restored.textContent = originalText;
                    select.replaceWith(restored);
                    setRequalifField();
                    return;
                }

                // Modification valide — mettre à jour
                const newCell = document.createElement("td");
                newCell.setAttribute("requalif-data", data);
                newCell.setAttribute("requalif-value", selectedValue);
                newCell.textContent = selectedText;
                select.replaceWith(newCell);
                setRequalifField(); // Pour réinitialiser les nouveaux champs
            };

            this.replaceWith(select);
        };
    });
}

window.addEventListener("load", function () {
    currentTicket = null;
    currentLicence = null;
    initComponentLib();
    resetTab();
    document.querySelector("#useLicenceFrm").style.display = "none";
    document.querySelector("#useLicence").onclick = function () {
        if (currentLicence == null) {
            _alert("No Licence selected !");
        }
        let params = document.querySelectorAll("[licenceparam_name]");
        console.dir(params);
        let paramAttr = {};
        debugger;
        if (params.length) {
            params.forEach(p => {
                paramAttr[p.getAttribute("licenceparam_name")] = p.value;
            })

        }
        let cible = document.querySelector("#idCible").value;
        let type_cible = document.querySelector("#TypeCible").value;
        let action = document.querySelector("#actionLicence").value;
        let comment = document.querySelector("#comment").value;
        console.log("avant 'useLicence'", paramAttr);
        useLicence(currentLicence, cible, type_cible, action, paramAttr, comment);
    }
    document.querySelectorAll("[name=useLicence]").forEach(btn => {
        btn.onclick = function () {
            currentLicence = this.getAttribute("idLicence");
            alert(currentLicence);
            document.querySelector("#useLicenceFrm").style.display = "block";

        }
    });
    if (compomentLib.console != undefined)
        compomentLib.console.style.display = "none";

    if (compomentLib.assignAgent != undefined)
        compomentLib.assignAgent.onchange = setAssignAgent;

    if (compomentLib.validTicket != undefined)
        compomentLib.validTicket.onclick = setValidTicket;
    compomentLib.updateTicket?.addEventListener("click", function () {
        updateCurrentTicket();
    })
    if (compomentLib.requalif.length)
        compomentLib.requalif.forEach(btn => {
            btn.addEventListener("click", function () {
                let id = this.getAttribute("idTicket");
                requalifTicket(id);
            })
        });
    setRequalifField()
    compomentLib.addData?.addEventListener("click", setAddDataBtn);
    compomentLib.addAgent?.addEventListener("click", setAddAgentBtn)
    compomentLib.seeBtn.forEach(el => {
        el.onclick = function () {
            const id = this.getAttribute("idTicket");

            seeTicket(id, function (result) {
                currentTicket = result;

                for (const key in result) {
                    const compoment = document.querySelector(`[data-ticket="${key}"]`);

                    if (!compoment) continue;
                    if (key === "states" || key === "dataTicket") {
                        renderSection(key, compoment, result[key]);
                    } else {
                        compoment.innerText = result[key];
                    }
                }

                const latestState = currentTicket.states?.[currentTicket.states.length - 1];
                if (latestState?.Etat_Ticket_idEtatTicket === 1) {
                    setUpdateCompoment(["objetTicket", "contentTicket"]);
                }
            });
        }
    })
});
function renderSection(type, compoment, data) {
    if (!compoment || !data) return;

    compoment.innerHTML = "";

    if (type === "states") {
        data.forEach(state => {
            const li = document.createElement("li");
            li.innerHTML = `
                <span>${state.dateEtat.split(" ")[0]}</span>
                <span>${state.libEtat}</span>
                <span>${state.commentEtat ?? ""}</span>`;
            compoment.appendChild(li);
        });
    } else if (type === "dataTicket") {
        for (const key in data) {
            const li = document.createElement("li");
            li.innerHTML = `<span>${key}</span><span>${data[key]}</span>`;
            compoment.appendChild(li);
        }
    } else {
        throw new Error(`Unknown render type: ${type}`);
    }
}
function updateCurrentTicket() {
    if (currentTicket == null) {
        alert("No ticket selected !");
        return;
    }
    let updateFields = ["objetTicket", "contentTicket"];
    updateFields.forEach(el => {
        currentTicket[el] = document.querySelector(`[data-ticket=${el}]`).innerText;
    });
    let data = {};
    document.querySelectorAll("[data-ticket=dataTicket] li").forEach(element => {
        console.dir(element.childNodes);
        data[element.childNodes[0].innerText] = element.childNodes[1].innerText
    });
    currentTicket["dataTicket"] = JSON.stringify(data);
    updateTicket(currentTicket, function (data) {
        console.dir(data);
    })
}
function getService() {
    let mainEl = document.querySelector("[data-service]");
    return mainEl.getAttribute("data-service");
}