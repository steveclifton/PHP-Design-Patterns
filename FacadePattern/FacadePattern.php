<?php

/**
 * Facade is a structural design pattern that provides a simplified (but limited) interface to a complex system of classes, library or framework.
 * Facade decreases the overall complexity of the application, it also helps to move unwanted dependencies to one place.
 */

/**
 * Used to control our Store
 */
class Store {

	protected array $items = [];

	protected array $sales = [];

	public function __construct()
	{
		// Add a sale
		$this->sales = [
			(object) [
				'saleid' => 1,
				'sale' => '$5 Off!',
				'discount' => '5',
				'type' => '$'
			]

		];
	}

	/**
	 * Adds an item to the store
	 * - applies any sales
	 *
	 * @see applySales
	 * @param stdClass $item
	 * @return void
	 */
	public function addItem(stdClass $item)
	{
		$this->applySales($item);

		$this->items[$item->itemid] = $item;

		return true;
	}

	/**
	 * Attempts to apply all the sales to the item
	 *
	 * @see applySale
	 * @param stdClass $item
	 * @return void
	 */
	protected function applySales(stdClass $item)
	{
		foreach ($this->sales as $sale) {
			$return = $this->applySale($sale, $item);

			// If a sale has been applied, dont attempt to apply more
			if (!empty($return)) {
				break;
			}

		}

		return null;
	}

	/**
	 * Attempts to apply a sale to an item
	 *
	 * @param stdClass $sale
	 * @param stdClass $item
	 * @return void
	 */
	protected function applySale(stdClass $sale, stdClass &$item)
	{
		if (!empty($item->saleid)) {
			return null;
		}

		switch ($sale->type) {

			case '$' :
				$item->price -= $sale->discount;
				break;

			case '%' :
				$discount = round($item->price * $sale->discount);

				$item->price -= $discount;
				break;
		}

		$item->saleid = $sale->saleid;

		return true;


	}

	/**
	 * Returns the Stores items
	 *
	 * @return void
	 */
	public function getItems()
	{
		return $this->items;
	}

}

/**
 * Facade to wrap the Store class
 */
class StoreFacade {

	protected Store $store;

	public function __construct()
	{
		$this->store = new Store();
	}

	/**
	 * Add an item to the store
	 *
	 * @param stdClass $item
	 * @return void
	 */
	public function addItem(stdClass $item)
	{
		return $this->store->addItem($item);
	}

	/**
	 * Get all the stores items
	 *
	 * @return void
	 */
	public function getItems()
	{
		return $this->store->getItems();
	}

}

// Create a new store facade
$store = new StoreFacade();

// Add an Item
$store->addItem(
	(object) [
		'itemid' => 1,
		'price' => 100.00,
		'name' => 'Book'
	]
);

// Get the stores items
print_r($store->getItems());
