<?php
 namespace apis\module\asyncModule;

use DateTime;
use Exception;
use SQLEntities\AgentEntity;
use SQLEntities\EtatTicketEntity;
use SQLEntities\JournalLicenceEntity;
use SQLEntities\LicenceExceptionEntity;
use SQLEntities\ServiceEntity;
use SQLEntities\TicketEntity;
use vendor\easyFrameWork\Core\Main;
use vendor\easyFrameWork\Core\Master\Autoloader;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
 use vendor\easyFrameWork\Core\Master\SessionManager;
 use vendor\easyFrameWork\Core\Master\Cryptographer;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use vendor\easyFrameWork\Core\Master\EnvParser;
use vendor\easyFrameWork\Core\Master\SQLFactory;

abstract class MiscFunction{
    public static function getTicketFrom($serv){
        $sqlF=new SQLFactory(null,"../include/config.ini");
        $serv=ServiceEntity::getServiceBy($sqlF,"idService",$serv);
        if($serv==false){
            echo json_encode(["status" => "error", "message" => "No Service found"]);
            return false;
        }
        $tck_arr=array_reduce($serv->getTicketsFrom($sqlF),function($car,$el)use($sqlF){
            $car[]=$el->getArrayWithState($sqlF);
            return $car;
        },[]);
        echo json_encode(["status" => "success", "data" => $tck_arr]); ;
    }
    public static function useLicence($uuid,$cible,$type_cible,$comment,$action,$param){
        $sqlF=new SQLFactory(null,"../include/config.ini");
        date_default_timezone_set("Europe/Paris");
        $licence=LicenceExceptionEntity::getLicenceExceptionBy($sqlF,"uuidLicence",$uuid);
        if($licence==false){
            echo json_encode(["status" => "error", "message" => "No Licence found"]);
            return false;
         }
        $logLicence=new JournalLicenceEntity;
       $logLicence->licence=$licence->idLicence;
       $logLicence->cible=$cible;
       $logLicence->type_cible=$type_cible;
       $logLicence->dateAction=date("Y-m-d H:i:s");
       $logLicence->commentaire=$comment;
       $action=["name"=>$action,"param"=>$param];
    $newData=json_encode($action, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
       $logLicence->action= str_replace('"',"\\\"",$newData);
       if($r=JournalLicenceEntity::add($sqlF,$logLicence)){
        if($logLicence->execute($sqlF))
            return true;
        else{
            echo json_encode(["status" => "error", "message" => "error while exectuting Licence"]);
            return false;
        }
       }else{
        echo json_encode(["status" => "error", "message" => "error while creating LogLicence"]);
        return false;
       }

    }
    /**
     * Met à jour un service
     * @param integer $id
     * @param array $servData
     * @return bool
     */
    public static function updateService($id,$servData){
         $sqlF=new SQLFactory(null,"../include/config.ini");
         $service=ServiceEntity::getServiceBy($sqlF,"idService",$id);
         if($service==false){
            echo json_encode(["status" => "error", "message" => "No agent found"]);
            return false;
         }
         $service->libService=$servData["libService"];
         $service->desc_service=$servData["desc_service"];
         $service->create_enable=$servData["create_enable"];
         $service->update_enable=$servData["update_enable"];
         if(ServiceEntity::update($sqlF,$service)){
            return true;
         }else{
             echo json_encode(["status" => "error", "message" => "Error while updating datas"]);
            return false;
         }
    }
    /**
     * Met à jour l'agent
     * @param integer $id
     * @param array $agentData
     * @return bool
     */
    public static function updateAgent($id,$agentData){
        $sqlF=new SQLFactory(null,"../include/config.ini");
        $agent=AgentEntity::getAgentTblBy($sqlF,"idAgent",$id);
        if($agent==false){
            echo json_encode(["status" => "error", "message" => "No agent found"]);
            return false;
        }
        $agent[0]->NomAgent=$agentData["NOM"];
        $agent[0]->PrenomAgent=$agentData["PRENOM"];
        $agent[0]->mailAgent=$agentData["MAIL"];
        $agent[0]->service=$agentData["SERVICE"];
        $agent[0]->blockAgent=$agentData["BLOCKAGE"];
        if(AgentEntity::update($sqlF,$agent[0])){
            return true;
        }else{
            echo json_encode(["status" => "error", "message" => "Error while updating datas"]);
            return false;
        }
    }
    /**
     * Permet de modifier le service destinataire d'un ticket ainsi que sa priorité
     * @param integer $id
     * @param integer $service
     * @param string $prio
     */
    public static function requalifTicket($id,$service,$prio){
        $sqlF=new SQLFactory(null,"../include/config.ini");
        $ticket=TicketEntity::getTicketTblBy($sqlF,"idTicket",$id);
        $ticket->service=$service;
        $ticket->prioriteTicket=$prio;
        if(TicketEntity::update($sqlF,$ticket)){
            return $ticket->changeState($sqlF,6,"");
        }
    }
    /**
     * Change l'état du ticket
     * @param integer $id
     * @param integer $state
     * @param string $comment
     */
    public static function setStateTicket($id,$state,$comment){
        $sqlF=new SQLFactory(null,"../include/config.ini");
        $ticket=TicketEntity::getTicketTblBy($sqlF,"idTicket",$id);
        return $ticket->changeState($sqlF,$state,$comment);
    }
    /**
     * Met à jour le ticket (or service et priorité)
     * @param integer $id
     * @param string $objet
     * @param string $content
     * @param string $datas
     * @throws \Exception
     * @return bool
     */
    public static function updateTicket($id,$objet,$content,$datas){
        $sqlF=new SQLFactory(null,"../include/config.ini");
        $ticket=TicketEntity::getTicketTblBy($sqlF,"idTicket",$id);
        //check state for update
        $states=$ticket->getStates($sqlF);
        if(end($states)["Etat_Ticket_idEtatTicket"]!=1){
            echo json_encode(["status" => "error", "message" => "update not allowed for this state"]);
            return false;
        }
        $ticket->objetTicket=html_entity_decode($objet);
        $ticket->contentTicket=html_entity_decode($content);
        $decoded = json_decode($datas, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Le JSON passé dans dataTicket est invalide : " . json_last_error_msg());
    }
    $newData=json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    // Étape 2 : Re-encoder proprement en UTF-8
  //  $ticket->dataTicket = str_replace('"',"\\\"",$newData);
        return TicketEntity::update($sqlF,$ticket);
    }
    /**
     * Créer un nouvel agent
     * @param string $nom
     * @param string $prenom
     * @param string $mail
     * @param string $ref
     * @param integer $type
     * @param integer $service
     * @return array|bool
     */
    public static function addAgent($nom,$prenom,$mail,$ref,$type,$service="null"){
        $agent=new AgentEntity;
        $agent->NomAgent=mb_convert_encoding($nom, "UTF-8", mb_detect_encoding($nom, "UTF-8, ISO-8859-1, ISO-8859-15", true));
        $agent->PrenomAgent=mb_convert_encoding($prenom, "UTF-8", mb_detect_encoding($prenom, "UTF-8, ISO-8859-1, ISO-8859-15", true));
        $agent->mailAgent=mb_convert_encoding($mail, "UTF-8", mb_detect_encoding($mail, "UTF-8, ISO-8859-1, ISO-8859-15", true));
        $agent->refAgent=$ref;
        $agent->typeAgent=$type;
        $agent->service=$service=="null"?null:$service;
        $agent->typeAgent=$type;
        $agent->uuidAgent=uniqid();
        $arrive = date("Y-m-d");
        $date = new DateTime($arrive);
        // Ajout de 1 mois
$date->modify('+1 months');

// Récupération de la date modifiée
$valid = $date->format('Y-m-d');
        $agent->validiteMdp=$valid;
        if(AgentEntity::add(new SQLFactory(null,"../include/config.ini"),$agent)){
            return [$agent->idAgent,$agent->uuidAgent];
        }else
            return false;
    }
    /**
     * Créé un nouveau ticket
     * @param string $obj
     * @param integer $serv
     * @param integer $type
     * @param string $content
     * @param string $data
     * @throws \Exception
     * @return bool
     */
    public static function addTicket($obj,$serv,$type,$content,$data){
        $sessionManager=new SessionManager();
        $user=Main::fixObject($sessionManager->get("user",SessionManager::PUBLIC_CONTEXT),"SQLEntities\AgentEntity");
    $t=new TicketEntity;
    $t->contentTicket=$content;
    $t->dateTicket=date("Y-m-d");
    $t->auteur=$user[0]->idAgent;
    $t->service=$serv;
    $t->objetTicket=$obj;
    $t->typeTicket=$type;
    $t->prioriteTicket='normale';
    $decoded = json_decode($data, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Le JSON passé dans dataTicket est invalide : " . json_last_error_msg());
    }
    $newData=json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $t->dataTicket = str_replace('"',"\\\"",$newData);
    return TicketEntity::add(new SQLFactory(null,"../include/config.ini"),$t);
}
public static function createLicence($agent,$auto=false){
    date_default_timezone_set("Europe/Paris");
    $licence=new LicenceExceptionEntity;
    $licence->agent=$agent;
    $licence->uuidLicence=uniqid();
    $licence->dateAttribution=date("Y-m-d H:i:s");
    $licence->estActive="0";
    $licence->isAutoAttribution=$auto?"1":"0";
    if($r=LicenceExceptionEntity::add(new SQLFactory(null,"../include/config.ini"),$licence)!=true)
        return true;
    else
        echo json_encode(["status" => "error", "message" => "Error dans l'éxécution,\"$r\""]);
}
}