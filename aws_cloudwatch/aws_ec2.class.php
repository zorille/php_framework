<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class aws_ec2<br>
 *
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage Aws
 */
class aws_ec2 extends aws_wsclient {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type aws_ec2.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param aws_datas &$aws_datas Reference sur un objet aws_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return aws_ec2
	 */
	static function &creer_aws_ec2(&$liste_option, &$aws_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new aws_ec2 ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"aws_datas" => $aws_datas 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return aws_ec2
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["aws_datas"] )) {
			return $this->onError ( "il faut un objet de type aws_datas" );
		}
		$this->setObjetAwsDatas ( $liste_class ["aws_datas"] );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		return true;
	}

	/************************* GESTION Aws HOST ****************************/
	
	//-------------------------------------------------------------------------//
	// EC2_DescribeInstances 
	//-------------------------------------------------------------------------//
	function EC2_DescribeInstances() {
		$retour = array ();
		
		$this->setParams ( 'Action', 'DescribeInstances' );
		$this->setParams ( 'Version', "2014-10-01", true );
		$xml_object = $this->execute_requete_aws ();
		
		$this->onDebug ( "HTTP result received from AWS for DescribeInstances = " . var_export ( $xml_object, true ), 1 );
		
		// Initialize counters
		$instance_count = 0;
		$spot_instance_count = 0;
		
		$instance_state_array = array (
				'pending' => 0,
				'running' => 0,
				'shutting-down' => 0,
				'terminated' => 0,
				'stopping' => 0,
				'stopped' => 0 
		);
		
		$instance_type_array = array (
				'c1.medium' => 0,
				'c1.xlarge' => 0,
				'cc1.4xlarge' => 0,
				'cg1.4xlarge' => 0,
				'm1.large' => 0,
				'm1.medium' => 0,
				'm1.small' => 0,
				'm1.xlarge' => 0,
				'm2.2xlarge' => 0,
				'm2.4xlarge' => 0,
				'm2.xlarge' => 0,
				't1.micro' => 0 
		);
		
		$monitoring_status_array = array (
				'enabled' => 0,
				'pending' => 0,
				'disabling' => 0,
				'disabled' => 0 
		);
		
		$instance_state_reason_array = array (
				'Server.SpotInstanceTermination' => 0,
				'Server.InternalError' => 0,
				'Server.InsufficientInstanceCapacity' => 0,
				'Client.InternalError' => 0,
				'Client.InstanceInitiatedShutdown' => 0,
				'Client.UserInitiatedShutdown' => 0,
				'Client.VolumeLimitExceeded' => 0,
				'Client.InvalidSnapshot.NotFound' => 0 
		);
		
		// A successful query to the AWS API will have one or more
		// reservationSet - so iterate through that.
		// Each reservationSet in turn will have zero, one or more
		// instanceSets  and each instanceSet will have zero or more
		// Amazon EC2 instances.
		foreach ( $xml_object->reservationSet as $reservationSet ) {
			if (empty ( $reservationSet )) {
				break;
			}
			foreach ( $reservationSet->item->instancesSet->item as $instance ) {
				
				$instance_count ++;
				
				$spot_instance_indicator = $instance->spotInstanceRequestId;
				if (! empty ( $spot_instance_indicator )) {
					$spot_instance_count ++;
				}
				
				$instance_state = $instance->instanceState->name;
				if (! empty ( $instance_state )) {
					$instance_state_array ["$instance_state"] ++;
				}
				
				$instance_type = $instance->instanceType;
				if (! empty ( $instance_type )) {
					$instance_type_array ["$instance_type"] ++;
				}
				
				$instance_monitoring_status = $instance->monitoring->state;
				if (! empty ( $instance_monitoring_status )) {
					$monitoring_status_array ["$instance_monitoring_status"] ++;
				}
				
				$instance_state_reason = $instance->stateReason->message;
				if (! empty ( $instance_state_reason )) {
					$instance_state_reason_array ["$instance_state_reason"] ++;
				}
			}
		}
		
		// Print all the data to be sent to Zabbix
		$retour [] .= "Instances_Total $instance_count";
		
		$retour [] .= "Spot_Instances_Total $spot_instance_count";
		
		$temp_counter = 0;
		foreach ( $instance_state_array as $instance_state_key => $instance_state_value ) {
			$retour [] .= "Instances_State_${instance_state_key} $instance_state_value";
			$temp_counter += $instance_state_value;
		}
		if ($instance_count != $temp_counter) {
			$retour [] .= "Instances_State_Unknown " . abs ( $instance_count - $temp_counter );
		}
		
		$temp_counter = 0;
		foreach ( $instance_type_array as $instance_type_key => $instance_type_value ) {
			$retour [] .= "Instances_Type_${instance_type_key} $instance_type_value";
			$temp_counter += $instance_type_value;
		}
		if ($instance_count != $temp_counter) {
			$retour [] .= "Instances_Type_Unknown " . abs ( $instance_count - $temp_counter );
		}
		
		$temp_counter = 0;
		foreach ( $monitoring_status_array as $monitoring_status_key => $monitoring_status_value ) {
			$retour [] .= "Instances_Monitoring_${monitoring_status_key} $monitoring_status_value";
		}
		
		if ($instance_count != $temp_counter) {
			$retour [] .= "Instances_Without_Monitoring " . abs ( $instance_count - $temp_counter );
		}
		
		foreach ( $instance_state_reason_array as $instance_state_reason_key => $instance_state_reason_value ) {
			$retour [] .= "Instances_State_Reason_${instance_state_reason_key} $instance_state_reason_value";
		}
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	

	//-------------------------------------------------------------------------//
	// EC2_DescribeSpotPriceHistory
	//-------------------------------------------------------------------------//
	function EC2_DescribeSpotPriceHistory() {
		$retour = array ();
		
		$this->setParams ( 'Action', "DescribeSpotPriceHistory" );
		$this->setParams ( 'Version', "2014-10-01", true );
		$this->setParams ( 'StartTime', gmdate ( 'Y-m-d\TH:i:s\Z', mktime () - 30 ), true );
		$this->setParams ( 'EndTime', gmdate ( 'Y-m-d\TH:i:s\Z', mktime () ), true );
		$xml_object = $this->execute_requete_aws ();
		
		$this->onDebug ( "HTTP result received from EC2 for DescribeSpotPriceHistory = " . var_export ( $xml_object, true ), 1 );
		
		// A successful query to the EC2 API will have one or more
		// spotPriceHistorySet - so iterate through that.
		// Each spotPriceHistorySet in turn will have zero, one or more
		// items and each item will have an instanceType and spotPrice.
		foreach ( $xml_object->spotPriceHistorySet as $spotPriceHistorySet ) {
			if (empty ( $spotPriceHistorySet )) {
				break;
			}
			foreach ( $spotPriceHistorySet->item as $item ) {
				// Print all the data to be sent to Zabbix
				$chars_to_replace = array (
						'/',
						' ' 
				);
				$retour [] .= "SpotPrice_" . "$item->instanceType" . str_replace ( $chars_to_replace, '_', $item->productDescription ) . " " . $item->spotPrice;
			}
		}
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	

	//-------------------------------------------------------------------------//
	// DescribeAccountAttributes
	//-------------------------------------------------------------------------//
	function EC2_DescribeAccountAttributes() {
		$retour = array ();
		
		$this->setParams ( 'Action', "DescribeAccountAttributes" );
		$this->setParams ( 'AttributeName.1', "default-vpc", true );
		$this->setParams ( 'Version', "2014-10-01", true );
		$xml_object = $this->execute_requete_aws ();
		
		$this->onDebug ( "HTTP result received from EC2 for DescribeSpotPriceHistory = " . var_export ( $xml_object, true ), 1 );
		
		// A successful query to the EC2 API will have one or more
		// spotPriceHistorySet - so iterate through that.
		// Each spotPriceHistorySet in turn will have zero, one or more
		// items and each item will have an instanceType and spotPrice.
		foreach ( $xml_object->DescribeAccountAttributes as $DescribeAccountAttributes ) {
			if (empty ( $DescribeAccountAttributes )) {
				break;
			}
			foreach ( $DescribeAccountAttributes->item as $item ) {
				// Print all the data to be sent to Zabbix
				//	$retour [] .= "DescribeAccountAttributes_" . "$item->instanceType" . $item->productDescription . " " . $item->spotPrice;
			}
		}
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	

	//-------------------------------------------------------------------------//
	// EC2_DescribeSpotPriceHistory
	//-------------------------------------------------------------------------//
	function EC2_DescribeVpcs() {
		$retour = array ();
		
		$this->setParams ( 'Action', "DescribeVpcs" );
		$this->setParams ( 'Version', "2014-10-01", true );
		$xml_object = $this->execute_requete_aws ( 'DescribeVpcs' );
		
		$this->onDebug ( "HTTP result received from EC2 for DescribeVpcs = " . var_export ( $xml_object, true ), 1 );
		
		foreach ( $xml_object->vpcSet as $DescribeVpcs ) {
			if (empty ( $DescribeVpcs )) {
				break;
			}
			foreach ( $DescribeVpcs->item as $item ) {
				$retour [] .= "Vpcs_" . $item->vpcId . "_state" . " " . $item->state;
				$retour [] .= "Vpcs_" . $item->vpcId . "_cidrBlock" . " " . $item->cidrBlock;
				$retour [] .= "Vpcs_" . $item->vpcId . "_dhcpOptionsId" . " " . $item->dhcpOptionsId;
			}
		}
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	

	//-------------------------------------------------------------------------//
	// EC2_DescribeAddresses 
	//-------------------------------------------------------------------------//
	function EC2_DescribeAddresses() {
		$retour = array ();
		
		$this->setParams ( 'Action', 'DescribeAddresses' );
		$this->setParams ( 'Version', "2014-10-01", true );
		$xml_object = $this->execute_requete_aws ();
		
		$this->onDebug ( "HTTP result received from EC2 for DescribeAddresses = " . var_export ( $xml_object, true ), 1 );
		
		// Initialize counters
		$ip_address_count = 0;
		$ip_address_used_by_instance_count = 0;
		
		// A successful query to the EC2 API will have one or more
		// addressesSet - so iterate through that.
		// Each addressesSet in turn will have zero, one or more
		// items containing a publicIP.
		foreach ( $xml_object->addressesSet as $addressesSet ) {
			if (empty ( $addressesSet )) {
				break;
			}
			foreach ( $addressesSet->item as $item ) {
				$ip_address_count ++;
				if (! empty ( $item->instanceId )) {
					$ip_address_used_by_instance_count ++;
				}
			}
		}
		
		// Print all the data to be sent to Zabbix
		$retour [] .= "IP_Addresses_Total $ip_address_count";
		$retour [] .= "IP_Addresses_Assigned $ip_address_used_by_instance_count";
		$retour [] .= "IP_Addresses_Unassigned " . ($ip_address_count - $ip_address_used_by_instance_count);
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	

	//-------------------------------------------------------------------------//
	// EC2_DescribeReservedInstances 
	//-------------------------------------------------------------------------//
	function EC2_DescribeReservedInstances() {
		$retour = array ();
		
		$this->setParams ( 'Action', 'DescribeReservedInstances' );
		$this->setParams ( 'Version', "2014-10-01", true );
		$xml_object = $this->execute_requete_aws ();
		
		$this->onDebug ( "HTTP result received from EC2 for DescribeReservedInstances = " . var_export ( $xml_object, true ), 1 );
		
		// Initialize counters
		$total_reserved_instance_count = 0;
		
		$reserved_instance_count_array = array ();
		
		// A successful query to the EC2 API will have one or more
		// reservedInstancesSet - so iterate through that.
		// Each addressesSet in turn will have zero, one or more
		// items containing a publicIP.
		foreach ( $xml_object->reservedInstancesSet as $reservedInstancesSet ) {
			if (empty ( $reservedInstancesSet )) {
				break;
			}
			foreach ( $reservedInstancesSet->item as $item ) {
				$instanceType = strtolower ( $item->instanceType );
				$reserved_instance_count_array ["$instanceType"] ++;
			}
		}
		
		// Print all the data so that it can be sent to Zabbix
		foreach ( $reserved_instance_count_array as $key => $value ) {
			$retour [] .= "Reserved_Instances_Type_${key} $value";
			$total_reserved_instance_count += $value;
		}
		
		$retour [] .= "Reserved_Instances_Total $total_reserved_instance_count";
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	

	//-------------------------------------------------------------------------//
	// EC2_DescribeSnapshots
	//-------------------------------------------------------------------------//
	function EC2_DescribeSnapshots() {
		$retour = array ();
		
		$this->setParams ( 'Action', 'DescribeSnapshots' );
		$this->setParams ( 'Version', "2014-10-01", true );
		$xml_object = $this->execute_requete_aws ();
		
		$this->onDebug ( "HTTP result received from EC2 for DescribeSnapshots = " . var_export ( $xml_object, true ), 1 );
		
		// Initialize counters
		$snapshots_array = array (
				"All" => array (
						"Snapshots_All_Total" => 0,
						"Snapshots_All_size_GB" => 0,
						"Snapshots_All_Status_pending" => 0,
						"Snapshots_All_Status_completed" => 0,
						"Snapshots_All_Status_error" => 0 
				),
				"self" => array (
						"Snapshots_self_Total" => 0,
						"Snapshots_self_size_GB" => 0,
						"Snapshots_self_Status_pending" => 0,
						"Snapshots_self_Status_completed" => 0,
						"Snapshots_self_Status_error" => 0 
				),
				"amazon" => array (
						"Snapshots_amazon_Total" => 0,
						"Snapshots_amazon_size_GB" => 0,
						"Snapshots_amazon_Status_pending" => 0,
						"Snapshots_amazon_Status_completed" => 0,
						"Snapshots_amazon_Status_error" => 0 
				),
				"other" => array (
						"Snapshots_other_Total" => 0,
						"Snapshots_other_size_GB" => 0,
						"Snapshots_other_Status_pending" => 0,
						"Snapshots_other_Status_completed" => 0,
						"Snapshots_other_Status_error" => 0 
				) 
		);
		
		// A successful query to the EC2 API will have one or more
		// snapshotSet - so iterate through that.
		// Each snapshotSet in turn will have zero, one or more
		// snapshotSets  and each snapshotSet will have zero or more
		// Amazon EC2 snapshots.
		foreach ( $xml_object->snapshotSet as $snapshotSet ) {
			if (empty ( $snapshotSet )) {
				break;
			}
			foreach ( $snapshotSet->item as $snapshot ) {
				
				$snapshot_status = $snapshot->status;
				switch ($snapshot->ownerAlias) {
					case "amazon" :
						$owner = $snapshot->ownerAlias;
						break;
					case "self" :
						$owner = $snapshot->ownerAlias;
						break;
					default :
						$owner = "other";
						break;
				}
				
				$snapshots_array ["All"] ["Snapshots_All_Total"] ++;
				$snapshots_array ["$owner"] ["Snapshots_${owner}_Total"] ++;
				$snapshots_array ["All"] ["Snapshots_All_size_GB"] += $snapshot->volumeSize;
				$snapshots_array ["$owner"] ["Snapshots_${owner}_size_GB"] += $snapshot->volumeSize;
				$snapshots_array ["All"] ["Snapshots_All_Status_${snapshot_status}"] ++;
				$snapshots_array ["$owner"] ["Snapshots_${owner}_Status_${snapshot_status}"] ++;
			}
		}
		
		// Print all the data so that it can be sent to Zabbix
		foreach ( $snapshots_array as $owner_name => $owner_data_array ) {
			foreach ( $owner_data_array as $key => $value ) {
				$retour [] .= "${key} $value";
			}
		}
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	

	//-------------------------------------------------------------------------//
	// EC2_DescribeVolumes 
	//-------------------------------------------------------------------------//
	function EC2_DescribeVolumes() {
		$retour = array ();
		
		$this->setParams ( 'Action', 'DescribeVolumes' );
		$this->setParams ( 'Version', "2014-10-01", true );
		$xml_object = $this->execute_requete_aws ();
		
		$this->onDebug ( "HTTP result received from EC2 for DescribeVolumes = " . var_export ( $xml_object, true ), 1 );
		
		// Initialize counters
		$volume_status_array = array (
				"creating" => array (
						"Volumes_Status_creating" => 0,
						"Volumes_Status_creating_Total_Size_GB" => 0 
				),
				"available" => array (
						"Volumes_Status_available" => 0,
						"Volumes_Status_available_Total_Size_GB" => 0 
				),
				"other" => array (
						"Volumes_Status_other" => 0,
						"Volumes_Status_other_Total_Size_GB" => 0 
				) 
		);
		$volume_attachment_status_array = array (
				"attaching" => array (
						"Volumes_attaching" => 0,
						"Volumes_attaching_Total_Size_GB" => 0 
				),
				"attached" => array (
						"Volumes_attached" => 0,
						"Volumes_attached_Total_Size_GB" => 0 
				),
				"detaching" => array (
						"Volumes_detaching" => 0,
						"Volumes_detaching_Total_Size_GB" => 0 
				),
				"detached" => array (
						"Volumes_detached" => 0,
						"Volumes_detached_Total_Size_GB" => 0 
				) 
		);
		
		// A successful query to the EC2 API will have one or more
		// volumeSets - so iterate through that.
		// Each volumeSet in turn will have zero, one or more
		// items containing a volume with volumeId.
		foreach ( $xml_object->volumeSet as $volumeSet ) {
			if (empty ( $volumeSet )) {
				break;
			}
			foreach ( $volumeSet->item as $volumeId ) {
				
				$volume_status = $volumeId->status;
				$volume_size = $volumeId->size;
				switch ($volume_status) {
					case "creating" :
						break;
					case "available" :
						break;
					default :
						$volume_status = "other";
						break;
				}
				
				$volume_status_array ["$volume_status"] ["Volumes_Status_${volume_status}"] ++;
				$volume_status_array ["$volume_status"] ["Volumes_Status_${volume_status}_Total_Size_GB"] += $volume_size;
				foreach ( $volumeId->attachmentSet as $attachmentSet ) {
					if (empty ( $volumeSet )) {
						break;
					}
					
					$attachment_status = $attachmentSet->item->status;
					
					$volume_attachment_status_array ["$attachment_status"] ["Volumes_${attachment_status}"] ++;
					$volume_attachment_status_array ["$attachment_status"] ["total_${attachment_status}_Total_Size_GB"] += $volume_size;
				}
			}
		}
		
		// Print all the data so that it can be sent to Zabbix
		foreach ( $volume_status_array as $volume_status => $volume_status_data_array ) {
			foreach ( $volume_status_data_array as $key => $value ) {
				$retour [] .= "$key $value";
			}
		}
		
		foreach ( $volume_attachment_status_array as $volume_attachment_status => $volume_attachment_status_data_array ) {
			foreach ( $volume_attachment_status_data_array as $key => $value ) {
				$retour [] .= "$key $value";
			}
		}
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	

	//-------------------------------------------------------------------------//
	// EC2_DescribeImages
	//-------------------------------------------------------------------------//
	function EC2_DescribeImages() {
		$retour = array ();
		
		$this->setParams ( 'Action', "DescribeImages" );
		$this->setParams ( 'Version', "2014-10-01", true );
		$this->setParams ( 'Owner', "self", true );
		$xml_object = $this->execute_requete_aws ();
		
		$this->onDebug ( "HTTP result received from EC2 for DescribeImages = " . var_export ( $xml_object, true ), 1 );
		
		// Initialize counters
		$total_image_count = 0;
		$public_image_count = 0;
		
		// A successful query to the EC2 API will have one or more
		// imageSet - so iterate through that.
		foreach ( $xml_object->imagesSet as $imagesSet ) {
			if (empty ( $imagesSet )) {
				break;
			}
			foreach ( $imagesSet->item as $item ) {
				$total_image_count ++;
				if ($item->isPublic == true) {
					$public_image_count ++;
				}
			}
		}
		
		// Print all the data to be sent to Zabbix
		$retour [] .= "Images_Total $total_image_count";
		$retour [] .= "Images_Public $public_image_count";
		$temp_total = $total_image_count - $public_image_count;
		$retour [] .= "Images_Non-Public $temp_total";
		
		return $retour;
	}
	//-------------------------------------------------------------------------//
	/************************* GESTION Aws HOST ****************************/
	public function valide_requete() {
		
		// Since every successful query to the EC2 API will have a
		// "RequestId" returned, check for that first.
		/*if (isset ( $xml_object->ResponseMetadata )) {
		 $requestId = $xml_object->ResponseMetadata->RequestId;
		} else {
		$requestId = $xml_object->requestId;
		}
		if (is_null ( $requestId ) or empty ( $requestId )) {
		return $this->onError ( "Error: EC2 requestId was either null or empty\n", $requestId );
		}*/
	}

	/************************* Accesseurs ***********************/
	
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
