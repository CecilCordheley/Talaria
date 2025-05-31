<?php
namespace vendor\easyFrameWork\Core\Master\Controller;

use SQLEntities\AgentEntity;
use SQLEntities\JournalLicenceEntity;
use SQLEntities\LicenceExceptionEntity;
use SQLEntities\ServiceEntity;
use vendor\easyFrameWork\Core\Master\Controller;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
use vendor\easyFrameWork\Core\Master\ResourceManager;
use vendor\easyFrameWork\Core\Master\EasyTemplate;
use vendor\easyFrameWork\Core\Master\SessionManager;
use vendor\easyFrameWork\Core\Main;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use vendor\easyFrameWork\Core\Master\SQLFactory;

class licenceController extends Controller{
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
      //  EasyFrameWork::Debug($this->user);
            $this->setData("user",$u[0]->getArray());
   
        }
    }
    public function handleRequest(){
        $sessionManager=EasyGlobal::createSessionManager();
        $config=parse_ini_file("include/config.ini",true)["localhost"];
        $JsLib=["1"=>["adminBehav.js"],"2"=>["managerBehav.js"],"3"=>["agentBehav.js"]];
        $template = new EasyTemplate($config,new ResourceManager());
        $template->remplaceTemplate("MainContent","licence.tpl");
                 if(isset($_GET["act"])){
                switch($_GET["act"]){
                    case "use":{
                        if($this->user->typeAgent=="1"){
                           date_default_timezone_set("Europe/Paris");
    $licence=new LicenceExceptionEntity;
    $licence->agent=$this->user->idAgent;
    $licence->uuidLicence=uniqid();
    $licence->dateAttribution=date("Y-m-d H:i:s");
    $licence->estActive="0";
    $licence->isAutoAttribution="1";
    if(LicenceExceptionEntity::add(new SQLFactory(),$licence)){
       $id= $licence->idLicence;
       $logLicence=new JournalLicenceEntity;
       $logLicence->licence=$id;
       $logLicence->cible=$_POST["id_cible"];
       $logLicence->type_cible=$_POST["type_cible"];
       $logLicence->dateAction=date("Y-m-d H:i:s");
       $logLicence->commentaire=$_POST["licence_comment"];
       $action=["name"=>$_POST["action_licence"]];
    $newData=json_encode($action, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
       $logLicence->action= str_replace('"',"\\\"",$newData);
       if($r=JournalLicenceEntity::add(new SQLFactory(),$logLicence)){
        if($logLicence->execute(new SQLFactory())){
            Main::redirectWithAlert($template,"Licence Executé","./index.php");
        }
       }else{
        echo $r;
       }
    }else{
       echo "Error";
    }
                        }else{
                            echo "not enable";
                        }
                        break;
                    }
                }
            }
        if(isset($JsLib[$this->user->typeAgent]))
            foreach($JsLib[$this->user->typeAgent] as $lib){
                $this->setData("moduleScript","public/js/$lib");
            }
         $template->getRessourceManager()->addScript("public/js/actions.js");
            $template->getRessourceManager()->addScript("public/js/async.js");
                  $menu=[
                "1"=>[
                    ["label"=>"Acceuil","action"=>"#","href"=>"index.php"],
                    ["label"=>"Nouveau Service","action"=>"#","href"=>"newService"],
                    
                ],
                "2"=>[
                    ["label"=>"Importer des agents","action"=>" ","href"=>"importAgent"],
                    ["label"=>"requalification","action"=>" ","href"=>"requalif.php"]
                ],
                "3"=>[
                    ["label"=>"Voir les agents connectés","action"=>" ","href"=>"#"],
                ],
                "4"=>[
                    ["label"=>"Gestion des utilisateurs","action"=>" ","href"=>"userManager"],
                    ["label"=>"Gestion des services","action"=>" ","href"=>"serviceManager"],
                    ["label"=>"Gestion de l'application","action"=>" ","href"=>"appManager"]
                ]
                ];
            $template->setLoop("menu",$menu[$this->user->typeAgent]);
        $licences=LicenceExceptionEntity::getAll(new SQLFactory());
        //agent for assignation
        if($licences==false){
            $template->cancelLoop("licence");
                }else{
            $arr = is_array($licences) ? $licences : [$licences];
                    //EasyFrameWork::Debug($arr);
                    $i=0;
                    $sqlF=new SQLFactory();
                    $agentArr=array_reduce($arr,function($c,$e) use(&$i,$sqlF){
                        $c[$i]=$e->getArray();
                        $agt=AgentEntity::getAgentTblBy($sqlF,"idAgent",$e->agent);
                        if($agt){
                            $c[$i]["refAgent"]=$agt[0]->refAgent;
                        }
                        $i++;
                        return $c;
                    },[]);
                $template->setLoop("licence",$agentArr);
            }
        $template->setLoop("TypeCible",array_reduce(JournalLicenceEntity::getTypeCible(),function($car,$el){
            $car[]=["name"=>$el];
            return $car;
        },[]));
        $template->setVariables($this->getData());
        // Rendre le template
        $sqlfactory=new SQLFactory();
       
        $template->render([], $sqlfactory->getPdo());
    }
}