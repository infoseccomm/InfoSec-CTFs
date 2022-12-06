 <?php
//Test dev Object Oriented Programming in PHP
ini_set('display_errors', 'Off');
@session_start();
class Fruit {
  // Properties
  public $name;
  public $color;

  // Methods
  function set_name($name) {
    $this->name = $name;
  }
  function get_name() {
    return $this->name;
  }
  function set_color($color) {
    $this->color = $color;
  }
  function get_color() {
    return $this->color;
  }
}
$apple = new Fruit();
$apple->set_name('Apple');
$apple->set_color('Red');
echo "Name: " . $apple->get_name();
echo "<br>";
echo "Color: " . $apple->get_color();
class test{
  private $destruct_cmd='';
  private $close_cmd = '';
  public $message;

  function __wakeup(){
    throw new \LogicException($this->message.":".$this->cmd);
  }
  function __destruct(){
    system($this->destruct_cmd);
  }
  public function close(){
    system($this->close_cmd);
  }
}

class test1{
  function __destruct(){
    $this->logger->close();
  }

}
class Name {
   var $_firstName;
   var $_lastName;
   
   function Name($first_name, $last_name) {
      $this->_firstName = $first_name;
      $this->_lastName = $last_name;
   }
   
   function toString() {
      return($this->_lastName .", " .$this->_firstName);
   }
}
class NameSub1 extends Name {
   var $_middleInitial;
   function NameSub1($first_name, $middle_initial, $last_name) {
      Name::Name($first_name, $last_name);
      $this->_middleInitial = $middle_initial;
   }
   function toString() {
      return(Name::toString() . " " . $this->_middleInitial);
   }
}
class employee {  
  public $name;  
  var $salary;  
  function set_name($name) {  
    $this->name = $name;  
  }  
    function set_salary($salary) {  
    $this->salary = $salary;  
  }  
}  
$employee_1 = new employee();  
$employee_1->set_name("JOHN");  
$employee_1->set_salary(" $ 2000000");  
  
$employee_2 = new employee();  
$employee_2->set_name("ROCK");  
$employee_2->set_salary(" $ 1200000");  
echo $employee_1->name;  
echo $employee_1->salary;  
echo "<br>";  
echo $employee_2->name;  
echo $employee_2->salary;  
$deser=$_GET['deser'];
if($deser){
  if(substr($deser, 0,1)=="O"){
    die('objects prohinited!');
  }else{
    var_dump(unserialize($deser));
  }
}else{
  highlight_file(__FILE__);
}
?>

