<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class vmwareVim25ManagedEntity<br>
 * @package Lib
 * @subpackage VMWare
 */
class vmwareVim25ManagedEntity extends vmwareVim25Commun {
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareClusterComputeResource
	 */
	private $objetVmwareClusterComputeResource = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareComputeResource
	 */
	private $objetVmwareComputeResource = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareDatacenter
	 */
	private $objetVmwareDatacenter = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareDatastore
	 */
	private $objetVmwareDatastore = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareFolder
	 */
	private $objetVmwareFolder = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareHostSystem
	 */
	private $objetVmwareHostSystem = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareNetwork
	 */
	private $objetVmwareNetwork = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareHostSystem
	 */
	private $objetVmwareResourcePool = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareVirtualMachine
	 */
	private $objetVmwareVirtualMachine = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareVim25ManagedEntity.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $vmware_webservice Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareVim25ManagedEntity
	 */
	static function &creer_vmwareVim25ManagedEntity(&$liste_option, &$vmware_webservice, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new vmwareVim25ManagedEntity ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareWsclient" => $vmware_webservice 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareVim25ManagedEntity
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere le champ obj d'une reponse SOAP sur un vcenter
	 * @param stdClass $resultat_recherche_soap Resultat d'une requete SOAP vcenter
	 * @return array
	 */
	public function renvoi_obj($resultat_recherche_soap) {
		$liste_finale = $this->getObjectVmwareWsclient ()
			->convertit_donnees ( $resultat_recherche_soap, "xml" );
		$liste_obj = $liste_finale->renvoi_donnee ( "obj" );
		if (! isset ( $liste_obj [0] )) {
			$liste_obj = array (
					$liste_obj 
			);
		}
		
		$this->onDebug ( $liste_obj, 2 );
		return $liste_obj;
	}

	/**
	 * 
	 * @param soapvar|string $TraversalSpec
	 * @return ArrayObject
	 */
	public function &creer_Folder_spec() {
		$array = new ArrayObject ( array (
				'name' => 'FolderTraversalSpec',
				'type' => 'Folder',
				'path' => 'childEntity',
				'skip' => false 
		) );
		
		return $array;
	}

	/**
	 *
	 * @param soapvar|string $TraversalSpec
	 * @return ArrayObject
	 */
	public function &creer_Datacenter_spec($path = 'hostFolder', $skip = false) {
		$array = new ArrayObject ( array (
				'name' => 'DataCenterTraversalSpec',
				'type' => 'Datacenter',
				'path' => $path,
				'skip' => $skip 
		) );
		
		return $array;
	}

	/************************* Methodes Cluster ***********************/
	/**
	 * Fait un Destroy_task
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Del_Cluster($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		// 		return $this->Get_Cluster_Name ( $name )
		// 		->Destroy_Task ();
		return $this->onError ( "NOT Implemented" );
	}

	/**
	 * Retrouve des donnees sur les Clusters
	 *
	 * @param array $pathSet Liste des parametres recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_Cluster($options = array (
			"maxObjects" => "50"
	)) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des datacenters
		$a = $this->creer_Folder_spec ();
		$a->append ( new soapvar ( array (
				'name' => 'DataCenterTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$b = $this->creer_Datacenter_spec ();
		$b->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$this->getObjectVmwarePropertyCollector ()
			->ObjectSpec ( $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->creer_entete_rootFolder_this (), false, array (
				new soapvar ( $a, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $b, SOAP_ENC_OBJECT, 'TraversalSpec' ) 
		) );
		$resultat_recherche = $this->retrouve_objets ( 'ComputeResource', array (), $options );
		
		return $this->renvoi_obj ( $resultat_recherche );
	}

	/**
	 * Retrouve les donnees d'un ClusterComputeResource/ComputeResource
	 *
	 * @param string $name
	 * @return vmwareClusterComputeResource|false
	 * @throws Exception
	 */
	public function Get_Cluster_Datas($cluster_name, $all = true, $pathSet = array(), $options = "") {
		$this->onDebug ( __METHOD__, 1 );
		
		$Cluster = $this->Get_Cluster_Name ( $cluster_name );
		if ($Cluster instanceof vmwareClusterComputeResource) {
			$Cluster_datas = $Cluster->getClusterComputeResource ();
		} else {
			$Cluster_datas = $Cluster->getComputeResource ();
		}
		$resources_cluster = $this->getObjectVmwarePropertyCollector ()
			->retrouve_propset ( $Cluster_datas, $all, $pathSet, $options );
		
		return $resources_cluster;
	}

	/**
	 * Recherche un datacenter par nom et instancie un objet vmware_Cluster si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareClusterComputeResource|false
	 * @throws Exception
	 */
	public function Get_Cluster_Name($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des nom de datacenters
		$resultat_recherche = $this->Get_Cluster ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"name" 
			) );
			if (isset ( $donnees ["name"] ) && $donnees ["name"] == $name) {
				//On a trouve le Cluster
				if ($obj ["type"] == "ClusterComputeResource") {
					return $this->getObjectVmwareClusterComputeResource ()
						->setClusterComputeResource ( $obj );
				}
				return $this->getObjectVmwareComputeResource ()
					->setComputeResource ( $obj );
			}
		}
		
		return $this->onError ( "Pas de Cluster/Standalone nomme " . $name );
	}

	/**
	 * Fait un CreateCluster
	 * Necessite le rootFolder de reference
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Set_Cluster($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->onError ( "NOT Implemented" );
	}

	/************************* Methodes Cluster ***********************/
	
	/************************* Methodes Datacenter ***********************/
	/**
	 * Fait un Destroy_task
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Del_Datacenter($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->Get_Datacenter_Name ( $name )
			->Destroy_Task ();
	}

	/**
	 * Retrouve des donnees sur les Datacenters
	 *
	 * @param array $pathSet Liste des parametres recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_Datacenter() {
		$this->onDebug ( __METHOD__, 1 );
		
		$resultat_recherche = $this->getObjectVmwarePropertyCollector ()
			->retrouve_propset ( ( array ) $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->getRootFolder (), false, array (
				"childEntity" 
		) );
		$liste_finale = array ();
		foreach ( $resultat_recherche as $obj ) {
			if (isset ( $obj ['ManagedObjectReference'] )) {
				$liste_finale = &$obj ['ManagedObjectReference'];
			}
		}
		
		$this->onDebug ( $liste_finale, 2 );
		return $liste_finale;
	}

	/**
	 * Recherche un datacenter par nom et instancie un objet vmwareDatacenter si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareDatacenter|false
	 * @throws Exception
	 */
	public function Get_Datacenter_Name($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des nom de datacenters
		$resultat_recherche = $this->Get_Datacenter ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"name" 
			) );
			if (isset ( $donnees ["name"] ) && $donnees ["name"] == $name) {
				//On a trouve le datacenter
				return $this->getObjectVmwareDatacenter ()
					->setDatacenter ( $obj );
			}
		}
		
		return $this->onError ( "Pas de Datacenter nomme " . $name );
	}

	/**
	 * Fait un CreateDatacenter
	 * Necessite le rootFolder de reference
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Set_Datacenter($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve le rootFolder (celui qui n'a pas de parent)
		$resultat_recherche = $this->Get_Folder ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"parent" 
			) );
			if (! isset ( $donnees ["parent"] )) {
				//Si il n'y a pas de parent, on est au rootFolder
				return $this->getObjectVmwareFolder ()
					->setMoIDFolder ( $obj )
					->CreateDatacenter ( $name );
			}
		}
		
		return $this->onError ( "Pas de rootFolder pour le creer le Datacenter " . $name );
	}

	/************************* Methodes Datacenter ***********************/
	
	/************************* Methodes Datastore ***********************/
	/**
	 * Fait un Destroy_task
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Del_Datastore($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		// 		return $this->Get_Datastore_Name ( $name )
		// 		->Destroy_Task ();
		return $this->onError ( "NOT Implemented" );
	}

	/**
	 * Retrouve des donnees sur les Datastores
	 *
	 * @param array $pathSet Liste des parametres recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_Datastore($options = array (
			"maxObjects" => "50"
	)) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des datastores
		$a = $this->creer_Folder_spec ();
		$a->append ( new soapvar ( array (
				'name' => 'DataCenterTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$b = $this->creer_Datacenter_spec ( 'datastore' );
		$b->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$this->getObjectVmwarePropertyCollector ()
			->ObjectSpec ( $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->creer_entete_rootFolder_this (), false, array (
				new soapvar ( $a, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $b, SOAP_ENC_OBJECT, 'TraversalSpec' ) 
		) );
		$resultat_recherche = $this->retrouve_objets ( 'Datastore', array (), $options );
		
		return $this->renvoi_obj ( $resultat_recherche );
	}

	/**
	 * Recherche un datacenter par nom et instancie un objet vmwareDatastore si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareDatastore|false
	 * @throws Exception
	 */
	public function Get_Datastore_Name($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des nom de datacenters
		$resultat_recherche = $this->Get_Datastore ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"name" 
			) );
			if (isset ( $donnees ["name"] ) && $donnees ["name"] == $name) {
				//On a trouve le datacenter
				return $this->getObjectVmwareDatastore ()
					->setDatastore ( $obj );
			}
		}
		
		return $this->onError ( "Pas de Datastore nomme " . $name );
	}

	/**
	 * Fait un CreateDatastore
	 * Necessite le rootFolder de reference
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Set_Datastore($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->onError ( "NOT Implemented" );
	}

	/************************* Methodes Datastore ***********************/
	
	/************************* Methodes Folder ***********************/
	/**
	 * Fait un Destroy_task
	 * Necessite le rootFolder de reference
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Del_Folder($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->Get_Folder_Name ( $name )
			->Destroy_Task ();
	}

	/**
	 * Retrouve des donnees sur les Folders
	 *
	 * @param array $pathSet Liste des parametres recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_Folder($options = array (
			"maxObjects" => "50"
	)) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des Folders
		$a = $this->creer_Folder_spec ();
		$a->append ( new soapvar ( array (
				'name' => 'DataCenterTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		$a->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$b = $this->creer_Datacenter_spec ( 'vmFolder' );
		$b->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$this->getObjectVmwarePropertyCollector ()
			->ObjectSpec ( $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->creer_entete_rootFolder_this (), false, array (
				new soapvar ( $a, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $b, SOAP_ENC_OBJECT, 'TraversalSpec' ) 
		) );
		$resultat_recherche = $this->retrouve_objets ( 'Folder', array (), $options );
		
		return $this->renvoi_obj ( $resultat_recherche );
	}

	/**
	 * Recherche un datacenter par nom et instancie un objet vmwareFolder si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareFolder|false
	 * @throws Exception
	 */
	public function Get_Folder_Name($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des nom de datacenters
		$resultat_recherche = $this->Get_Folder ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"name" 
			) );
			if (isset ( $donnees ["name"] ) && $donnees ["name"] == $name) {
				//On a trouve le datacenter
				return $this->getObjectVmwareFolder ()
					->setMoIDFolder ( $obj );
			}
		}
		
		return $this->onError ( "Pas de Folder nomme " . $name );
	}

	/**
	 * Fait un CreateFolder
	 * Necessite le rootFolder de reference
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Set_Folder($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->onError ( "NOT Implemented" );
	}

	/************************* Methodes Folder ***********************/
	
	/************************* Methodes HostSystem ***********************/
	/**
	 * Fait un Destroy_task
	 * Necessite le rootHostSystem de reference
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Del_HostSystem($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->Get_HostSystem_Name ( $name )
			->Destroy_Task ();
	}

	/**
	 * Retrouve des donnees sur les HostSystems
	 *
	 * @param array $pathSet Liste des parametres recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_HostSystem($options = array (
			"maxObjects" => "50"
	)) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des HostSystems
		$a = $this->creer_Folder_spec ();
		$a->append ( new soapvar ( array (
				'name' => 'DataCenterTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		$a->append ( new soapvar ( array (
				'name' => 'hostFolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$b = $this->creer_Datacenter_spec ( 'hostFolder' );
		$b->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$c = new ArrayObject ( array (
				'name' => 'hostFolderTraversalSpec',
				'type' => 'ComputeResource',
				'path' => 'host',
				'skip' => false 
		) );
		
		$this->getObjectVmwarePropertyCollector ()
			->ObjectSpec ( $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->creer_entete_rootFolder_this (), false, array (
				new soapvar ( $a, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $b, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $c, SOAP_ENC_OBJECT, 'TraversalSpec' ) 
		) );
		$resultat_recherche = $this->retrouve_objets ( 'HostSystem', array (), $options );
		
		return $this->renvoi_obj ( $resultat_recherche );
	}

	/**
	 * Recherche un datacenter par nom et instancie un objet vmwareHostSystem si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareHostSystem|false
	 * @throws Exception
	 */
	public function Get_HostSystem_Name($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des nom de datacenters
		$resultat_recherche = $this->Get_HostSystem ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"name" 
			) );
			if (isset ( $donnees ["name"] ) && $donnees ["name"] == $name) {
				//On a trouve le datacenter
				return $this->getObjectVmwareHostSystem ()
					->setHostSystem ( $obj );
			}
		}
		
		return $this->onError ( "Pas de HostSystem nomme " . $name );
	}

	/**
	 * Fait un CreateHost
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Set_HostSystem($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->onError ( "NOT Implemented" );
	}

	/************************* Methodes HostSystem ***********************/
	
	/************************* Methodes Network ***********************/
	/**
	 * Fait un Destroy_Task
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Del_Network($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->Get_Network_Name ( $name )
			->Destroy_Task ();
	}

	/**
	 * Retrouve des pointeurs sur les Networks
	 *
	 * @param array $options Liste des options de recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_Network($options = array (
			"maxObjects" => "50"
	)) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des datacenters
		$a = $this->creer_Folder_spec ();
		$a->append ( new soapvar ( array (
				'name' => 'DataCenterTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$b = $this->creer_Datacenter_spec ( 'networkFolder' );
		$b->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$this->getObjectVmwarePropertyCollector ()
			->ObjectSpec ( $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->creer_entete_rootFolder_this (), false, array (
				new soapvar ( $a, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $b, SOAP_ENC_OBJECT, 'TraversalSpec' ) 
		) );
		$resultat_recherche = $this->retrouve_objets ( 'Network', array (), $options );
		
		return $this->renvoi_obj ( $resultat_recherche );
	}

	/**
	 * Recherche une Network par nom et renvoi une instance d'objet vmwareNetwork si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareNetwork|false
	 * @throws Exception
	 */
	public function Get_Network_Name($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des nom de datacenters
		$resultat_recherche = $this->Get_Network ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"name" 
			) );
			if (isset ( $donnees ["name"] ) && $donnees ["name"] == $name) {
				//On a trouve le network
				return $this->getObjectVmwareNetwork ()
					->setNetwork ( $obj );
			}
		}
		
		return $this->onError ( "Pas de Network nomme " . $name );
	}

	/**
	 * Fait un CreateVM_Task
	 *
	 * @param vmwareNetworkConfigSpec $NetworkConfigSpec
	 * @param vmwareResourcePool $ResourcePool
	 * @param vmwareHostSystem $host
	 * @return array|false
	 * @throws Exception
	 */
	public function Set_Network($NetworkConfigSpec, $ResourcePool, $host) {
		$this->onDebug ( __METHOD__, 1 );
		
		// 		return $this->getObjectVmwareFolder ()
		// 		->CreateVM_Task ( $NetworkConfigSpec, $ResourcePool, $host );
		return $this->onError ( "NOT Implemented" );
	}

	/************************* Methodes Network ***********************/
	
	/************************* Methodes ResourcePool ***********************/
	/**
	 * Fait un Destroy_task
	 * Necessite le rootResourcePool de reference
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Del_ResourcePool($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->Get_ResourcePool_Name ( $name )
			->Destroy_Task ();
	}

	/**
	 * Retrouve des donnees sur les ResourcePools
	 *
	 * @param array $pathSet Liste des parametres recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_ResourcePool($options = array (
			"maxObjects" => "50"
	)) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des ResourcePools
		$a = $this->creer_Folder_spec ();
		$a->append ( new soapvar ( array (
				'name' => 'DataCenterTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		$a->append ( new soapvar ( array (
				'name' => 'hostFolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$b = $this->creer_Datacenter_spec ( 'hostFolder' );
		$b->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$c = new ArrayObject ( array (
				'name' => 'hostFolderTraversalSpec',
				'type' => 'ComputeResource',
				'path' => 'resourcePool',
				'skip' => false,
				new soapvar ( array (
						'name' => 'ResourcePoolTraversalSpec' 
				), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) 
		) );
		
		$d = new ArrayObject ( array (
				'name' => 'ResourcePoolTraversalSpec',
				'type' => 'ResourcePool',
				'path' => 'resourcePool',
				'skip' => false 
		) );
		
		$this->getObjectVmwarePropertyCollector ()
			->ObjectSpec ( $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->creer_entete_rootFolder_this (), false, array (
				new soapvar ( $a, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $b, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $c, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $d, SOAP_ENC_OBJECT, 'TraversalSpec' ) 
		) );
		$resultat_recherche = $this->retrouve_objets ( 'ResourcePool', array (), $options );
		
		return $this->renvoi_obj ( $resultat_recherche );
	}

	/**
	 * Recherche un datacenter par nom et instancie un objet vmwareResourcePool si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareResourcePool|false
	 * @throws Exception
	 */
	public function Get_ResourcePool_Name($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des nom de datacenters
		$resultat_recherche = $this->Get_ResourcePool ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"name" 
			) );
			if (isset ( $donnees ["name"] ) && $donnees ["name"] == $name) {
				//On a trouve le datacenter
				return $this->getObjectVmwareResourcePool ()
					->setResourcePool ( $obj );
			}
		}
		
		return $this->onError ( "Pas de ResourcePool nomme " . $name );
	}

	/**
	 * Valide que la resourcePool $moid_resourcePool porte le nom $nomResource
	 * @param array $moid_resourcePool
	 * @param string $nomResource
	 * @return boolean true si vrai, false sinon
	 */
	public function valide_nom_resourcePool($moid_resourcePool, $nomResource) {
		$donnees_resourcePool = $this->getObjectVmwarePropertyCollector ()
			->retrouve_propset ( $moid_resourcePool, false, array (
				"name" 
		) );
		if (isset ( $donnees_resourcePool ["name"] ) && $donnees_resourcePool ["name"] == $nomResource) {
			//On a trouve le resourcePool
			return true;
		}
		
		return false;
	}

	/**
	 * Recherche un datacenter par nom et instancie un objet vmwareResourcePool si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareResourcePool|false
	 * @throws Exception
	 */
	public function Get_ResourcePool_From_Clusters($cluster_name, $nomResource = "Resources") {
		$this->onDebug ( __METHOD__, 1 );
		
		$resources_cluster = $this->Get_Cluster_Datas ( $cluster_name, false, array (
				"resourcePool" 
		) );
		if (isset ( $resources_cluster ["resourcePool"] )) {
			//Si on cherche le container de resourcePool du cluster (nomme "Resources")
			if ($this->valide_nom_resourcePool ( $resources_cluster ["resourcePool"], $nomResource )) {
				return $this->getObjectVmwareResourcePool ()
					->setResourcePool ( $resources_cluster ["resourcePool"] );
			}
			//Sinon on cherche dans les resourcePools du container "Resources" du Cluster s'il y en a
			$liste_resourcePools = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $resources_cluster ["resourcePool"], false, array (
					"resourcePool" 
			) );
			if (isset ( $liste_resourcePools ["resourcePool"] )) {
				foreach ( $liste_resourcePools ["resourcePool"] as $obj ) {
					if ($this->valide_nom_resourcePool ( $obj, $nomResource )) {
						return $this->getObjectVmwareResourcePool ()
							->setResourcePool ( $obj );
					}
				}
				// @codeCoverageIgnoreStart
			}
		}
		// @codeCoverageIgnoreEnd
		

		return $this->onError ( "Pas de ResourcePool nomme " . $nomResource );
	}

	/**
	 * Fait un 
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Set_ResourcePool($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->onError ( "NOT Implemented" );
	}

	/************************* Methodes ResourcePool ***********************/
	
	/************************* Methodes VirtualMachine ***********************/
	/**
	 * Fait un Destroy_Task
	 *
	 * @param string $name
	 * @return array|false
	 * @throws Exception
	 */
	public function Del_VirtualMachine($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->Get_VirtualMachine_Name ( $name )
			->Destroy_Task ();
	}

	/**
	 * Retrouve des pointeurs sur les VirtualMachines
	 *
	 * @param array $options Liste des options de recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_VirtualMachine($options = array (
			"maxObjects" => "50"
	)) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des VirtualMachines
		$a = $this->creer_Folder_spec ();
		$a->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		$a->append ( new soapvar ( array (
				'name' => 'DataCenterTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$b = $this->creer_Datacenter_spec ( 'vmFolder' );
		$b->append ( new soapvar ( array (
				'name' => 'FolderTraversalSpec' 
		), SOAP_ENC_OBJECT, null, null, 'selectSet', null ) );
		
		$this->getObjectVmwarePropertyCollector ()
			->ObjectSpec ( $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->creer_entete_rootFolder_this (), false, array (
				new soapvar ( $a, SOAP_ENC_OBJECT, 'TraversalSpec' ),
				new soapvar ( $b, SOAP_ENC_OBJECT, 'TraversalSpec' ) 
		) );
		$resultat_recherche = $this->retrouve_objets ( 'VirtualMachine', array (), $options );
		
		return $this->renvoi_obj ( $resultat_recherche );
	}

	/**
	 * Recherche une VirtualMachine par nom et renvoi une instance d'objet vmwareVirtualMachine si le nom est trouve
	 *
	 * @param string $name
	 * @return vmwareVirtualMachine|false
	 * @throws Exception
	 */
	public function Get_VirtualMachine_Name($name) {
		$this->onDebug ( __METHOD__, 1 );
		
		//On retrouve la liste des nom de datacenters
		$resultat_recherche = $this->Get_VirtualMachine ();
		foreach ( $resultat_recherche as $obj ) {
			$donnees = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $obj, false, array (
					"name" 
			) );
			if (isset ( $donnees ["name"] ) && $donnees ["name"] == $name) {
				//On a trouve le datacenter
				return $this->getObjectVmwareVirtualMachine ()
					->setMoIDVirtualMachine ( $obj );
			}
		}
		
		return $this->onError ( "Pas de VirtualMachine nomme " . $name );
	}

	/**
	 * Fait un CreateVM_Task
	 *
	 * @param VirtualMachineConfigSpec $VirtualMachineConfigSpec
	 * @param vmwareResourcePool $ResourcePool
	 * @param vmwareHostSystem $host
	 * @return array (Task)|false
	 * @throws Exception
	 */
	public function Set_VirtualMachine($VirtualMachineConfigSpec, $ResourcePool, $host) {
		$this->onDebug ( __METHOD__, 1 );
		
		return $this->getObjectVmwareFolder ()
			->CreateVM_Task ( $VirtualMachineConfigSpec, $ResourcePool, $host );
	}

	/************************* Methodes VirtualMachine ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return vmwareClusterComputeResource
	 */
	public function &getObjectVmwareClusterComputeResource() {
		if (! $this->objetVmwareClusterComputeResource instanceof vmwareClusterComputeResource) {
			$this->setObjectVmwareClusterComputeResource ( vmwareClusterComputeResource::creer_vmwareClusterComputeResource ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareClusterComputeResource;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareClusterComputeResource(&$objetVmwareClusterComputeResource) {
		$this->objetVmwareClusterComputeResource = $objetVmwareClusterComputeResource;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareComputeResource
	 */
	public function &getObjectVmwareComputeResource() {
		if (! $this->objetVmwareComputeResource instanceof vmwareComputeResource) {
			$this->setObjectVmwareComputeResource ( vmwareComputeResource::creer_vmwareComputeResource ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareComputeResource;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareComputeResource(&$objetVmwareComputeResource) {
		$this->objetVmwareComputeResource = $objetVmwareComputeResource;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareDatacenter
	 */
	public function &getObjectVmwareDatacenter() {
		if (! $this->objetVmwareDatacenter instanceof vmwareDatacenter) {
			$this->setObjectVmwareDatacenter ( vmwareDatacenter::creer_vmwareDatacenter ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareDatacenter;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareDatacenter(&$objetVmwareDatacenter) {
		$this->objetVmwareDatacenter = $objetVmwareDatacenter;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareDatastore
	 */
	public function &getObjectVmwareDatastore() {
		if (! $this->objetVmwareDatastore instanceof vmwareDatastore) {
			$this->setObjectVmwareDatastore ( vmwareDatastore::creer_vmwareDatastore ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareDatastore;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareDatastore(&$objetVmwareDatastore) {
		$this->objetVmwareDatastore = $objetVmwareDatastore;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareFolder
	 */
	public function &getObjectVmwareFolder() {
		if (! $this->objetVmwareFolder instanceof vmwareFolder) {
			$this->setObjectVmwareFolder ( vmwareFolder::creer_vmwareFolder ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareFolder;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareFolder(&$objetVmwareFolder) {
		$this->objetVmwareFolder = $objetVmwareFolder;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareHostSystem
	 */
	public function &getObjectVmwareHostSystem() {
		if (! $this->objetVmwareHostSystem instanceof vmwareHostSystem) {
			$this->setObjectVmwareHostSystem ( vmwareHostSystem::creer_vmwareHostSystem ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareHostSystem;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareHostSystem(&$objetVmwareHostSystem) {
		$this->objetVmwareHostSystem = $objetVmwareHostSystem;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareNetwork
	 */
	public function &getObjectVmwareNetwork() {
		if (! $this->objetVmwareNetwork instanceof vmwareNetwork) {
			$this->setObjectVmwareNetwork ( vmwareNetwork::creer_vmwareNetwork ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareNetwork;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareNetwork(&$objetVmwareNetwork) {
		$this->objetVmwareNetwork = $objetVmwareNetwork;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareResourcePool
	 */
	public function &getObjectVmwareResourcePool() {
		if (! $this->objetVmwareResourcePool instanceof vmwareResourcePool) {
			$this->setObjectVmwareResourcePool ( vmwareResourcePool::creer_vmwareResourcePool ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareResourcePool;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareResourcePool(&$objetVmwareResourcePool) {
		$this->objetVmwareResourcePool = $objetVmwareResourcePool;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareVirtualMachine
	 */
	public function &getObjectVmwareVirtualMachine() {
		if (! $this->objetVmwareVirtualMachine instanceof vmwareVirtualMachine) {
			$this->setObjectVmwareVirtualMachine ( vmwareVirtualMachine::creer_vmwareVirtualMachine ( $this->getListeOptions (), $this->getObjectVmwareWsclient () ) );
		}
		return $this->objetVmwareVirtualMachine;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareVirtualMachine(&$vmwareVirtualMachine) {
		$this->objetVmwareVirtualMachine = $vmwareVirtualMachine;
		return $this;
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

?>
