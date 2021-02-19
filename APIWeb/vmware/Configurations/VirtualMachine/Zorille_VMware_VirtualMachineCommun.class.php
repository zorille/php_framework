<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \stdClass as stdClass;
/**
 * class VirtualMachineCommun<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualMachineCommun extends Core\abstract_log {
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
