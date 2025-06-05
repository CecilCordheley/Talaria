<?php

use SQLEntities\AgentEntity;
use SQLEntities\ServiceEntity;
use SQLEntities\TicketEntity;
use vendor\easyFrameWork\Core\Main;

use vendor\easyFrameWork\Core\Master\Cryptographer;
require_once ("vendor/easyFrameWork/Core/Master/EasyFrameWork.php");
use vendor\easyFrameWork\Core\Master\EasyFrameWork;

use vendor\easyFrameWork\Core\Master\Autoloader;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use vendor\easyFrameWork\Core\Master\SessionManager;
use vendor\easyFrameWork\Core\Master\SQLFactory;

EasyFrameWork::INIT("./vendor/easyFrameWork/Core/config/config.json");
Autoloader::register();
$sessionManager=EasyGlobal::createSessionManager();
$u=Main::fixObject($sessionManager->get("user",SessionManager::PUBLIC_CONTEXT),"SQLEntities\AgentEntity");
 $service=ServiceEntity::getServiceBy(new SQLFactory(),"idService",$u[0]->service);
if(isset($_GET["data"])){
  switch($_GET["data"]){
    case "agent":{
        if($u[0]->typeAgent=="2"){
            echo "UUID#Ref#Nom#Prenom#service#Tickets#validité\n";
            $service_parent=$service->getChildren(new SQLFactory());
            $agents=$service->getAgents(new SQLFactory(),$service_parent!=false);
            if($agents!=false){
              $arr = is_array($agents) ? $agents : [$agents];
              $sqlF=new SQLFactory();
              $return=array_reduce($arr,function($car,$el) use($sqlF){
                $serv=ServiceEntity::getServiceBy($sqlF,"idService",$el->service)->libService;
                $ticket=$el->getTicket($sqlF);
                $nbTicket=0;
                if($ticket!=false){
                  if(is_array($ticket)){
                    $nbTicket=count($ticket);
                  }else{
                    $nbTicket=1;
                  }
                }
                $line=$el->uuidAgent."#".$el->refAgent."#".$el->NomAgent."#".$el->PrenomAgent."#$serv#$nbTicket#".$el->validiteMdp;

                $car.="$line\n";
                return $car;
              },"");
              Main::export($return,"user_".date("Y-m-d"));
            }
        }
        break;
    }
    case "ticketFrom":{
      $service_parent=$service->getChildren(new SQLFactory());
       $ticketFrom=$service->getTicketsFrom(new SQLFactory());
       if($ticketFrom!=false){
         $arr = is_array($ticketFrom) ? $ticketFrom : [$ticketFrom];
         $sqlF=new SQLFactory();
         echo $service->libService."\n";
         echo "RefTicket#DateTicket#Objet#priorite#Data#Auteur#destinataire#dernier etat\n";
         $i=0;
              $return=array_reduce($arr,function($car,$el) use($sqlF,&$i){
                $stat=$el->getStates($sqlF);
                  $auteur=AgentEntity::getAgentTblBy($sqlF,"idAgent",$el->auteur);
                  $serv=ServiceEntity::getServiceBy($sqlF,"idService",$el->service);
                  $service=$serv!=false?$serv->libService:"-";
                 $refAgent=$auteur!=false?$auteur[0]->refAgent:"-";
                   $lastS=end($stat);
                    $ticket=$el->getArray();
                    unset($ticket["idTicket"]);
                    unset($ticket["contentTicket"]);
                    unset($ticket["service"]);
                    unset($ticket["auteur"]);
                    unset($ticket["agentResponsable"]);
                    unset($ticket["typeTicket"]);
                    $car[$i]=$ticket;
                    $car[$i]["refAgent"]=$refAgent;
                    $car[$i]["service"]=$service;
                     $car[$i]["lastState"]=$lastS["libEtat"]."#".$lastS["dateEtat"]."#".$lastS["commentEtat"];
                    $i++;
                    return $car;
              },[]);
             // EasyFrameWork::Debug($return);
              Main::exportCsv($return,"ticketFrom_".date("Y-m-d"),"#");
       }
      break;
    }
  }
}else{
  var_dump($_GET);
}