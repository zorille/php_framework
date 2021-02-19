<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \ArrayObject as ArrayObject;
/**
 * class CustomizationIpV6Generator<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class CustomizationIpV6Generator extends Core\abstract_log {

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();

		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/

/************************* Accesseurs ***********************/
}

?>
