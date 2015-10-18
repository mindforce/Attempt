<?php
/**
 * Attempt Component Class
 *
 * Based on http://bakery.cakephp.org/articles/aep_/2006/11/04/brute-force-protection
 *
 * @author Thomas Heymann
 * @version	0.1
 * @license	http://www.opensource.org/licenses/mit-license.php The MIT License
 * @package app
 * @subpackage app.controllers.components
 **/

class AttemptComponent extends Component {
	public $settings = array(
		'attemptLimit' => 5,
		'attemptDuration' => '+1 hour',
		
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = array_merge($this->settings, (array)$settings);
		$this->Controller = $collection->getController();
		parent::__construct($collection, $settings);
	}

	// Called after the Controller::beforeFilter() and before the controller action
	public function startup(Controller $controller) {
		$this->Controller = $controller;
		$this->Attempt = ClassRegistry::init('Attempt.Attempt');
	}

	public function count() {
		return $this->Attempt->count(
			$this->Controller->request->clientIp(),
			$this->Controller->request['action']
		);
	}

	public function limit() {
		$this->cleanup();
		return $this->Attempt->limit(
			$this->Controller->request->clientIp(),
			$this->Controller->request['action'],
			$this->settings['attemptLimit']
		);
	}

	public function fail() {
		return $this->Attempt->fail(
			$this->Controller->request->clientIp(),
			$this->Controller->request['action'],
			$this->settings['attemptDuration']
		);
	}

	public function reset() {
		return $this->Attempt->reset(
			$this->Controller->request->clientIp(),
			$this->Controller->request['action']
		);
	}

	public function cleanup($time = null) {
		return $this->Attempt->cleanup($time);
	}
}
