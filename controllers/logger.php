<?php

class Logger {
	private $log_name;
	private $file_pointer;
	private $error_prefix;
	private $debug_prefix;
	private $info_prefix;

	public function __construct() {

		$log_name = "events.log";

		$error_prefix = "ERROR: ";
		$debug_prefix = "DEBUG: ";
		$info_prefix  = " INFO: ";
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

	public function log_error($content_string) {
		
		$this->write($this->error_prefix,$content_string);
	}

	public function log_debug($content_string) {
		
		$this->write($this->debug_prefix,$content_string);
	}

	public function log_info($content_string) {
		
		$this->write($this->info_prefix,$content_string);
	}
}
