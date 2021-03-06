<?php
/**
 * Onserver Pattern
 * One to many dependancy between objects, when the observable changes state, all of its dependants are notified
 *    and they can choose which updates they want to receive.
 *
 * Chosen to pass a WeatherStation instance to the observers to allow THEM to choose which data they wish to pull
 *
 * Example : chatroom
 *  - chatroom is the observable, users are the observers
 */


interface Observable {

	/* Add an observer - an object who wants to be notified of changes */
	public function registerObserver(Observer $ob);

	/* Remove an observer */
	public function removeObserver(Observer $ob);

	/* Used to notify ALL observers than an update is available */
	public function notify();
}
final class WeatherStation implements Observable {

	protected float $temperature;
	protected float $humidity;
	protected array $observers = [];

	public function registerObserver(Observer $ob) {
		echo 'Log: Adding observer' . PHP_EOL;
		$this->observers[] = $ob;
	}

	public function removeObserver(Observer $ob) {
		foreach ($this->observers as $key => $observer) {
			if ($observer == $ob) {
				echo 'Log: Removing observer' . PHP_EOL;
				unset($this->observers[$key]);
			}
		}
	}

	public function notify() {
		foreach ($this->observers as $observer) {
			$observer->update($this);
		}
	}

	public function getTemperature() : float {
		return $this->temperature;
	}

	public function getHumidity() : float {
		return $this->humidity;
	}

	/* Dummy method to randomly generate temp and notify observers */
	public function processTemperature() {
		$this->setTemperature((float) rand(5, 50) * 1.0);
		$this->setHumidity((float) rand(80, 99) * 1.0);
		$this->notify();
	}

	protected function setTemperature(float $temperature) {
		$this->temperature = $temperature;
	}

	protected function setHumidity(float $humidity) {
		$this->humidity = $humidity;
	}
}




interface Observer {
	/* Used to tell the observer an update is available */
	public function update(WeatherStation $ws);
}
interface Display {
	/* Used to display some data */
	public function display();
}
final class DisplayTelevision implements Observer, Display {

	protected Observable $observable;

	public function update(WeatherStation $ws) {
		// Set the observable to the one passed
		$this->observable = $ws;

		// Do some things, then call display
		$this->display();
	}

	public function display() {
		$temperature = $this->observable->getTemperature();
		echo __CLASS__ . ' : Temp : ' . number_format($temperature, 2) . PHP_EOL;
	}
}

final class DisplayComputer implements Observer, Display {

	protected Observable $observable;

	public function update(WeatherStation $ws) {
		// Set the observable to the one passed
		$this->observable = $ws;

		// Do some things, then call display
		$this->display();
	}

	public function display() {
		$temperature = $this->observable->getHumidity();
		echo __CLASS__ . ' : Humidity : ' . number_format($temperature, 2) . PHP_EOL;
	}
}



/**
 * Main below
 */

// Create concrete weather station object
$weatherStation = new WeatherStation();

// Create television in the kitchen as the display
$televisionKitchen = new DisplayTelevision();
// Add the tv kitchen as an observer of the weather station
$weatherStation->registerObserver($televisionKitchen);

// Create computer in the living room as the display
$computerLivingroom = new DisplayComputer();
// Add the computer livingroom as an observer of the weather station
$weatherStation->registerObserver($computerLivingroom);

// Generate some data, notify observers
$weatherStation->processTemperature();

// Remove tv kitchen as an observer
$weatherStation->removeObserver($televisionKitchen);

// Generate some more data, notify remaining observer
$weatherStation->processTemperature();
