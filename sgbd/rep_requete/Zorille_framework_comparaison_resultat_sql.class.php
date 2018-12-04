<?php
/**
 * Permet la comparaison de gros tableau de 1 niveau
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class comparaison_resultat_sql
 *
 * @package Lib
 * @subpackage SQL
 */
class comparaison_resultat_sql extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $tableau_ajoute = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $tableau_supprime = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type comparaison_resultat_sql.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return comparaison_resultat_sql
	 */
	static function &creer_comparaison_resultat_sql(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new comparaison_resultat_sql ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return comparaison_resultat_sql
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur        	
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Applique les requetes et renvoi un tableau "de hash" representant la liste des tuples : <br>
	 * $tableau["champ1"]["champ2"].....["champN"]=1
	 *
	 * @param connexion $connexion Connexion ouverte sur une base (objet BD).
	 * @param string $requete Requete a appliquer sur la base.
	 * @return array false de resultat (liste des tuples), FALSE sinon.
	 * @throws Exception
	 */
	public function prepare_donnees($resultat_sql) {
		$this->onDebug ( "Preparation des donnees.", 2 );
		if (! is_array ( $resultat_sql )) {
			return $this->onError ( "La liste de donnees n'est pas un tableau pour la comparaison.", $resultat_sql, 3005 );
		} else {
			$CODE_RETOUR = array();
		}
		// On joue avec les adresses memoires pour creer le tableau de hash
		foreach ( $resultat_sql as $row ) {
			$liste = &$CODE_RETOUR;
			foreach ( $row as $key => $value ) {
				if (! is_int ( $key )) {
					if (is_string ( $value ) && $value == '')
						$value = 'ZVIDE';
					elseif ($value == NULL)
						$value = 'ZNULL';
					elseif (is_numeric ( $value ))
						$value = "ZINT" . $value;
					
					$value = $this->encode_donnee ( $value );
					if (! isset ( $liste [$value] ))
						$liste [$value] = array ();
					$liste = &$liste [$value];
				}
			}
			$liste = 1;
		}
		
		$this->onDebug ( "Resultat du tableau prepare :", 2 );
		$this->onDebug ( $CODE_RETOUR, 2 );
		return $CODE_RETOUR;
	}

	/**
	 * Fonction qui compare 2 tableaux de resultats.<br>
	 * Les tableaux a comparer doivent avoir une "profondeur" egale (meme nombre de champ dans la requete SQL).<br>
	 * Cette fonction modifie le tableau2 (image de la table d'arrivee) en supprimant les tuples egaux,
	 * et, en mettant 1 pour les tuples a supprimer et 0 pour les tuples a ajouter.
	 *
	 * @param array $tableau1 Liste des tuples de la table a synchroniser (table d'origine).
	 * @param array &$tableau2 Pointeur vers la liste des tuples de la table de destination.
	 * @return comparaison_resultat_sql
	 */
	public function compare_tuple($tableau1, &$tableau2) {
		if (is_array ( $tableau1 ) && count ( $tableau1 ) > 0) {
			foreach ( $tableau1 as $key => $value ) {
				if (! isset ( $tableau2 [$key] ))
					$tableau2 [$key] = array ();
				if (is_array ( $value )) {
					$this->compare_tuple ( $value, $tableau2 [$key] );
					if (is_array ( $tableau2 [$key] ) && count ( $tableau2 [$key] ) == 0)
						unset ( $tableau2 [$key] );
				} elseif (! is_array ( $tableau2 [$key] ) && $tableau2 [$key] == 1)
					unset ( $tableau2 [$key] );
				else
					$tableau2 [$key] = 0;
			}
		}
		
		return $this;
	}

	/**
	 * Fonction qui prend le tableau apres comparaison et renvoi
	 * la liste de tuple a supprimer pour le code 1 et la liste des
	 * tuple a ajouter pour le code 0.
	 *
	 * @param array $tableau Tableau renvoye par compare_tuple.
	 * @param int $code Prend les valeurs 0 ou 1.
	 * @return array Liste des tuples a modifier en fonction du code.
	 */
	public function retrouve_donnees_a_modifier($tableau, $code) {
		$CODE_RETOUR = array ();
		$flag = false;
		if (is_array ( $tableau ) && count ( $tableau ) > 0) {
			// permet de connaitre la ligne du tableau resultat
			$i = 1;
			// Pour chaque case du tableau de depart
			foreach ( $tableau as $key => $value ) {
				// On passe au sous case
				$liste = $this->retrouve_donnees_a_modifier ( $value, $code );
				$CODE_RETOUR [0] = $liste [0];
				if ($liste [0]) {
					// si la taille est a 1, on charge la valeur
					if (count ( $liste ) == 1) {
						$CODE_RETOUR [$i] = $key;
						$flag = true;
						$i ++;
					} else {
						for($j = 1; $j < count ( $liste ); $j ++) {
							$CODE_RETOUR [$i] = $key . "'param'" . $liste [$j];
							
							$flag = true;
							$i ++;
						}
					}
				}
			}
		} elseif ($tableau == $code)
			$CODE_RETOUR [0] = true;
		else
			$CODE_RETOUR [0] = false;
		
		if ($flag)
			$CODE_RETOUR [0] = true;
		
		return $CODE_RETOUR;
	}

	/**
	 * Fonction qui recupere la liste des champ du select pour faire le INSERT.
	 *
	 * @param string $requete Requete contenant des champs.
	 * @return array Liste des champs de la requete.
	 */
	public function recuperer_liste_champ($requete) {
		$liste_string = substr ( $requete, 0, stripos ( $requete, "FROM " ) );
		$liste = explode ( ",", trim ( $liste_string ) );
		//if ($liste && count ( $liste ) > 0) {
			// On vire le select et autres distinct ..
			$tempo = explode ( " ", $liste [0] );
			$liste [0] = $tempo [(count ( $tempo ) - 1)];
			foreach ( $liste as $pos => $champ ) {
				if (stripos ( $champ, " AS " ) !== false) {
					$tempo2 = explode ( " ", $champ );
					$liste [$pos] = $tempo2 [3];
				}
			}
			$CODE_RETOUR = $liste;
// 		} else
// 			$CODE_RETOUR = false;
		
		return $CODE_RETOUR;
	}

	/**
	 * Encode une string
	 *
	 * @param string $data        	
	 * @return string
	 */
	public function encode_donnee($data) {
		$RETOUR = str_replace ( "\'", "'", $data );
		$RETOUR = str_replace ( "\\", "%2bs%", $RETOUR );
		$RETOUR = urlencode ( $RETOUR );
		
		return $RETOUR;
	}

	/**
	 * Decode une string
	 *
	 * @param string $data        	
	 * @return string
	 */
	public function decode_donnee($data) {
		$RETOUR = str_replace ( "'", "''", urldecode ( $data ) );
		$RETOUR = str_replace ( "%2bs%", "\\\\", $RETOUR );
		if (strpos ( $RETOUR, "ZINT" ) === 0) {
			$RETOUR = str_replace ( "ZINT", "", $RETOUR );
		}
		
		return $RETOUR;
	}

	/**
	 * Supprime les tuples en trop dans la base de destination.
	 *
	 * @param array $tableau_comparee Tableau renvoye par compare_tuple.
	 * @param string $table Table a modifier dans la base de destination.
	 * @param array $liste_champs Liste des champs de la table de destination.
	 * @return Bool TRUE si OK, FALSE sinon.
	 * @throws Exception
	 */
	public function liste_suppression_donnees($tableau_comparee, $table, $liste_champs) {
		$CODE_RETOUR = array ();
		$liste_a_supprimer = $this->retrouve_donnees_a_modifier ( $tableau_comparee, 1 );
		$nb_ligne = count ( $liste_a_supprimer );
		$nb_en_cours = $nb_ligne - 1;
		$this->onDebug ( "Il y a " . $nb_en_cours . " lignes a supprimer.", 1 );
		$this->onDebug ( "Liste des lignes a supprimer : ", 2 );
		$this->onDebug ( $liste_a_supprimer, 2 );
		if ($nb_ligne > 1) {
			for($i = 1; $i < $nb_ligne; $i ++) {
				$liste_variable = explode ( "'param'", $liste_a_supprimer [$i] );
				if (count ( $liste_variable ) == count ( $liste_champs )) {
					$ligne = "";
					for($j = 0; $j < count ( $liste_champs ); $j ++) {
						if ($liste_variable [$j] != "ZNULL") {
							if ($ligne != "")
								$ligne .= " AND ";
							
							if ($liste_variable [$j] == "ZVIDE")
								$ligne .= $liste_champs [$j] . "=''";
							else {
								$ligne .= $liste_champs [$j] . "='" . $this->decode_donnee ( $liste_variable [$j] ) . "'";
							}
						}
					}
					$CODE_RETOUR [] .= "DELETE FROM " . $table . " WHERE " . $ligne . " ;";
					$this->onDebug ( $table . " : suppression de : " . $ligne, 1 );
				} else {
					return $this->onError ( "Le nombre de champ du set ne correspond pas au nombre de champs a supprimer.", "", 3006 );
				}
			}
		}
		
		$this->onDebug ( "La liste de suppression de " . $nb_en_cours . " lignes est OK.", 1 );
		$this->onDebug ( $CODE_RETOUR, 2 );
		
		return $CODE_RETOUR;
	}

	/**
	 * Ajoute les tuples manquants dans la base de destination.
	 *
	 * @param array $tableau_comparee Tableau renvoye par compare_tuple.
	 * @param string $table Table a modifier dans la base de destination.
	 * @param array $liste_champs Liste des champs de la table de destination.
	 * @return Bool TRUE si OK, FALSE sinon.
	 * @throws Exception
	 */
	public function liste_ajout_donnees($tableau_comparee, $table, $liste_champs) {
		$CODE_RETOUR = array ();
		$liste_a_ajouter = $this->retrouve_donnees_a_modifier ( $tableau_comparee, 0 );
		$nb_ligne = count ( $liste_a_ajouter );
		$nb_en_cours = $nb_ligne - 1;
		$this->onDebug ( "Il y a " . $nb_en_cours . " lignes a ajouter.", 1 );
		$this->onDebug ( "Liste des lignes a ajouter : ", 2 );
		$this->onDebug ( $liste_a_ajouter, 2 );
		
		$nb_ligne = count ( $liste_a_ajouter );
		$nb_en_cours = $nb_ligne - 1;
		$this->onDebug ( "Lignes a ajouter : " . $nb_en_cours, 1 );
		if ($nb_ligne > 1) {
			for($i = 1; $i < $nb_ligne; $i ++) {
				$liste_variable = explode ( "'param'", $liste_a_ajouter [$i] );
				if (count ( $liste_variable ) == count ( $liste_champs )) {
					$ligne = "";
					$insert = "";
					for($j = 0; $j < count ( $liste_champs ); $j ++) {
						if ($ligne != "")
							$ligne .= ",";
						$ligne .= $liste_champs [$j];
						if ($insert != "")
							$insert .= ",";
						$insert .= "'" . $this->decode_donnee ( $liste_variable [$j] ) . "'";
						$insert = str_replace ( "'ZNULL'", "NULL", $insert );
						$insert = str_replace ( "'ZVIDE'", "''", $insert );
					}
					$CODE_RETOUR [] .= "INSERT INTO " . $table . " (" . $ligne . ") VALUE (" . $insert . ") ;";
					$this->onDebug ( $table . " : ajout de : " . $insert, 1 );
				} else {
					return $this->onError ( "Le nombre de champ du set ne correspond pas au nombre de champs a inserer.", $liste_variable, 3007 );
				}
			}
		}
		
		$this->onDebug ( "La liste d'ajout de " . $nb_en_cours . " lignes est OK.", 1 );
		$this->onDebug ( $CODE_RETOUR, 2 );
		
		return $CODE_RETOUR;
	}

	/**
	 * *****************************************************************
	 */
	/**
	 * Concatene deux tableaux.
	 *
	 * @param array $tableau1 Tableau quelconque.
	 * @param array $tableau2 Tableau quelconque.
	 * @return array Renvoi les tableaux concatenes.
	 */
	public function concatene_tableau($tableau1, $tableau2) {
		if (is_array ( $tableau2 )) {
			foreach ( $tableau2 as $key => $data ) {
				if (is_array ( $data ) && count ( $data ) > 0)
					$tableau1 [$key] = $this->concatene_tableau ( $tableau1 [$key], $data );
				else
					$tableau1 [$key] = $data;
			}
		}
		
		return $tableau1;
	}

	/**
	 * *****************************************************************
	 */
	
	/**
	 * Ordonnance la synchronisation de chaque table du fichier de configuration.
	 *
	 * @param array &$resultat_sql_entree Connexion vers la base d'origine.
	 * @param array &$resultat_sql_sortie Connexion vers la base de destination.
	 * @param string $table Nom de la table a modifier.
	 * @param array $liste_champs Liste des champs de la table a modifier.
	 * @return comparaison_resultat_sql|FALSE
	 * @throws Exception
	 */
	public function synchro_table(&$resultat_sql_entree, &$resultat_sql_sortie, $table, $liste_champs = array()) {
		$this->onDebug ( "On synchronise la table : " . $table, 1 );
		//On reset les tableaux au depart
		$this->setTableauSupprime ( array () );
		$this->setTableauAjoute ( array () );
		// On recupere les donnees dans la table d'entree
		$this->onDebug ( "La base d'entree", 1 );
		$liste_data_entree = $this->prepare_donnees ( $resultat_sql_entree );
		
		// On recupere les donnees dans la table de sortie
		$this->onDebug ( "La base de sortie", 1 );
		$liste_data_sortie = $this->prepare_donnees ( $resultat_sql_sortie );
		
		// on compare les donnees
		$this->onDebug ( "On compare les donnees entre les bases.", 1 );
		$this->compare_tuple ( $liste_data_entree, $liste_data_sortie );
		$this->onDebug ( "Donnees a modifier :", 2 );
		$this->onDebug ( $liste_data_sortie, 2 );
		
		// enfin on envoi l'ajout et/ou la suppression dans 
		if (is_array ( $liste_data_sortie ) && count ( $liste_data_sortie ) > 0) {
			$this->onDebug ( "Liste des champs : ", 2 );
			$this->onDebug ( $liste_champs, 2 );
			
			$this->setTableauSupprime ( $this->liste_suppression_donnees ( $liste_data_sortie, $table, $liste_champs ) );
			
			$this->setTableauAjoute ( $this->liste_ajout_donnees ( $liste_data_sortie, $table, $liste_champs ) );
		} else {
			$this->onDebug ( "Aucune modification necessaire pour la table : " . $table, 1 );
		}
		
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getTableauAjoute() {
		return $this->tableau_ajoute;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTableauAjoute($tableau_ajoute) {
		$this->tableau_ajoute = $tableau_ajoute;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTableauSupprime() {
		return $this->tableau_supprime;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTableauSupprime($tableau_supprime) {
		$this->tableau_supprime = $tableau_supprime;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * Cette fonction fait un exit.
	 * Arguments reconnus :<br>
	 * --help
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gere la synchro des bases";
		$help [__CLASS__] ["text"] [] .= " <sql using=\"oui\">";
		$help [__CLASS__] ["text"] [] .= "  <liste_bases>";
		$help [__CLASS__] ["text"] [] .= "   <sql_entree using=\"oui\">";
		$help [__CLASS__] ["text"] [] .= "    <database>nom_database</database>";
		$help [__CLASS__] ["text"] [] .= "    <dbhost>serveur1</dbhost>";
		$help [__CLASS__] ["text"] [] .= "    <dbuser>nobody</dbuser>";
		$help [__CLASS__] ["text"] [] .= "    <dbpasswd>passwd</dbpasswd>";
		$help [__CLASS__] ["text"] [] .= "   </sql_entree>";
		$help [__CLASS__] ["text"] [] .= "   <sql_sortie using=\"oui\" >";
		$help [__CLASS__] ["text"] [] .= "    <database>nom_database2</database>";
		$help [__CLASS__] ["text"] [] .= "    <dbhost>serveur2</dbhost>";
		$help [__CLASS__] ["text"] [] .= "    <dbuser></dbuser>";
		$help [__CLASS__] ["text"] [] .= "    <dbpasswd>passwd</dbpasswd>";
		$help [__CLASS__] ["text"] [] .= "   </sql_sortie>";
		$help [__CLASS__] ["text"] [] .= "  </liste_bases>";
		$help [__CLASS__] ["text"] [] .= " </sql>";
		$help [__CLASS__] ["text"] [] .= " ";
		$help [__CLASS__] ["text"] [] .= " <tables>";
		$help [__CLASS__] ["text"] [] .= "  <nom_table>";
		$help [__CLASS__] ["text"] [] .= "   <requete_entree>select champ_1,champ_2 from nom_table where id=1</requete_entree>";
		$help [__CLASS__] ["text"] [] .= "   <requete_entree_secondaire>select champ_1,champ_2 from nom_table  </requete_entree_secondaire> Optionnel";
		$help [__CLASS__] ["text"] [] .= "   <requete_sortie>select champ_1,champ_1 from Rapport where id=1</requete_sortie>";
		$help [__CLASS__] ["text"] [] .= "   <table>Rapport</table>";
		$help [__CLASS__] ["text"] [] .= "  </nom_table>";
		$help [__CLASS__] ["text"] [] .= " </tables>";
		$help [__CLASS__] ["text"] [] .= " ";
		$help [__CLASS__] ["text"] [] .= "Dans le select, il ne faut pas mettre des \"integers\" ou il faut les nommer avec \"as\"";
		$help [__CLASS__] ["text"] [] .= " ";
		$help [__CLASS__] ["text"] [] .= "Pour les \"requete_entree_secondaire\", il faut avoir les memes champs que la \"requete_entree\" dans le select";
		
		return $help;
	}
}
?>
