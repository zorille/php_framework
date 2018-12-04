<?php
/**
 * @author combodo
 * @package Lib
 *
 */
namespace Zorille\framework;
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