<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Talaria</title>
    <!--CDN IMPORT-->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <!--Internal Libs-->
    <script src="public/js/main.js"></script>
    <script src="public/js/alert.js"></script>
    <link rel="stylesheet" href="public/css/main.css">
    <link rel="stylesheet" href="public/css/alert.css">
  </head>
  <body>

    <div class="container-fluid" id="wrapper">
      <header g_area="header">
        <h2>Talaria {:IF {var:_isConnect}=1}
          Bienvenue<span> {var:user.NomAgent} {var:user.PrenomAgent}</span>
          {:/IF}</h2>
      </header>
        <div class="list-group" g_area="menu">
          {:IF {var:_isConnect}=1}
          {LOOP:Menu}
          <a href="{#href#}" data-bs-toggle="modal" data-bs-target="#{#action#}" class="list-group-item list-group-item-action" aria-current="true">
            {#label#}
            </a>
          {/LOOP}    
          <a href="deconnexion" class="list-group-item list-group-item-action">Déconnexion</a>   
          {:/IF}
        </div>
        <div id="main" g_area="main" {:IF {var:_isConnect}=1}data-service={var:user.service}{:/IF}>
          {var:MainContent}
        </div>
      <footer g_area="footer">
        Pied de page
      </footer>
    </div>
    <script type="module" src="{var:moduleScript}"></script>
    <script>
        let actionList=document.querySelectorAll("[data-action]");
        actionList.forEach(el=>{
            actions[el.getAttribute("data-action")]();
            return false;
        })
    </script>
  </body>
</html>
