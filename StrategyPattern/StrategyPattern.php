<?php
/**
 * Steve Clifton
 * Strategy Pattern with constructor Dependancy Injection
 *
 */

/* Flying Interface */
interface FlyBehaviour {
	public function fly();
}
final class CanFly implements FlyBehaviour {
	public function fly() {
		echo 'I can Fly :-)' . PHP_EOL;
	}
}
final class CantFly implements FlyBehaviour {
	public function fly() {
		echo 'I cant Fly :-(' . PHP_EOL;
	}
}

/* Quacking Abstract Class */
abstract class QuackBehaviour {
	abstract public function quack();
}
final class CanQuack extends QuackBehaviour {
	public function quack() {
		echo 'I can Quack :-)' . PHP_EOL;
	}
}
final class CantQuack extends QuackBehaviour {
	public function quack() {
		echo 'I cant Quack :-(' . PHP_EOL;
	}
}

/* Display */
abstract class DisplayBehaviour {
	protected $name;
	public function __construct($name) {
		$this->name = $name;
	}
	abstract public function display();
}
final class NameDisplay extends DisplayBehaviour {
	public function display() {
		echo 'My name is ' . $this->name . PHP_EOL;
	}
}

/** Generic duck class */
class Duck {
	protected FlyBehaviour $flyBehaviour;
	protected QuackBehaviour $quackBehaviour;
	protected DisplayBehaviour $displayBehaviour;

	public function __construct(FlyBehaviour $fb, QuackBehaviour $qb, DisplayBehaviour $db) {
		$this->flyBehaviour = $fb;
		$this->quackBehaviour = $qb;
		$this->displayBehaviour = $db;
	}
	public function fly() {
		$this->flyBehaviour->fly();
	}
	public function quack() {
		$this->quackBehaviour->quack();
	}
	public function display() {
		$this->displayBehaviour->display();
	}
	public function setFlyBehavior(FlyBehaviour $fb) {
		$this->flyBehaviour = $fb;
	}
}

/* Main */

$ducks = [
	new Duck(new CantFly, new CantQuack, new NameDisplay('Rubber Duck!')),
	new Duck(new CanFly, new CanQuack, new NameDisplay('Pond Duck!')),
	new Duck(new CanFly, new CantQuack, new NameDisplay('Rocket Duck!'))
];

foreach ($ducks as $duck) {
	$duck->display();
	$duck->fly();
	$duck->quack();
	echo PHP_EOL;
}


// Create a new duck with 1 fly behavior, and then change it

$waterDuck = new Duck(new CanFly, new CanQuack, new NameDisplay('Water Duck'));
$waterDuck->display();
$waterDuck->fly();
// Had an accident, cant fly now
$waterDuck->setFlyBehavior(new CantFly);
$waterDuck->fly();







/**
 Notes

The Strategy pattern defines a family of algorithms, encapsulates each one, and makes them
  interchangable.
Strategy lets the algorithm vary independantly from clients that use it

Take what varies and 'encapsulate' it so you wont affect the rest of your code.
Fewer unintended consequences from code changes + more flexibility

HAS-A is better than IS-A

'Favor composition over inheritance'
Putting two classes together like this is composition
Instad of inheriting their behavior, the ducks get their behavior by being composed
  of the right behavior object





 */