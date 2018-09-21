<?php
/**
 * Copyright 2013 splunk, Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"): you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
namespace Zorille\framework;
use \Exception as Exception;
use \SimpleXMLElement as SimpleXMLElement;
/**
 * Contains utilities for parsing Atom feeds received from the splunk REST API.
 * 
 * @package splunk
 * @internal
 */
abstract class splunk_AtomFeed extends abstract_log {
	/**
	 * var privee
	 * Name of the 's' namespace in splunk Atom feeds.
	 * @access private
	 * @var string
	 */
	private $NS_S = 'http://dev.splunk.com/ns/rest';

	/**
	 * Parses and returns the value inside the specified XML element.
	 *
	 * @param SimpleXMLElement $containerXml
	 * @return array|string
	 */
	public function parseValueInside($containerXml) {
		$dictValue = $containerXml ->children ( $this ->getNameSpace () )->dict;
		$listValue = $containerXml ->children ( $this ->getNameSpace () )->list;
		
		if ($this ->getListeOptions () 
			->elementExists ( $dictValue )) {
			return $this ->parseDict ( $dictValue );
		} else if ($this ->getListeOptions () 
			->elementExists ( $listValue )) {
			return $this ->parseList ( $listValue );
		}
		
		return $this ->getListeOptions () 
			->getTextContent ( $containerXml );
	}

	/**
	 * Example of $dictXml:
	 *
	 * <s:dict>
	 *     <s:key name="k1">v1</s:key>
	 *     <s:key name="k2">v2</s:key>
	 * </s:dict>
	 * @param SimpleXMLElement $dictXml
	 * @return array
	 */
	public function parseDict($dictXml) {
		$dict = array ();
		foreach ( $dictXml ->children ( $this ->getNameSpace () )->key as $keyXml ) {
			$key = $this ->getListeOptions () 
				->getAttributeValue ( $keyXml, 'name' );
			$value = $this ->parseValueInside ( $keyXml );
			
			$dict [$key] = $value;
		}
		return $dict;
	}

	/**
	 * Example of $listXml:
	 *
	 * <s:list>
	 *     <s:item>e1</s:item>
	 *     <s:item>e2</s:item>
	 * </s:list>
	 * @param SimpleXMLElement $listXml
	 * @return array
	 */
	public function parseList($listXml) {
		$list = array ();
		foreach ( $listXml ->children ( $this ->getNameSpace () )->item as $itemXml ) {
			$list [] = $this ->parseValueInside ( $itemXml );
		}
		return $list;
	}

	/** Returns the <entry> element inside the root element.
	 * @param SimpleXMLElement $simpleXML
	 * @return array
	 * @throws Exception
	 */
	public function recupereListEntry($simpleXML) {
		$liste_entry = array ();
		if (isset ( $simpleXML->entry ) && is_object ( $simpleXML->entry )) {
			if ($simpleXML->entry ->count () > 1) {
				
				foreach ( $simpleXML->entry as $content ) {
					array_push ( $liste_entry, $content );
				}
			} else {
				$liste_entry = array ( 
						$simpleXML->entry );
			}
		}
		
		return $liste_entry;
	}

	/** Returns the <result> element inside the root element.
	 * @param SimpleXMLElement $simpleXML
	 * @return array
	 * @throws Exception
	 */
	public function recupereListResult($simpleXML) {
		$liste_result = array ();
		if (isset ( $simpleXML->result ) && is_object ( $simpleXML->result )) {
			if ($simpleXML->result ->count () > 1) {
				
				foreach ( $simpleXML->result as $content ) {
					array_push ( $liste_result, $this ->recupereListField ( $content ) );
				}
			} else {
				$liste_result = array ( 
						$this ->recupereListField ( $simpleXML->result ) );
			}
		}
		
		return $liste_result;
	}

	/** Returns the <field> element inside the root element.
	 * @param SimpleXMLElement $simpleXML
	 * @return array
	 * @throws Exception
	 */
	public function recupereListField($simpleXML) {
		$liste_field = array ();
		if (isset ( $simpleXML->field ) && is_object ( $simpleXML->field )) {
			if ($simpleXML->field ->count () > 1) {
				foreach ( $simpleXML->field as $content ) {
					$liste_field = array_merge ( $liste_field, $this ->recupereField ( $content ) );
				}
			} else {
				$liste_field = array_merge ( $liste_field, $this ->recupereField ( $simpleXML->field ) );
			}
		}
		
		return $liste_field;
	}

	/**
	 * 
	 * @param SimpleXMLElement $simpleXML
	 * @return array
	 */
	public function recupereField($simpleXML) {
		$attr = $this ->getListeOptions () 
			->getAttributeValue ( $simpleXML, 'k' );
		if ($attr != NULL && isset ( $simpleXML->value->text )) {
			$valeur = ( string ) $simpleXML->value->text;
			return array ( 
					$attr => $valeur );
		}
		
		return array ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getNameSpace() {
		return $this->NS_S;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNameSpace($namespace) {
		$this->NS_S = $namespace;
		return $this;
	}

/**
 * ***************************** ACCESSEURS *******************************
 */
}