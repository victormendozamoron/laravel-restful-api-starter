<?php
/**
 *	@author Jeremy Legros
 *	@version 0.1.0
 * 	@license http://opensource.org/licenses/MIT MIT
 */

use Illuminate\Validation\Validator;

/**
 *	Convenient method to format response messages
 */
class ApiResponse {

	/**
	 *	@param Validation $validator Illuminate\Validation\Validator instance to format errors
	 * 	@return json String representation of the validation errors
	 */
	public static function validation ($validator) {
		if ( !($validator instanceof Validator) )
			throw new Exception('Argument is not a Validator instance ('.get_class($validator).' found).');

		$response = 'success';

		if ( $validator->fails() ) {
			$errors = $validator->messages()->toArray();
			$response = '';
			if ( is_array($errors) ) {
				foreach ($errors as $key => $value) {
					if ( self::is_assoc_array($value) ){
						$response .= $key.' ';
						foreach ($value as $key => $val) {
							$response .= strtolower($key).'. ';
						}
					}
					else for ($i=0; $i <count($value) ; $i++) { 
							$response .= $value[$i].' ';
					}
				}
			}
			else $response .= $errors;
		}
		
		return json_encode(array( 'validation' => $response) );
	}

	/**
	 *	@param string $string Message to format
	 * 	@return json String representation of the error message
	 */
	public static function error( $string ){
		return json_encode(array('error'=>$string));
	}

	/**
	 *	@param string $string Message to format
	 * 	@return json String representation of the warning message
	 */
	public static function warning( $string ){
		return json_encode(array('warning'=>$string));
	}

	/**
	 *	@param string $string Message to format
	 * 	@return json String representation of the success message
	 */
	public static function success( $string ){
		return json_encode(array('success'=>$string));
	}

	/**
	 *	@param string $string Message to format
	 * 	@return json String representation of the message
	 */
	public static function json( $arg ){
		return json_encode( $arg );
	}

	protected static function is_assoc_array($array){
		if ( empty($array) ) return false;
    	return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

}