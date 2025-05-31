<?php
namespace vendor\easyFrameWork\Core\Master\Controller;

use SQLEntities\AgentEntity;
use SQLEntities\ServiceEntity;
use SQLEntities\TypeTicket;
use vendor\easyFrameWork\Core\Master\Controller;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
use vendor\easyFrameWork\Core\Master\ResourceManager;
use vendor\easyFrameWork\Core\Master\EasyTemplate;
use vendor\easyFrameWork\Core\Master\SessionManager;
use vendor\easyFrameWork\Core\Main;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use vendor\easyFrameWork\Core\Master\SQLFactory;


class requalifController extends Controller{
     /**
     * Current User
     * @var AgentEntity
     */
    private AgentEntity $user;
    private ServiceEntity $service;
    private bool $isConnect;
    public function __construct(){
        parent::__construct();
        $sessionManager=EasyGlobal::createSessionManager();
        //EasyFrameWork::Debug($_SESSION);
       // EasyFrameWork::Debug(AgentTbl::getAll(new SQLFactory()));
        $this->isConnect=(($sessionManager->get("user",SessionManager::PUBLIC_CONTEXT))!=null)?1:0;
            $this->setData("_isConnect",strval($this->isConnect));
        if($this->isConnect=="1"){
            $u=Main::fixObject($sessionManager->get("user",SessionManager::PUBLIC_CONTEXT),"SQLEntities\AgentEntity");
            $this->user=$u[0];
            if($u[0]->service!=null)
            $this->service=ServiceEntity::getServiceBy(new SQLFactory(),"idService",$u[0]->service);
       // EasyFrameWork::Debug($this->service);
            $this->setData("user",$u[0]->getArray());
        }
    }
    public function handleRequest(){
        $sessionManager=EasyGlobal::createSessionManager();
        $config=parse_ini_file("include/config.ini",true)["localhost"];
        $template = new EasyTemplate($config,new ResourceManager());
        $JsLib=["1"=>["adminBehav.js"],"2"=>["managerBehav.js"]];
            $template->getRessourceManager()->addScript("public/js/actions.js");
            $template->getRessourceManager()->addScript("public/js/async.js");
            if(isset($JsLib[$this->user->typeAgent]))
            foreach($JsLib[$this->user->typeAgent] as $lib){
                $this->setData("moduleScript","public/js/$lib");
            }
 $menu=[
                "1"=>[
                    ["label"=>"Accueil","action"=>"#","href"=>"index.php"],
                    ["label"=>"Nouveau Service","action"=>"#","href"=>"newService"]
                ],
                "2"=>[
                     ["label"=>"Accueil","action"=>"#","href"=>"index.php"],
                    ["label"=>"Importer des agents","action"=>" ","href"=>"importAgent"],
                    ["label"=>"requalification","action"=>" ","href"=>"requalif.php"]
                ],
                "4"=>[
                    ["label"=>"Gestion des utilisateurs","action"=>" ","href"=>"userManager"],
                    ["label"=>"Gestion des services","action"=>" ","href"=>"serviceManager"],
                    ["label"=>"Gestion de l'application","action"=>" ","href"=>"appManager"]
                ]
                ];
            $template->setLoop("menu",$menu[$this->user->typeAgent]);
            if($this->user->typeAgent==3){
                echo "this section is not allowed";
                exit;
            }
            $template->remplaceTemplate("MainContent","requalif.tpl");
            $serv=ServiceEntity::getServiceBy(new SQLFactory(),"idService",$this->user->service);
                if($serv==false){
                    $template->cancelLoop("ticketRequalif");
                }else{
                  $ticketFrom=$serv->getTicketsFrom(new SQLFactory());
                  if(count($ticketFrom)==0){
                    $template->cancelLoop("ticketRequalif");
                }else{
                    $arr = is_array($ticketFrom) ? $ticketFrom : [$ticketFrom];
                    $i=0;
                    $sqlF=new SQLFactory();
                $template->setLoop("ticketRequalif",array_reduce($arr,function($c,$e)use(&$i,$sqlF){
                     $stat=$e->getStates($sqlF);
                  
                   $lastS=end($stat);
                //   EasyFrameWork::Debug($lastS,false);
                   if($lastS["Etat_Ticket_idEtatTicket"]==1){
                    $c[$i]=$e->getArrayWithState($sqlF);
                    $c[$i]["refAgent"]=AgentEntity::getAgentTblBy($sqlF,"idAgent",$e->auteur)[0]->refAgent;
                    $c[$i]["typeTicket"]=TypeTicket::getTypeTicketBy($sqlF,"idTypeTicket",$e->typeTicket)->libTypeTicket;
                    $i++;
                   }
                    return $c;
                },[]));
                }
            }
        $template->setVariables($this->getData());
        // Rendre le template
        $sqlfactory=new SQLFactory();
       
        $template->render([], $sqlfactory->getPdo());
    }
}