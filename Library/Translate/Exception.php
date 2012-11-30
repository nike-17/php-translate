<?php

class Translate_Exception extends Exception {

	public function __construct($message, $code, $previous) {
		parent::__construct($message, $code, $previous);
	}

}