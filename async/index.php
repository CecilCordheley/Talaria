<?php
namespace apis;

use Exception;
use SQLEntities\TypeTicket;
require_once ("../vendor/easyFrameWork/Core/Master/EasyFrameWork.php");

use vendor\easyFrameWork\Core\Main;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
use vendor\easyFrameWork\Core\Master\AjaxPHPTranspiler;
require_once "../vendor/easyFrameWork/Core/Master/AjaxPHPTranspiler.php";
use vendor\easyFrameWork\Core\Master\Autoloader;
use vendor\easyFrameWork\Core\Master\SQLFactory;
use SQLEntities\AgentTbl;
use SQLEntities\AgentEntity;
use SQLEntities\TicketEntity;
use SQLEntities\EtatTicketEntity;
use SQLEntities\Service;
use SQLEntities\ServiceEntity;
use SQLEntities\TicketHasEtatEntity;
use SQLEntities\TypeTicketEntity;
require_once "../vendor/easyFrameWork/Core/Master/SQLFactory.php";
require_once "../vendor/easyFrameWork/Core/Master/EasyGlobal.php";
EasyFrameWork::INIT("../vendor/easyFrameWork/Core/config/config.json");

Autoloader::register();
//EasyFrameWork::showClasses();
$cmds=[
    "ticketFnc"=>function(){
$transpiler = new AjaxPHPTranspiler(__DIR__,"TicketFnc",true);
 $transpiler->setAction($_GET["action"]);
 $transpiler->run();
    },
    "LicenceFnc"=>function(){
        $transpiler = new AjaxPHPTranspiler(__DIR__,"licence",true);
        $transpiler->setAction($_GET["action"]);
        $transpiler->run();
    },
    "addLicence"=>function(){
        $transpiler = new AjaxPHPTranspiler(__DIR__,"createLicence");
        $transpiler->run();
    },
    "delService"=>function(){
        header("Content-Type: application/json");
        $idService=$_GET["id"];
        $SQLF=new SQLFactory(null,"../include/config.ini");
        $service=ServiceEntity::getServiceBy($SQLF,"idService",$idService);
        if($service==false){
             echo json_encode(["status" => "error", "message" => "No service existing !"]);
            exit();
        }
        $service->isActif=0;
        if(ServiceEntity::update($SQLF,$service)){
            echo json_encode(["status" => "success", "data" => "Service mis à jour"]);
        }
    },
    "updateService"=>function(){
        $transpiler = new AjaxPHPTranspiler(__DIR__,"updateService");
        $transpiler->run();
    },
    "updateAgent"=>function(){
 $transpiler = new AjaxPHPTranspiler(__DIR__,"updateAgent");
        $transpiler->run();
    },
    "getAgent"=>function(){
        header("Content-Type: application/json");
        $idAgent=$_GET["id"]??false;
        $SQLF=new SQLFactory(null,"../include/config.ini");
        if($idAgent!=false)
        $agent=AgentEntity::getAgentTblBy($SQLF,"idAgent",$idAgent*1);
        else{
            $agent=AgentEntity::getAll($SQLF);
        }
        if($agent==false){
            echo json_encode(["status" => "error", "message" => "No agent existing !"]);
            exit();
        }
        if($idAgent!=false)
        echo json_encode(["status" => "success", "data" => ["agent"=>$agent[0]->getArray()]]);
        else{
            $return=array_reduce($agent,function($c,$e){
                $c[]=$e->getArray();
                return $c;
            },[]);
            echo json_encode(["status" => "success", "data" => $return]);
        }
    },
    "requalifTicket"=>function(){
        $transpiler = new AjaxPHPTranspiler(__DIR__,"requalifTicket");
        $transpiler->run();
    },
    "assignTicket"=>function(){
        header("Content-Type: application/json");
        $idTiket=$_GET["ticket"];
        $idAgent=$_GET["agent"];
        
        $SQLF=new SQLFactory(null,"../include/config.ini");
        $agent=AgentEntity::getAgentTblBy($SQLF,"idAgent",$idAgent*1);
        if($agent==false){
            echo json_encode(["status" => "error", "message" => "No agent existing !"]);

        }
     //   EasyFrameWork::Debug($agent);
        $t=TicketEntity::getTicketTblBy($SQLF,"idTicket",$idTiket);
       // var_dump($idTiket*1,$t);
        if($t==false){
            echo json_encode(["status" => "error", "message" => "No ticket existing !"]);
   
        }
            $t->agentResponsable=$idAgent*1;
            if(TicketEntity::update($SQLF,$t)){
                $t->changeState($SQLF,"3");
                echo json_encode(["status" => "success", "data" => ["agent"=>$agent[0]->getArray()]]);
            }
        
    },
    "changeStateTicket"=>function(){
        $transpiler = new AjaxPHPTranspiler(__DIR__,"setStateTicket");
        $transpiler->run();
    },
    "updateTicket"=>function(){
        $json_data = json_decode(file_get_contents('php://input'), true);
        $transpiler = new AjaxPHPTranspiler(__DIR__,"updateTicket");
        $transpiler->run();
    },
    "addTypeTicket"=>function(){
        $json_data = json_decode(file_get_contents('php://input'), true);
        $SQLF=new SQLFactory(null,"../include/config.ini");
        $typeTicket=new TypeTicketEntity;
        $typeTicket->libTypeTicket=$json_data["lib"];
        $return=[];
        if(TypeTicketEntity::add($SQLF,$typeTicket)){
            $return["status"]="ok";
            $return["data"]=[];
            $return["data"]["idTypeTicket"]=$typeTicket->idTypeTicket;
        }else{
            $return["status"]="fail";
        }
        echo json_encode($return);
    },
    "getService"=>function(){
        header("Content-Type: application/json");
       
        $sqlF=new SQLFactory(null,"../include/config.ini");
        if(isset($_GET["id"])){
            $serv=ServiceEntity::getServiceBy($sqlF,"idService",$_GET["id"]);
            if($serv==false){
                echo json_encode(["status" => "error", "message" => "No service for this id !"]);
            }else{
                $return=$serv->getArray();
                $return["desc_service"]=mb_convert_encoding($serv->desc_service, 'UTF8');
                $agt=$serv->getAgents($sqlF);
                $return["Agent"]=count($agt);
                $ticketTo=$serv->getTickets($sqlF);
                $return["ticketTo"]=$ticketTo!=false?count($ticketTo):0;
                $ticketFrom=$serv->getTicketsFrom($sqlF);
                $return["ticketFrom"]=$ticketFrom!=false?count($ticketFrom):0;
                echo json_encode(["status" => "success", "data" => $return]);
            }
        }else{
        $services=ServiceEntity::getAll($sqlF);
        if($services!=false){
            $a=is_array($services)?$services:[$services];
            $arr=array_reduce($a,function($c,$e){
                $c[]=$e->getArray();
                return $c;
            },[]);
          
            echo json_encode(["status" => "success", "data" => $arr]);
               if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => 1, "message" => "JSON encoding error: " . json_last_error_msg()]);
        return;
    }
        }else{
            echo json_encode(["status" => "error", "message" => "No services existing !"]);
        }
    }
        
    },
    "addService"=>function(){
        $json_data = json_decode(file_get_contents('php://input'), true);
        $serv=new ServiceEntity;
        $SQLF=new SQLFactory(null,"../include/config.ini");
        $serv->libService=html_entity_decode($json_data["lib"]);
        $serv->RefService=$json_data["ref"];
        $serv->desc_service=Main::utf8ize($json_data["desc"]);
        $serv->create_enable=$json_data["create"];
        $serv->update_enable=$json_data["update"];
        $serv->parent_service=$json_data["parent"];
        $serv->archivable="1";
        $serv->isActif="1";
        $return=[];
        if(ServiceEntity::add($SQLF,$serv)){
            $return["status"]="ok";
            $return["data"]=[];
            $return["data"]["id"]=$serv->idService;
        if($json_data["manager"]!="null" && $json_data["manager"]!=""){
            $m=AgentEntity::getAgentTblBy($SQLF,"idAgent",$json_data["manager"]);

            $m[0]->service=$serv->idService;
            if(AgentEntity::update($SQLF,$m[0])){
                $return["data"]["default"]=$m[0]->idAgent;
            }
        }
    }else{
        $return["status"]="fail";
    }
    echo json_encode($return);
    },
    "addAgent"=>function(){
        $json_data = json_decode(file_get_contents('php://input'), true);
        // EasyFrameWork::Debug($json_data);
         $transpiler = new AjaxPHPTranspiler(__DIR__,"addAgent");
         $transpiler->run();
    },
    "connexion"=>function(){
       $json_data = json_decode(file_get_contents('php://input'), true);
       // EasyFrameWork::Debug($json_data);
        $transpiler = new AjaxPHPTranspiler(__DIR__,"connexion");
        $transpiler->run();
    },
    "addTicket"=>function(){
       $json_data = json_decode(file_get_contents('php://input'), true);
       // EasyFrameWork::Debug($json_data);
        $transpiler = new AjaxPHPTranspiler(__DIR__,"addTicket");
        $transpiler->run();
    },
"seeTicket" => function () {
  //  header("Content-Type: application/json");

    if (!isset($_GET["id"])) {
        echo json_encode(["error" => 1, "message" => "Missing ticket ID"]);
        return;
    }

    $sqlF = new SQLFactory(null, "../include/config.ini");
    $t = TicketEntity::getTicketTblBy($sqlF, "idTicket", $_GET["id"]);

    if ($t === false) {
        echo json_encode(["error" => 1, "message" => "No ticket with ID {$_GET["id"]}"]);
        return;
    }

    $return = $t->getArrayWithState($sqlF);
    $return["contentTicket"] = html_entity_decode($return["contentTicket"]);
    $return["dataTicket"]=(array) $return["dataTicket"];
 //   EasyFrameWork::Debug($return);
    $json = json_encode(["status" => "success", "data" => $return]);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => 1, "message" => "JSON encoding error: " . json_last_error_msg()]);
        return;
    }

    echo $json;
}


];
if(isset($_GET['act'])){
    if(!array_key_exists($_GET["act"],$cmds)){
        echo json_encode(["error"=>0,"message"=>"not a valid query"]);
    }
    $cmds[$_GET["act"]]();
}

