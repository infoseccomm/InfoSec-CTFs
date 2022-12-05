<?php
	class Read {
		
		// Still in development phase

		private $file_name;

		public function print($val) {
			include($this->file_name);
			echo $val;
		}
	}
?>