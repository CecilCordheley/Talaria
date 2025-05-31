<table class="table">
    <tr>
        <th>#</th>
        <th>Objet</th>
        <th>Date</th>
        <th>Priorité</th>
        <th>Statut</th>
    </tr>
    {LOOP:ticketManager}
    <tr>
        <td>{#idTicket#}</td>
        <td>{#objetTicket#}</td>
        <td>{#dateTicket#}</td>
        <td>{#prioriteTicket#}</td>
        <td>{#lastStatut#}</td>
    </tr>
    {/LOOP}
</table>