<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualMachineCommun<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualMachineCommun extends abstract_log {
	/**
	 * Retrouve la valeur du ManagedObjectReference (MOR)
	 * @param stdClass|array $donnees_MOR
	 * @return string
	 */
	static public function retrouve_valeur_MOR($donnees_MOR) {
		if (is_array ( $donnees_MOR )) {
			return $donnees_MOR ["_"];
		}
		return $donnees_MOR->_;
	}
}

?>
