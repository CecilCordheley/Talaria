function setManageBtn(btn, canHandle = false) {
    btn.onclick = function () {
        document.getElementById("commentFail").style.display = "none";
        let id = this.getAttribute("idTicket");
        seeTicket(id, function (result) {
            currentTicket = result;
            for (var r in result) {
                let compoment = document.querySelector(`[manage-ticket=${r}`);
                if (compoment != undefined) {
                    if (r == "states") {
                        compoment.innerHTML = "";
                        result[r].forEach(state => {
                            let statEl = document.createElement("li");
                            statEl.innerHTML = `<span>${state["dateEtat"]}</span> <span>${state["libEtat"]}</span> <span>${state["commentEtat"] ?? ""}</span>`;
                            compoment.appendChild(statEl);
                        });

                    } else if (r == "dataTicket") {
                        for (var data in result[r]) {
                            let statEl = document.createElement("li");
                            statEl.innerHTML = `<span>${data}</span><span>${result[r][data]}</span>`;
                            compoment.appendChild(statEl);
                        }
                    }
                    else
                        compoment.innerText = result[r];
                }
            }
        });
        if (canHandle == false) {
            document.getElementById("lastStateCompoment").style.display = "none";
        } else {
            compomentLib.lastState.onchange = function () {
                if (this.value == 5) {
                    document.getElementById("commentFail").style.display = "block";
                } else {
                    document.getElementById("commentFail").style.display = "none";
                }
            }
        }
    }
}
function setTicket(state, comment = "") {
    let _state = [];
    _state[4] = "traité";
    _state[5] = "échoué";
    changeStateTicket(currentTicket.idTicket, state, comment, function (data) {
        if (data == "Ok") {
            alert("ticket " + _state[state])
        }
    })
}
var compomentLib = {};
var currentTicket = null;
window.addEventListener("load", function () {

    compomentLib = {
        "seeTicket": this.document.getElementById("seeTicket"),
        "lastState": this.document.getElementById("StateTicket"),
        "seeBtn": this.document.querySelectorAll("[name=seeTicket]"),
        "manageBtn": this.document.querySelectorAll("[name=manageTicket]"),
        "handleTicket": this.document.querySelectorAll("[name=handleTicket]"),
        "validState": this.document.getElementById("validState")
    }
    compomentLib.seeTicket.style.display = "none";
    compomentLib.seeBtn.forEach(el => {

        setSeeButton(el);
    })
    compomentLib.manageBtn.forEach(el => {
        setManageBtn(el)
    });
    compomentLib.handleTicket.forEach(el => {
        setManageBtn(el, true);
    })
    compomentLib.validState.onclick = function () {
        let state = compomentLib.lastState.value;
        let comment = document.getElementById("commentFail").value;
        if (state != "NULL") {
            setTicket(state, comment);
        }
    }
    this.document.querySelector("[name=updateTicket]").onclick = function () {
        updateCurrentTicket();
    }
    this.document.getElementById("addData").onclick = function () {
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
    this.document.getElementById("addDataNewTicket").onclick = function () {
        let dataName = document.getElementById("data-name");
        let dataValue = document.getElementById("data-value");
        if (dataName.value == "" && dataValue.value == "") {
            return;
        }
        let compoment = document.querySelector(`#dataTicket`);
        let statEl = document.createElement("li");
        statEl.innerHTML = `<span>${dataName.value}</span><span>${dataValue.value}</span>`;
        compoment.appendChild(statEl);
        dataName.value = "";
        dataValue.value = "";
    }
    this.document.querySelector("#addTicket").onclick = function () {
        try {
            let obj = document.querySelector("[name=objetNewTicket]").value;
            let serv = document.querySelector("[name=service]").value;
            let content = document.querySelector("[name=contentNewTicket]").value;
            let type = document.querySelector("[name=typeTicket]").value;
            let data = {};
            document.querySelectorAll("#dataTicket li").forEach(element => {
                console.dir(element.childNodes);
                data[element.childNodes[0].innerText] = element.childNodes[1].innerText
            });
            addTicket(obj, serv, type, content, JSON.stringify(data), function (data) {
                if (data == "Ok") {
                    document.querySelector("[name=objetNewTicket]").value = "";
                    document.querySelector("[name=service]").value = "";
                    document.querySelector("[name=contentNewTicket]").value = "";
                    document.querySelector("[name=typeTicket]").value = "";
                    let table = document.querySelector("#userTicketTbl tbody");
                    document.querySelector("#newTicket .result").innerText = "Le ticket d'intervention a bien été créé";
                } else {
                    document.querySelector("#newTicket .result").innerText = "Une erreur s'est produite";
                }

            }, function (message) {
                document.querySelector(".result").innerText = "Une erreur s'est produite : " + message;
            });
        } catch (e) {
            document.querySelector(".result").innerText = "Une erreur s'est produite : " + e;
        }
    }
});
function setSeeButton(btn) {
    btn.onclick = function () {
        document.getElementById("seeTicket").style.display = "block";

        const id = this.getAttribute("idTicket");

        seeTicket(id, function (result) {
            currentTicket = result;

            for (const key in result) {
                const component = document.querySelector(`[data-ticket="${key}"].editable`);

                if (!component) continue;
                if (key === "states" || key === "dataTicket") {
                    renderSection(key, component, result[key]);
                } else {
                    component.innerText = result[key];
                }
            }

            const latestState = currentTicket.states?.[currentTicket.states.length - 1];
            if (latestState?.Etat_Ticket_idEtatTicket === 1) {
                setUpdateCompoment(["objetTicket", "contentTicket"]);
            }
        });
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
    },function(error){
        _alert(error);
    })
}
function renderSection(type, component, data) {
    if (!component || !data) return;

    component.innerHTML = "";

    if (type === "states") {
        data.forEach(state => {
            const li = document.createElement("li");
            li.innerHTML = `
                <span>${state.dateEtat.split(" ")[0]}</span>
                <span>${state.libEtat}</span>
                <span>${state.commentEtat ?? ""}</span>`;
            component.appendChild(li);
        });
    } else if (type === "dataTicket") {
        for (const key in data) {
            const li = document.createElement("li");
            li.innerHTML = `<span>${key}</span><span>${data[key]}</span>`;
            component.appendChild(li);
        }
    } else {
        throw new Error(`Unknown render type: ${type}`);
    }
}

function resetUserNav() {
    let sect = this.document.querySelectorAll("[data-name]");
    let navSect = this.document.querySelectorAll("#userNav [data-target]");
    navSect.forEach(el => {
        el.classList.remove("active");
    })
    sect.forEach(el => {
        el.style.display = "none";
    })
}