<?php
/****************************************************
 * Lean mean web machine
 *
 * Site specific validator rules
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-09
 *
 ****************************************************/

class ValidatorRules extends ValidatorRulesBase
{

	/**
	 * Check for unique login name
	 *
	 * @param string $login
	 * @return mixed
	 */
	public function uniqueLogin($login){
		// if(!model('Users/User')->checkLogin($login)) return true				
		return 'Helaas, deze gebruikersnaam is al in gebruik';
	}

}
