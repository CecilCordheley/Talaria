import { initComponentLib, createSelectCompoment, compomentLib } from './component-lib.js';


var currentAgent = null;
var currentService = null;

window.addEventListener("load", function () {

    initComponentLib();
    document.getElementById("createLicence")?.addEventListener("click", function () {
        let agent=document.getElementById("agentNewLicence").value;
        if(agent!="NULL"){
            let max=document.getElementById("NbLicence").value*1;
            let _continu=true;
            for(var i=0;i<max;i++){
                createLicence(agent,false,(data)=>{
                    console.log(data);
                },(error)=>{
                    _alert(error,1);
                    _continu=false;
                });
            }
        }else{
            _alert("Pas d'agent séléctionné");
        }
    });
    compomentLib.seeService.forEach(btn => {
        btn.addEventListener("click", function () {
            getService(this.getAttribute("idService"), (data) => {
                console.dir(data);
                currentService = data;
                let fields = document.querySelectorAll("#seeService [data-service]");
                fields.forEach(f => {
                    let dataName = f.getAttribute("data-service");
                    if (dataName == "update_enable" || dataName == "create_enable") {
                        f.innerText = data[dataName] == "1" ? "OUI" : "NON";
                    } else
                        f.innerText = data[dataName] ?? "-";
                    setEditableField(document.querySelectorAll(".editable"), "service");
                })
            })
        });
    })
    document.querySelectorAll("[name=delService]").forEach(el => {
        el.onclick = function () {
            let id = this.getAttribute("idService");
            _confirm("Voulez-vous supprimer le service ?", () => {
                delService(id, (data) => {
                    alert("Service supprimer");
                    this.parentNode.parentNode.remove();
                })
            }, () => {
                alert("Abandon")
            });
            return false;
        }
    })
    document.getElementById("addTypeTicket")?.addEventListener("click", function () {
        let nom = document.getElementById("LibTypeTicket");
        createTypeTicket(nom.value, function (data) {
            if (data.idTypeTicket != undefined) {
                let table = document.querySelector("#typeTicketTable tbody");
                table.innerHTML += `<td>${data.idTypeTicket}</td><td>${nom.value}</td>`;
                nom.reset();
            }
        })
    })
    document.getElementById("updateService")?.addEventListener("click", function () {
        let fields = document.querySelectorAll("#seeService [data-service]");
        fields.forEach(f => {
            let dataName = f.getAttribute("data-service");
            let dataValue = "";
            if (dataName == "create_enable" || dataName == "update_enable") {
                dataValue = f.innerText == "OUI" ? 1 : 0;
            } else
                dataValue = f.innerText;
            currentService[dataName] = dataValue;
        });
        console.dir(currentService);
        updateService(currentService, (data) => {
            alert("Mise à jour faite !");
        }, (error) => {
            console.error(error);
        })
    });
    document.getElementById("updateAgent")?.addEventListener("click", function () {
        let fields = document.querySelectorAll("[data-agent]");
        fields.forEach(f => {
            let dataName = f.getAttribute("data-agent");
            let dataValue = (dataName != "service") ? f.innerText : f.getAttribute("data-service");
            currentAgent[dataName] = dataValue;
        });
        updateAgent(currentAgent.idAgent, currentAgent, (data) => {
            alert("Ok");
        }, (error) => {
            console.error(error);
        })
    });
    //rendre les champs éditable
    if (compomentLib.seeAgent.length)
        compomentLib.seeAgent.forEach(btn => {
            btn.onclick = function () {
                //get ID
                let id = this.getAttribute("idAgent");
                getAgent(id, (data) => {
                    seeAgent(data.agent);
                    setEditableField(document.querySelectorAll(".editable"));
                })
            }
        })
    /*
    seebtn.forEach(el => {
        el.onclick = function () {
            let id = this.getAttribute("idTicket");
            seeTicket(id, function (result) {
                for (var r in result) {
                    let compoment = document.querySelector(`[data-ticket=${r}`);
                    if (compoment != undefined) {
                        if(r=="states"){
                            result[r].forEach(state=>{
                                let statEl=document.createElement("li");
                                statEl.innerHTML=`<span>${state["dateEtat"]}</span> <span>${state["libEtat"]}</span> <span>${state["commentEtat"]??""}</span>`;
                                compoment.appendChild(statEl);
                            });
                        
                        }else
                            compoment.innerText = result[r];
                    }
                }
            })
        }
    })*/
    document.getElementById("addManager")?.addEventListener("click", function () {
        let nom = document.querySelector("[name=nomAgent]");
        let prenom = document.querySelector("[name=prenomAgent]");
        let mail = document.querySelector("[name=mailAgent]");
        let ref = document.querySelector("[name=refAgent]");
        let type = "2";
        let service = "null";
        console.log(nom.value, prenom.value, ref.value, mail.value);
        createAgent(nom.value, prenom.value, ref.value, mail.value, type, service, function (data) {
            if (data)
                document.querySelector("#frmNewAgent").reset();
        })
    })
    document.getElementById("addService")?.addEventListener("click", function () {
        let nom = document.querySelector("[name=nomService]");
        let ref = document.querySelector("[name=refService]");
        let defaultM = document.querySelector("[name=selectedManager]");
        let desc = document.querySelector("[name=descService");
         let parent = document.querySelector("[name=service_parent");
        let craeteEnable = document.querySelector("[name=createEnable").value == "on" ? "1" : "0";
        let updateEnable = document.querySelector("[name=updateEnable").value == "on" ? "1" : "0";
        createService(nom.value,ref.value, desc.value, defaultM.value,parent.value, craeteEnable, updateEnable, function (data) {
            nom.value = "";
            ref.value="";
            desc.value = "";
            defaultM.value = "NULL";
        });
    });
});
