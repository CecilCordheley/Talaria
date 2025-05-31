<?php
namespace vendor\easyFrameWork\Core\Master\Controller;

use SQLEntities\AgentEntity;
use vendor\easyFrameWork\Core\Master\Controller;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
use vendor\easyFrameWork\Core\Master\ResourceManager;
use vendor\easyFrameWork\Core\Master\EasyTemplate;
use vendor\easyFrameWork\Core\Master\SessionManager;
use vendor\easyFrameWork\Core\Main;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use SQLEntities\AgentTbl;
use SQLEntities\JournalLicenceEntity;
use SQLEntities\LicenceExceptionEntity;
use SQLEntities\Service;
use SQLEntities\ServiceEntity;
use SQLEntities\TicketEntity;
use vendor\easyFrameWork\Core\Master\SQLFactory;
class indexController extends Controller{
    /**
     * Current User
     * @var AgentEntity
     */
    private AgentEntity $user;
    private array $licences;
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
            if($u[0]->service!=null){
            $this->service=ServiceEntity::getServiceBy(new SQLFactory(),"idService",$u[0]->service);
            }
       // EasyFrameWork::Debug($this->service);
       $userData=$u[0]->getArray();
       if($u[0]->getLicences(new SQLFactory())){
       $this->licences=$u[0]->getLicences(new SQLFactory());
       if($this->licences!=false){
        $arr = is_array($this->licences) ? $this->licences : [$this->licences];
        $userData["licences"]=count($arr);
       }else{
         $userData["licences"]=0;
       }
    }
      // $userData=count())??0;
            $this->setData("user",$userData);
        }
    }
    private function setLicenceManager($template){
        if($this->user->typeAgent!="1"){
             $licences=is_array($this->licences) ? $this->licences : [$this->licences];
            $i=0;
            $template->setLoop("licences",array_reduce($licences,function($c,$e) use(&$i){
                $c[$i]=$e->getArray();
                $c[$i]["estActive"]=$e->estActive?"OUI":"NON";
                $i++;
                return $c;
            },[]));
        }
    }
    private function setServiceTicketFrom($template,$loopName){
        $ticketFrom=$this->service->getTicketsFrom(new SQLFactory());
        if($ticketFrom==false){
            $template->cancelLoop($loopName);
        }
        $arr = is_array($ticketFrom) ? $ticketFrom : [$ticketFrom];
                    $i=0;
                    $sqlF=new SQLFactory();
                $template->setLoop($loopName,array_reduce($arr,function($c,$e)use(&$i,$sqlF){
                    $stat=$e->getStates($sqlF);
                  
                   $lastS=end($stat);
                    $c[$i]=$e->getArrayWithState($sqlF);
                    $service=ServiceEntity::getServiceBy($sqlF,"idService",$e->service);
                    $c[$i]["serviceTicket"]=$service->libService;
                    $i++;
                    return $c;
                },[]));
    }
    private function setUserMangerUI($template){
        $template->setLoop("TypeCible",array_reduce(JournalLicenceEntity::getTypeCible(),function($car,$el){
            $car[]=["name"=>$el];
            return $car;
        },[]));
        $service_parent=$this->service->getChildren(new SQLFactory());
        $this->setData("service_parent",$service_parent!=false?"1":"0");
                //Get user List from service
                $agents=$this->service->getAgents(new SQLFactory(),$service_parent!=false);
                if($agents==false){
                    $template->cancelLoop("userList");
                }else{
                    $arr = is_array($agents) ? $agents : [$agents];
                    //EasyFrameWork::Debug($arr);
                    $sqlF=new SQLFactory();
                    $i=0;
                    $agentArr=array_reduce($arr,function($c,$e) use(&$i,$sqlF){
                        if($e->idAgent!=$this->user->idAgent){
                        $c[$i]=$e->getArray();
                        $c[$i]["libService"]=ServiceEntity::getServiceBy($sqlF,"idService",$e->service)->libService;
                        $i++;
                        }
                        return $c;
                    },[]);
                $template->setLoop("userList",$agentArr);
                $template->setLoop("agentAssign",$agentArr);
                //Get Ticket From Service
                $this->setServiceTicketFrom($template,"ticketFrom");
                $tiketTo=TicketEntity::getTicketTblBy(new SQLFactory(),"service",$this->service->idService);
                if($tiketTo==false){
                    $template->cancelLoop("ticketTo");
                }else{
                $arr = is_array($tiketTo) ? $tiketTo : [$tiketTo];
                    $i=0;
                    $sqlF=new SQLFactory();
                $template->setLoop("ticketTo",array_reduce($arr,function($c,$e)use(&$i,$sqlF){
                   $stat=$e->getStates($sqlF);
                  
                   $lastS=end($stat);
                //   EasyFrameWork::Debug($lastS,false);
                   if($lastS["Etat_Ticket_idEtatTicket"]!=1){
                    $c[$i]=$e->getArrayWithState($sqlF);
                    if($e->agentResponsable!="NULL")
                        $c[$i]["refAgent"]=AgentEntity::getAgentTblBy($sqlF,"idAgent",$e->agentResponsable)[0]->refAgent;
                    $i++;
                   }
                    return $c;
                },[]));
                }
            }
        }

    
    private function setTicketMangerUI($template){
       // EasyFrameWork::Debug($this->user);
        if($this->user->typeAgent*1>1){
            $template->remplaceTemplate("managerTicket","ADMIN/ticketManager.tpl");
            
            $tickets=TicketEntity::getTicketFrom(new SQLFactory,$this->user->service);
            //EasyFrameWork::Debug($tickets);
         if($tickets==false){
            $template->cancelLoop("ticketManager");
         }else{
            $arr=[];
            if(gettype($tickets)=="array"){
                $arr=$tickets;
            }else{
                $arr[]=$tickets;
            }
            $template->setLoop("ticketManager",array_reduce($arr,function($c,$e){
                $c[]=$e->getArrayWithState(new SQLFactory());
                return $c;
            },[]));
         }
            
        }else{
            $this->setData("managerTicket","Vous n'avez pas les droits");
        }
    }
    public function handleRequest(){
        $sessionManager=EasyGlobal::createSessionManager();
        $config=parse_ini_file("include/config.ini",true)["localhost"];
        $template = new EasyTemplate($config,new ResourceManager());
        $template->getRessourceManager()->addScript("public/js/async.js");
        if($this->isConnect){
            $UIs=["1"=>"adminUI.tpl","2"=>"managerUI.tpl","3"=>"agentUI.tpl"];
            $JsLib=["1"=>["adminBehav.js"],"2"=>["managerBehav.js"],"3"=>["agentBehav.js"]];
            $template->remplaceTemplate("MainContent",$UIs[$this->user->typeAgent]);
            $template->getRessourceManager()->addScript("public/js/actions.js");
            $template->getRessourceManager()->addScript("public/js/async.js");
            $template->getRessourceManager()->addScript("public/js/main.js");
            if(isset($JsLib[$this->user->typeAgent]))
            foreach($JsLib[$this->user->typeAgent] as $lib){
                $this->setData("moduleScript","public/js/$lib");
            }
            $menu=[
                "1"=>[
                    ["label"=>"Nouveau Service","action"=>"#","href"=>"newService"],
                    ["label"=>"Gérer les exception",'action'=>'#','href'=>"Licence"]
                ],
                "2"=>[
                    ["label"=>"Importer des agents","action"=>" ","href"=>"importAgent"],
                    ["label"=>"requalification","action"=>" ","href"=>"requalif.php"],
                     ["label"=>"Licences","action"=>"Licence","href"=>"#"]
                ],
                "3"=>[
                    ["label"=>"Voir les agents connectés","action"=>" ","href"=>"#"],
                    ["label"=>"Voir les tickets","action"=>"ServiceTicketMdl","href"=>"#"]
                ],
                "4"=>[
                    ["label"=>"Gestion des utilisateurs","action"=>" ","href"=>"userManager"],
                    ["label"=>"Gestion des services","action"=>" ","href"=>"serviceManager"],
                    ["label"=>"Gestion de l'application","action"=>" ","href"=>"appManager"]
                ]
                ];
            $template->setLoop("menu",$menu[$this->user->typeAgent]);
            if(isset($_GET["act"])){
                switch($_GET["act"]){
                    case "seeAgent":{
                        $template->getRessourceManager()->addDirectJs("window.addEventListener(\"load\", function () {
                        getAgent(".$_GET["id"].", (data) => {
                    seeAgent(data.agent);
                    setEditableField(document.querySelectorAll(\".editable\"));
                    const myModal= new bootstrap.Modal('#seeManager')
                    myModal.show();
                    document.querySelector('#seeManager').addEventListener('hidden.bs.modal', event => {
    window.location.href='index.php';
})
                })
                        })");
                        break;
                    }
                }
            }
           switch($this->user->typeAgent){
            case "2":{
                //Managers
                #get Licence Type
                $this->setUserMangerUI($template);
            #Gestion des Licences
           $this->setLicenceManager($template);
        
        }
                break;
            
            case "3":{
                $tickets=$this->user->getTicket(new SQLFactory());
                if($tickets==false){
                    $template->cancelLoop("userTicket");
                }else{
                    $arr = is_array($tickets) ? $tickets : [$tickets];
                    $sqlF=new SQLFactory();
                    $i=0;
                $template->setLoop("userTicket",array_reduce($arr,function($c,$e)use(&$i,$sqlF){
                $c[$i]=$e->getArrayWithState($sqlF);
                    $c[$i]["serviceTicket"]=ServiceEntity::getServiceBy($sqlF,"idService",$e->service)->libService;
                    $i++;
                    return $c;
                },[]));
            }
            $serviceTicket=$this->service->getTickets(new SQLFactory());
            $this->setServiceTicketFrom($template,"serviceTicketFrom");
            if($serviceTicket==false){
                $template->cancelLoop("serviceTicket");
            }else{
                $arr = is_array($serviceTicket) ? $serviceTicket : [$serviceTicket];
                $sqlF=new SQLFactory();
                $i=0;
            $template->setLoop("serviceTicket",array_reduce($arr,function($c,$e)use(&$i,$sqlF){
                $stat=$e->getStates($sqlF);
                  
                   $lastS=end($stat);
                //   EasyFrameWork::Debug($lastS,false);
                   if($lastS["Etat_Ticket_idEtatTicket"]!=1){
                $c[$i]=$e->getArrayWithState($sqlF);
                $agent=AgentEntity::getAgentTblBy($sqlF,"idAgent",$e->auteur)[0];
                
                $c[$i]["serviceTicket"]=ServiceEntity::getServiceBy($sqlF,"idService",$agent->service)->libService;
                $i++;
                   }
                return $c;
            },[]));
            }
                break;
            }
           }
            $this->setUserMangerUI($template);
            $this->setTicketMangerUI($template);
        }else{
            $template->remplaceTemplate("MainContent","connexion.tpl");
        }

        $template->setVariables($this->getData());
        // Rendre le template
        $sqlfactory=new SQLFactory();
       
        $template->render([], $sqlfactory->getPdo());
    }
}