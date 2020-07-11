<?php

/**
 * Steve Clifton
 * Strategy Pattern with Dependancy Injection
 */

$ducks = array();
$ducks[] = new Duck(new CantFly, new CantQuack, new NameDisplay('Rubber Duck!'));
$ducks[] = new Duck(new CanFly, new CanQuack, new NameDisplay('Pond Duck!'));
$ducks[] = new Duck(new CanFly, new CantQuack, new NameDisplay('Rocket Duck!'));

foreach ($ducks as $duck) {
	$duck->display();
	$duck->fly();
	$duck->quack();

	echo '<br>-----------------<br>';
}



/**
 * Generic duck class
 */
class Duck {
	protected $FlyBehaviour;
	protected $QuackBehaviour;
	protected $DisplayBehaviour;

	public function __construct(FlyBehaviour $fb, QuackBehaviour $qb, DisplayBehaviour $db) {
		$this->FlyBehaviour = $fb;
		$this->QuackBehaviour = $qb;
		$this->DisplayBehaviour = $db;
	}

	public function fly() {
		$this->FlyBehaviour->fly();
	}

	public function quack() {
		$this->QuackBehaviour->quack();
	}

	public function display() {
		$this->DisplayBehaviour->display();
	}
}

/*
 * Flying
 */
abstract class FlyBehaviour {
	abstract public function fly();
}
class CanFly extends FlyBehaviour {
	public function fly() {
		echo 'I can Fly :-)' . '<br>';
	}
}
class CantFly extends FlyBehaviour {
	public function fly() {
		echo 'I cant Fly :-(' . '<br>';
	}
}

/*
 * Quacking
 */
abstract class QuackBehaviour {
	abstract public function quack();
}
class CanQuack extends QuackBehaviour {
	public function quack() {
		echo 'I can Quack :-)' . '<br>';
	}
}
class CantQuack extends QuackBehaviour {
	public function quack() {
		echo 'I cant Quack :-(' . '<br>';
	}
}


/*
 * Display
 */
abstract class DisplayBehaviour {

	protected $name;

	public function __construct($name) {
		$this->name = $name;
	}

	abstract public function display();
}
class NameDisplay extends DisplayBehaviour {
	public function display() {
		echo 'My name is ' . $this->name . '<br>';
	}
}