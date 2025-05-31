<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>#</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Service</th>
                <th>Priorité</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            {LOOP:ticketRequalif}
            <tr ticket="{#idTicket#}">
                <td>{#idTicket#}</td>
                <td>{#refAgent#}</td>
                <td>{#dateTicket#}</td>
                <td requalif-data="service" requalif-value="{#service#}">{#libService#}</td>
                <td requalif-data="priority" requalif-value="{#prioriteTicket#}">{#prioriteTicket#}</td>
                <td>{#typeTicket#}</td>
                <td>
                    <a data-bs-toggle="modal" data-bs-target="#seeTicket" name="seeTicket" idTicket="{#idTicket#}"
                        class="btn btn-primary" href="#">voir</a>
                    <a name="requalifTicket" idTicket="{#idTicket#}"
                        class="btn btn-primary" href="#">voir</a>
                </td>
            </tr>
            {/LOOP}
        </table>
    </div>
</div>
<div class="modal fade" id="seeTicket" tabindex="-1" aria-labelledby="seeTicket" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="modal-title fs-5" id="seeTicket">Voir le ticket</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span>Date</span>
                    <span data-ticket="dateTicket"></span>
                </div>
                <div class="mb-3">
                    <span>Objet</span>
                    <span data-ticket="objetTicket"></span>
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
                </div>
                <div class="mb-3">
                    <span>Contenu</span>
                    <p data-ticket="contentTicket"></p>
                </div>
                <div class="mb-3">
                    <h4>Etat du tickets</h4>
                    <ul data-ticket="states">

                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>