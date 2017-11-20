<?php

final class Dump {

	/**
		* Display global names and backtrace.
		*
		* Reflection used so new methods can be seamlessly added.
		*
		* Usage: new Dump( [ 'all' | string method_name | array (method_names) ] [, boolean output_type] );
		*
		* Examples:
		*           new Dump();
		*           new Dump('displayFunctions', TRUE);
		*           new Dump( array('displayMemory', 'backtrace') );
		*
		* @author        Martin Latter
		* @copyright     Martin Latter June 2013
		* @version       1.02
		* @license       GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
		* @link          https://github.com/Tinram/Dump-PHP.git
	*/


	const BR = '<br><br>';


	/**
		* Constructor, initiate method reflection.
		*
		* @param   mixed $mMethodName [ string 'all' | string method_name | array method_names ]
		* @param   boolean $bPrintR,  toggle output format between var_dump() and print_r()
	*/

	public function __construct($mMethodName = 'all', $bPrintR = FALSE) {

		$bPrintR = (is_bool($bPrintR)) ? $bPrintR : FALSE; # override in case bad argument is sent

		$sSeparator = self::BR . '* * * * * * * * *' . self::BR; # keep separator out of class scope and therefore backtrace

		$oClass = new ReflectionClass(__CLASS__);

		$aClassInfo = $oClass->getMethods(ReflectionMethod::IS_PRIVATE);

		if ($mMethodName === 'all') {

			foreach ($aClassInfo as $oNCPair) {

				call_user_func_array(array(__CLASS__, $oNCPair->name), array($bPrintR));
				echo $sSeparator;
			}
		}
		else if (is_array($mMethodName)) {

			foreach ($mMethodName as $sMN) {

				if (method_exists(__CLASS__, $sMN)) {

					call_user_func_array(array(__CLASS__, $sMN), array($bPrintR));
					echo $sSeparator;
				}
			}
		}
		else if (method_exists(__CLASS__, $mMethodName)) {

			call_user_func_array(array(__CLASS__, $mMethodName), array($bPrintR));
			echo $sSeparator;
		}
		else {

			die('<p style="color:#c00;font-weight:bold;">The ' . __CLASS__ . '::' . $mMethodName . '() method does not exist!</p>');
		}

	} # end __construct()


	/**
		* Print memory usage.
	*/

	private function displayMemory() {

		echo 'MEMORY USAGE' . self::BR;

		$sOs = number_format(memory_get_usage()) . ' bytes <small>(';
		$sOs .= number_format(memory_get_usage(TRUE)) . ' real</small>)<br>';

		echo $sOs;

	} # end displayMemory()


	/**
		* Print constants.
		*
		* @param   boolean $bPrintR,  toggle output format
	*/

	private function displayConstants($bPrintR) {

		echo 'CONSTANTS' . self::BR;

		$aConsts = get_defined_constants();

		if ( ! $bPrintR) {
			var_dump($aConsts);
		}
		else {
			print_r($aConsts);
		}

	} # end displayConstants()


	/**
		* Print functions.
		*
		* @param   boolean $bPrintR,  toggle output format
	*/

	private function displayFunctions($bPrintR) {

		echo 'FUNCTIONS' . self::BR;

		$aFNs = get_defined_functions();

		if ( ! $bPrintR) {
			var_dump($aFNs['user']);
		}
		else {
			print_r($aFNs['user']);
		}

	} # end displayFunctions()


	/**
		* Print classes.
		*
		* @param   boolean $bPrintR, toggle output format
	*/

	private function displayClasses($bPrintR) {

		echo 'CLASSES' . self::BR;

		if ( ! $bPrintR) {
			var_dump(get_declared_classes());
		}
		else {
			print_r(get_declared_classes());
		}

	} # end displayClasses()


	/**
		* Print variables.
		*
		* @param   boolean $bPrintR, toggle output format
	*/

	private function displayVars($bPrintR) {

		echo 'VARIABLES' . self::BR;

		if ( ! $bPrintR) {
			var_dump($GLOBALS);
		}
		else {
			print_r($GLOBALS);
		}

	} # end displayVars()


	/**
		* Print includes / requires.
		*
		* @param   boolean $bPrintR, toggle output format
	*/

	private function displayIncludes($bPrintR) {

		echo 'INCLUDES' . self::BR;

		$aIncludes = get_included_files();

		foreach ($aIncludes as $k => $sInclude) {

			if (($sInclude === __FILE__) || (basename($sInclude) === $_SERVER['SCRIPT_NAME'])) {
				unset($aIncludes[$k]);
			}
		}

		if ( ! empty($aIncludes)) {

			if ( ! $bPrintR) {
				var_dump($aIncludes);
			}
			else {
				print_r($aIncludes);
			}
		}
		else {
			echo 'No includes found.';
		}

	} # end displayIncludes()


	/**
		* Print backtrace.
		*
		* @param   boolean $bPrintR, toggle output format
	*/

	private function backtrace($bPrintR) {

		echo 'BACKTRACE' . self::BR;

		debug_print_backtrace();

		echo self::BR;

		if ( ! $bPrintR) {
			var_dump(debug_backtrace());
		}
		else {
			print_r(debug_backtrace());
		}

	} # end backtrace()


	/**
		* Add more methods here.
	*/

}

?>