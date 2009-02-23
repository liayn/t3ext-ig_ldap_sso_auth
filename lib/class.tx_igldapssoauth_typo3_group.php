<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2007 Michael Gagnon <mgagnon@infoglobe.ca>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Class tx_igldapssoauth_typo3_group for the 'ig_ldap_sso_auth' extension.
 *
 * @author	Michael Gagnon <mgagnon@infoglobe.ca>
 * @package	TYPO3
 * @subpackage	tx_igldapssoauth_typo3_group
 *
 */
class tx_igldapssoauth_typo3_group {

	function init ($table = null) {

		// Get users table structure.
		$typo3_group_default = iglib_db::get_columns_from($table);

		foreach ($typo3_group_default as $field => $value) {

			$typo3_group[$field] = null;

		}

		return $typo3_group;

	}

	function select ($table = null, $uid = 0, $pid = null, $title = null, $dn = null) {

		// Search with uid and pid.
		if ($uid) {

			$QUERY = array (
				"SELECT" => "*",
				"FROM" => $table,
				"WHERE" => "uid=".$uid,
				"GROUP_BY" => "",
				"ORDER_BY" => "",
				"LIMIT" => "",
				"UID_INDEX_FIELD" => "" ,
			);

		// Search with DN, title and pid.
		} else {

			$QUERY = array (
				"SELECT" => "*",
				"FROM" => $table,
				"WHERE" => "tx_igldapssoauth_dn='".$dn."' AND pid IN (".$pid.")",
				"GROUP_BY" => "",
				"ORDER_BY" => "",
				"LIMIT" => "",
				"UID_INDEX_FIELD" => "" ,
			);

		}

		// Return TYPO3 group.
		return iglib_db::select($QUERY);

	}

	function insert ($table = null, $typo3_group = array()) {

		$QUERY = array (
			"TABLE" => $table,
			"FIELDS_VALUES" => $typo3_group,
			"NO_QUOTE_FIELDS" => false,
		);

		$uid = iglib_db::insert($QUERY);

		$QUERY = array (
			"SELECT" => "*",
			"FROM" => $table,
			"WHERE" => "uid=".$uid,
			"GROUP_BY" => "",
			"ORDER_BY" => "",
			"LIMIT" => "",
			"UID_INDEX_FIELD" => "",
		);

		return iglib_db::select($QUERY);

	}


	function update ($table = null, $typo3_group = array()) {

		$QUERY = array (
			"TABLE" => $table,
			"WHERE" => "uid=".$typo3_group['uid'],
			"FIELDS_VALUES" => $typo3_group,
			"NO_QUOTE_FIELDS" => false,
		);

		return iglib_db::update($QUERY);

	}

	function get_title ($ldap_user = array(), $mapping = array()) {

		if (!$mapping) { return null; }

		if (array_key_exists('title', $mapping) && preg_match("`<([^$]*)>`", $mapping['title'], $attribute)) {

			if ($attribute[1] == 'dn') {

				return $ldap_user[$attribute[1]];

			}

			return $ldap_user[$attribute[1]][0];

		}

		return null;

	}


}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ig_ldap_sso_auth/lib/class.tx_igldapssoauth_typo3_group.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ig_ldap_sso_auth/lib/class.tx_igldapssoauth_typo3_group.php']);
}

?>