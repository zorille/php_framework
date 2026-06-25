<?php
/**
 * @author dvargas
 * @package Lib
 * 
*/
namespace Zorille\framework;
use Exception;

/**
 * class message<br>
 * @codeCoverageIgnore
 * Gere une intercom entre les process
 * @package Lib
 * @subpackage Fork
*/
class mem_message extends abstract_log
{
	/**
	 * var privee
	 * @access private
	 * @var int
	*/
	var $msg_key;
	/**
	 * var privee
	 * @access private
	 * @var int
	*/
	var $msg_id;
	/**
	 * var privee
	 * @access private
	 * @var string
	*/
	var $err;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type mem_message.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param int|string $key Integer de message.
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return mem_message
	 */
	static function &creer_mem_message(options &$liste_option, int|string $key="", bool|string $sort_en_erreur = false, string $entete = __CLASS__): mem_message
	{
		$objet = new mem_message ( $key, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return mem_message
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise($liste_class);
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param int $key Integer de message.
	 * @param Bool $sort_en_erreur Prend les valeurs true/false.
	*/
	public function __construct($key="",$sort_en_erreur=true, $entete = __CLASS__)
	{
		if($key=="")
			$this->setMsgKey(rand(1000000,9999999));
		else
			$this->setMsgKey($key);
		
		//Gestion de abstract_log
		parent::__construct($entete,$sort_en_erreur);
		
		return $this;
	}

	/**
	 * Ouvre la zone de partage.
	 * @throws Exception
	 */
	public function ouvrir($perm=0644): bool|static
	{
		$this->setMsgId( msg_get_queue($this->getMsgKey(),$perm));
		if(!$this->getMsgId())
			return $this->onError("La queue n'a pas ete cree.");
		
		return $this;
	}

	/**
	 * Ferme la zone de partage.
	*/
	public function supprime(): static
	{
		msg_remove_queue($this->getMsgId());
		return $this;
	}

	/**
	 * calcul la taille d'un variable.
	 * 
	 * @param string $donnees Donnees a mettre en memoire
	*/
	static public function calcul_taille(string $donnees): float|int
	{
		return (((strlen(serialize($donnees))+ (4 * PHP_INT_SIZE)) /4 ) * 4 ) + 4;
	}

	/**
	 * Ecrit dans une variable de la zone de partage.
	 *
	 * @param $message
	 * @return bool|mem_message
	 * @throws Exception
	 */
	public function ecrit($message): bool|mem_message|static
	{
		if (!msg_send($this->getMsgId(), 1,$message,true,true,$this->getErr()))
			return $this->onError("Le message n'a pas ete envoye : ".$this->getErr());
		
		return $this;
	}

	/**
	 * Lit une variable dans la zone de partage.
	 *
	 * @param int $taille
	 * @return string Valeur de la variable.
	 * @throws Exception
	 */
	public function lire(int $taille=10000): string
	{
		if (msg_receive($this->getMsgId(), 1,$msgtype,$taille,$local,true, null, $this->getErr())!==true) 
		{
       		return $this->onError("Le message n'a pas ete recu : ".$this->getErr());
		}


		return $local;
	}

	/**
	 * Lit une variable dans la zone de partage.
	 *
	 * @return string Valeur de la variable.
	 */
	public function status(): string
	{
		return msg_stat_queue($this->getMsgId());
	}
	
	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getMsgKey(): int
	{
		return $this->msg_key;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setMsgKey($msg_key): static
	{
		$this->msg_key = $msg_key;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getMsgId(): int
	{
		return $this->msg_id;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setMsgId($msg_id): static
	{
		$this->msg_id = $msg_id;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &getErr(): string
	{
		return $this->err;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setErr($err): static
	{
		$this->err = $err;
		return $this;
	}
	/******************************* ACCESSEURS ********************************/
} //Fin de la class
