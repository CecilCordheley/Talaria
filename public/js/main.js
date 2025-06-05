window.addEventListener("load", function () {
    document.querySelectorAll("[g_area]").forEach(el => {
        el.style["grid-area"] = el.getAttribute("g_area");
    });
});
function getLicenceActionByType(cat,callBack){
    getActionsLicence((data)=>{
        let a=[];
        for(var act in data){
            if(data[act].type==cat){
                a.push(data[act]);
            }
        }
       callBack.call(this,a);
    })
}
function setEditableField(fiedls, editableObj = "agent",createSelectCompoment) {
    if (editableObj == "agent")
        if (currentAgent == null) {
            console.error("No agent selected !");
            return;
        }

    fiedls.forEach((f) => {
        f.addEventListener("click", function () {

            let data = editableObj == "agent" ? f.getAttribute("data-agent") : f.getAttribute("data-service");
            const tag = f.tagName;
            console.log(data);
            if (data == "service") {
                getServices((servData) => {
                    let servArr = servData.reduce((car, serv) => {
                        car.push({ "value": serv.idService, text: serv.libService });
                        return car;
                    }, [])
                    let sel = createSelectCompoment(servArr);
                    sel.classList.add("form-control");
                    sel.addEventListener("blur", function () {
                        const selectedText = this.options[this.selectedIndex].text;
                        let span = document.createElement("span");
                        span.classList.add("editable");
                        span.innerText = selectedText;
                        span.setAttribute("data-service", this.value);
                        span.setAttribute("data-agent", data);
                        this.replaceWith(span);
                    })
                    f.replaceWith(sel);
                })
            } else {
                let input = null;
                if (tag === "SPAN" || tag == "B") {
                    
                    input = document.createElement("input");
                    if (tag == "SPAN") {
                        input.type = "text";
                        input.classList.add("form-control");
                    } else {
                        input.type = "checkbox";
                        input.classList.add("form-check-input");
                    }

                } else {
                    input = document.createElement("textarea");
                    input.classList.add("form-control");
                }
                if (tag == "B")
                    input.checked = this.innerText == "OUI";
                else
                    input.value = this.innerText;

                input.addEventListener("blur", function () {
                    let span = document.createElement(tag);
                    span.classList.add("editable");
                    if (tag == "B")
                        span.innerText = this.value == "on" ? "OUI" : "NON";
                    else
                        span.innerText = this.value;
                    if (editableObj == "agent")
                        span.setAttribute("data-agent", data);
                    else
                        span.setAttribute("data-service", data);
                    this.replaceWith(span);
                })
                f.replaceWith(input);
            }
        })
    })

}
    /**
 * Assigne au champ [data-agent] les données du tableau
 * @param {array} agent 
 */
function seeAgent(agent) {
    currentAgent = agent;
    for (var data in agent) {
        console.dir(agent["service"]);
        let el = document.querySelector(`[data-agent=${data}]`);
        if (el)
            if (data == "service") {
                getServices((servData) => {
                    let servArr = servData.reduce((car, serv) => {
                        car[serv.idService] = serv.libService;
                        return car;
                    }, [])
                    console.log(agent["service"]);
                    el.setAttribute("data-service", agent["service"]);
                    el.innerText = servArr[agent["service"]];
                }, (error) => {
                    console.error(error);
                });
            } else
                el.innerText = agent[data] ?? " - ";
    }
}
async function setServiceSelector(selectCompoment) {
    getServices(function (data) {
        selectCompoment.innerHTML = "";
        data.forEach(service => {
            let option = document.createElement("option");
            option.value = service.idService;
            option.innerText = service.libService;
            selectCompoment.appendChild(option);
        })
    }, function (error) {
        console.error(error);
    })
}
function setUpdateCompoment(compoment) {
    select = compoment.reduce((c, e) => {
        c.push("[data-ticket=" + e + "].editable");
        return c;
    }, []).join(",");
    document.querySelectorAll(select).forEach(span => {
        span.onclick = function () {
            let dataT = this.getAttribute("data-ticket");
            let input = null;

            if (span.tagName.toUpperCase() === "SPAN") {

                
                if (dataT == "libService") {
                    input=document.createElement("select");
                    setServiceSelector(input);
                } else {
                    input = document.createElement("input");
                    input.type = "text";
                }
                input.classList.add("form-control");
                input.value = span.innerText;

                // Remplacer le span par l'input
                span.replaceWith(input);
                input.focus();

                // Lorsqu'on quitte le champ (blur)
                input.onblur = function () {
                    let newSpan = document.createElement("span");
                    newSpan.setAttribute("data-ticket", dataT); // recréer l'attribut
                    if(dataT=="libService")
                        newSpan.innerText=input.innerText;
                    newSpan.innerText = input.value;

                    // Réattacher l'événement pour le nouveau span
                    newSpan.onclick = span.onclick;

                    input.replaceWith(newSpan);
                };
            } else {
                let input = document.createElement("textarea");
                input.classList.add("form-control");
                input.value = span.innerText;

                // Remplacer le span par l'input
                span.replaceWith(input);
                input.focus();
                input.onblur = function () {
                    let newSpan = document.createElement("p");
                    newSpan.setAttribute("data-ticket", dataT); // recréer l'attribut
                    newSpan.innerText = input.value;

                    // Réattacher l'événement pour le nouveau span
                    newSpan.onclick = span.onclick;

                    input.replaceWith(newSpan);
                };
            }

        };
    });
}
