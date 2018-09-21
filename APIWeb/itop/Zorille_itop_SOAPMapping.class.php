<?php
/**
 * @author combodo
 * @package Lib
 *
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class SOAPMapping<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPMapping
{
	static function GetMapping()
	{
		$aSOAPMapping = array(
				'SearchCondition' => 'SOAPSearchCondition',
				'ExternalKeySearch' => 'SOAPExternalKeySearch',
				'AttributeValue' => 'SOAPAttributeValue',
				'LinkCreationSpec' => 'SOAPLinkCreationSpec',
				'KeyValue' => 'SOAPKeyValue',
				'LogMessage' => 'SOAPLogMessage',
				'ResultLog' => 'SOAPResultLog',
				'ResultData' => 'SOAPKeyValue',
				'ResultMessage' => 'SOAPResultMessage',
				'Result' => 'SOAPResult',
				'SimpleResult' => 'SOAPSimpleResult',
		);
		return $aSOAPMapping;
	}
}

?>