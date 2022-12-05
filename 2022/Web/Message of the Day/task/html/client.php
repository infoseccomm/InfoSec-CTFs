<?php
	include('read.php');

	class Client {

		private $name;
		private $tmp;

		function __construct($name) {
			$this->name = $name;
			$this->tmp = new Message();
		}

		function __destruct() {
			$this->tmp->print($this->name);
		}
	}

	class Message {

		public function print($val) {
			echo "Tigers and bears may be stronger predators, but wolves don't perform in circus, ". $val ."!";
		}

	}

?>