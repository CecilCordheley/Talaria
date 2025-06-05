<h2>Statistiques</h2>
<div class="row">
    <div class="col-4">
        <div class="list-group">
            <button data-stat="ByService" type="button" class="list-group-item list-group-item-action">Ticket par
                service</button>
            <button data-stat="ByAgent" type="button" class="list-group-item list-group-item-action">Ticket par
                agent</button>
            <button data-stat="ByState" type="button" class="list-group-item list-group-item-action">Ticket par
                Etat</button>
                 <button data-stat="ByType" type="button" class="list-group-item list-group-item-action">Ticket par
                Etat</button>
                <button data-stat="ByServiceWithStat" type="button" class="list-group-item list-group-item-action">Ticket par
               service avec les état</button>
        </div>
    </div>
    <div class="col-8" id="statContainer">
        Ici les Statistiques
    </div>
</div>
<script>
    let dataStat = document.querySelectorAll("[data-stat]");
    clearBtn = function (lst) {
        lst.forEach(el => {
            el.classList.remove("active");
        })
    }
    dataStat.forEach(btn => {

        btn.onclick = function () {
            clearBtn(dataStat);
            this.classList.add("active");
            let fnc = this.getAttribute("data-stat");
            switch (fnc) {
                case "ByServiceWithStat":{
                    getTicketByServiceWithStat("statContainer")
                    break;
                }
                 case "ByState": {
                    renderTicketChart({
                        url: 'async/StatFnc_getTicketByState',
                        containerId: 'statContainer',
                        title: 'Tickets par Etat'
                    });
                    break;
                }
                 case "ByType": {
                    renderTicketChart({
                        url: 'async/StatFnc_getTicketByType',
                        containerId: 'statContainer',
                        title: 'Tickets par Type'
                    });
                    break;
                }
                case "ByService": {
                    renderTicketChart({
                        url: 'async/StatFnc_getTicketByService',
                        containerId: 'statContainer',
                        title: 'Tickets par service'
                    });
                    break;
                }
                case "ByAgent": {
                    renderTicketChart({
                        url: 'async/StatFnc_getTicketByAgent',
                        containerId: 'statContainer',
                        title: 'Tickets par agent'
                    });
                    break;
                }
                default:{
                    alert("opo");
                    break;
                }
            }
        }
    })
</script>