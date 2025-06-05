<?php
namespace vendor\easyFrameWork\Core\Master\Controller;

use SQLEntities\AgentEntity;
use SQLEntities\ServiceEntity;
use SQLEntities\TicketEntity;
use vendor\easyFrameWork\Core\Master\Controller;
use vendor\easyFrameWork\Core\Master\EasyFrameWork;
use vendor\easyFrameWork\Core\Master\ResourceManager;
use vendor\easyFrameWork\Core\Master\EasyTemplate;
use vendor\easyFrameWork\Core\Master\SessionManager;
use vendor\easyFrameWork\Core\Main;
use vendor\easyFrameWork\Core\Master\EasyGlobal;
use vendor\easyFrameWork\Core\Master\SQLFactory;

class StatController extends Controller{
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
       $this->licences=$u[0]->getLicences(new SQLFactory())??[];
       if($this->licences){
        $arr = is_array($this->licences) ? $this->licences : [$this->licences];
        $userData["licences"]=count($arr);
       }else{
         $userData["licences"]=0;
       }
    }
}
    }
    public function handleRequest(){
        $sessionManager=EasyGlobal::createSessionManager();
        $nb=TicketEntity::getNbTicket(new SQLFactory(),"state");
      //  EasyFrameWork::Debug($nb);
        $config=parse_ini_file("include/config.ini",true)["localhost"];
        $template = new EasyTemplate($config,new ResourceManager());
        $template->addScript("https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js");
        $template->addScript("public/js/chartFnc.js");
        $template->remplaceTemplate("MainContent","stat.tpl");
        $menu=[
                "1"=>[
                    ["label"=>"Accueil","action"=>"#","href"=>"index.php"],
                    ["label"=>"Nouveau Service","action"=>"#","href"=>"newService"],
                    
                    ["label"=>"Gérer les exception",'action'=>'#','href'=>"Licence"]
                ],
                "2"=>[
                    ["label"=>"Importer des agents","action"=>" ","href"=>"importAgent"],
                    ["label"=>"Exportation","action"=>"export","href"=>"#"],
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
        //ICI LE CODE

        $template->setVariables($this->getData());
        // Rendre le template
        $template->render();
    }
}