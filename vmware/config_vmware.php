<?php
/**
 * Fichier de configuration du package Cacti
 *
 * @author dvargas
 * @package Lib
 * @subpackage VMWare
 */
$rep_vmware=$rep_lib . "/vmware";
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/Network" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine/bootOptions" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine/config" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine/customization" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine/description" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine/device" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine/device/profile" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine/device/backing" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Configurations/VirtualMachine/relocate" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/ManagedEntity" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/ManagedObject" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Managers" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/Services" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/vim25" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_vmware . "/WebService" );
?>