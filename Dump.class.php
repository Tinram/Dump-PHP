<?php

class Dump {

	/**
	* Display global names and backtrace.
	* Reflection used so new methods can be seamlessly added.
	*
	* Usage: new Dump( [ 'all' | string method_name | array (method_names) ] [, boolean output_type] );
	* e.g.
	*       new Dump();
	*       new Dump('displayVars', TRUE);
	*       new Dump( (array('displayMemory', 'backtrace')) );
	*
	* @author          Martin Latter
	* @copyright       Martin Latter June 2013
	* @version         1.01
	* @license         GNU GPL v3.0
	*/


	const BR = '<br><br>';


	/**
	* constructor, initiate method reflection
	*
	* @param    mixed $mMethodName [ string 'all' | string method_name | array method_names ]
	* @param    boolean $bPrintR,  toggle output format between var_dump() and print_r()
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
	* print memory usage
	*/

	private function displayMemory() {

		echo 'MEMORY USAGE' . self::BR;

		$sOs = number_format(memory_get_usage()) . ' bytes <small>(';
		$sOs .= number_format(memory_get_usage(TRUE)) . ' real</small>)<br>';

		echo $sOs;

	} # end displayMemoryUsage()


	/**
	* print variables
	*
	* @param     boolean $bPrintR,  toggle output format
	*/

	private function displayVars($bPrintR) {

		$aVars = get_defined_vars();

		if (!empty($aVars)) {

			echo 'VARIABLES' . self::BR;

			if (!$bPrintR) {
				var_dump($aVars);
			}
			else {
				print_r($aVars);
			}
		}
		else {
			echo 'No variables in global scope.';
		}

	} # end displayVars()


	/**
	* print functions
	*
	* @param     boolean $bPrintR,  toggle output format
	*/

	private function displayFunctions($bPrintR) {

		echo 'FUNCTIONS' . self::BR;

		$aFNs = get_defined_functions();

		if (!$bPrintR) {
			var_dump($aFNs['user']);
		}
		else {
			print_r($aFNs['user']);
		}

	} # end displayFunctions()


	/**
	* print classes
	*
	* @param     boolean $bPrintR, toggle output format
	*/

	private function displayClasses($bPrintR) {

		echo 'CLASSES' . self::BR;

		if (!$bPrintR) {
			var_dump(get_declared_classes());
		}
		else {
			print_r(get_declared_classes());
		}

	} # end displayClasses()


	/**
	* print backtrace
	*
	* @param     boolean $bPrintR, toggle output format
	*/

	private function backtrace($bPrintR) {

		echo 'BACKTRACE' . self::BR;

		debug_print_backtrace();

		echo self::BR;

		if (!$bPrintR) {
			var_dump(debug_backtrace());
		}
		else {
			print_r(debug_backtrace());
		}

	} # end backtrace()


	/**
	* add more methods here
	*/

} # end Dump {}

?>