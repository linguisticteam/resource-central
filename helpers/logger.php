<?php
defined('START') or die();

class Logger {

	private $log_name;

	private $error_prefix; // Error
	private $debug_prefix; // Debug info
	private $info_prefix;  // Info
	private $userr_prefix; // User error

	private $debug_mode_active;

	public function __construct() {

		$this->Initialize(dirname(dirname(__FILE__)) . "/admin/logs/events.log");
	}

	private function Initialize($log_name) {

		$this->log_name = $log_name;

		$this->error_prefix = "ERROR: ";
		$this->debug_prefix = "DEBUG: ";
		$this->info_prefix  = " INFO: ";
		$this->userr_prefix = "USERR: ";
		
		$this->debug_mode_active = FALSE;
	}

	private function write($entry_type_string,$content_string) {

		$string_to_write = date("Y-m-d H:i:s") . ", " . $entry_type_string . $content_string . "\n";

		file_put_contents($this->log_name,$string_to_write,FILE_APPEND);
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

	public function log_error($content_string, $file, $line) {
		$content_string = $content_string . ', FILE: ' . $file . ', LINE: ' . $line;
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
