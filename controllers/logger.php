<?php

class Logger {

	private $log_name;
	private $file_pointer;

	private $error_prefix; // Error
	private $debug_prefix; // Debug info
	private $info_prefix;  // Info
	private $userr_prefix; // User error

	private $debug_mode_active;

	public function __construct() {

		$this->log_name = "events.log";

		$this->error_prefix = "ERROR: ";
		$this->debug_prefix = "DEBUG: ";
		$this->info_prefix  = " INFO: ";
		$this->userr_prefix = "USERR: ";

		$this->debug_mode_active = FALSE;
	}

	private function open() {
		
		$file_pointer = fopen($log_name,"a");
	}

	private function close() {
		
		fclose($this->file_pointer);
	}

	private function write($entry_type_string,$content_string) {

		$string_to_write = date("Y-m-d H:i:s ") . $entry_type_string . $content_string . "\n";

		fwrite($this->file_pointer,$string_to_write);
	}

	public function activate_debug_mode() {

		$this->debug_mode_active = TRUE;
	}

	public function deactivate_debug_mode() {

		$this->debug_mode_active = FALSE;
	}

	public function is_debug_mode_active() {

		return $this->debug_mode_active;
	}

	public function log_error($content_string) {
		
		$this->write($this->error_prefix,$content_string);
	}

	public function log_debug($content_string) {

		if($this->debug_mode_active) {

			$this->write($this->debug_prefix,$content_string);
		}
	}

	public function log_info($content_string) {
		
		$this->write($this->info_prefix,$content_string);
	}

	public function log_userr($content_string) {
		
		$this->write($this->userr_prefix,$content_string);
	}
}
