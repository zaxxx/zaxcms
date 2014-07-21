<?php


namespace Zax\Bootstraps;
use Nette,
	Zax;

require_once __DIR__ . '/../IBootstrap.php';

/**
 * Class Bootstrap
 *
 * Simple bootstrap implementation
 *
 * @package Zax\Bootstraps
 */
class Bootstrap implements Zax\IBootstrap {

	protected $appDir;

	protected $rootDir;

	protected $debuggers = [];

	protected $debugEmails = [];

	protected $enableInject = FALSE;

	protected $debug = FALSE;

	protected $maintenance = '';

	protected $loaderPaths = [];

	protected $configs = [];

	protected $defaultTimezone = 'Europe/Prague';

	protected $isDebugger;

	protected $errorPresenter;

	/**
	 * @param $appDir
	 * @param $rootDir
	 */
	public function __construct($appDir, $rootDir) {
		$this->appDir = $appDir;
		$this->rootDir = $rootDir;
	}

	/**
	 * @param $ip
	 * @return $this
	 */
	public function addDebugger($ip) {
		$this->debuggers[] = $ip;
		return $this;
	}

	/**
	 * @param $email
	 * @return $this
	 */
	public function addDebuggerEmail($email) {
		$this->debugEmails[] = $email;
		return $this;
	}

	public function addConfig($config) {
		$this->configs[] = $config;
		return $this;
	}

	/**
	 * @param $ips
	 * @param $emails
	 * @return $this
	 */
	public function setDebuggers($ips, $emails) {
		foreach($ips as $ip) {
			$this->addDebugger($ip);
		}
		foreach($emails as $email) {
			$this->addDebuggerEmail($email);
		}
		return $this;
	}

	/**
	 * Enables @inject annotations and inject* methods in services and factories, it's kinda required
	 * for components and honestly I think there's nothing wrong with inject methods in abstract classes.
	 *
	 * @return $this
	 */
	public function enableInject() {
		$this->enableInject = TRUE;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function enableDebugger() {
		$this->debug = TRUE;
		return $this;
	}

	/**
	 * 'die's this message when web is in debug mode and visitor isn't a debugger
	 *
	 * @param $maintenance
	 * @return $this
	 */
	public function setMaintenanceMessage($maintenance) {
		$this->maintenance = $maintenance;
		return $this;
	}

	/**
	 * @param $path
	 * @return $this
	 */
	public function addLoaderPath($path) {
		$this->loaderPaths[] = $path;
		return $this;
	}

	/**
	 * @param $timezone
	 * @return $this
	 */
	public function setDefaultTimezone($timezone) {
		$this->defaultTimezone = $timezone;
		return $this;
	}

	/**
	 * @param $errorPresenter
	 * @return $this
	 */
	public function setErrorPresenter($errorPresenter) {
		$this->errorPresenter = $errorPresenter;
		return $this;
	}

	/**
	 * @return bool
	 */
	protected function isDebugger() {
		if($this->isDebugger === NULL) {
			return $this->isDebugger = isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $this->debuggers);
		} else {
			return $this->isDebugger;
		}
	}

	public function setUp() {
		date_default_timezone_set($this->defaultTimezone);

		if($this->debug && !$this->isDebugger()) {
			die($this->maintenance);
		}

		$configurator = new Nette\Configurator;

		// Fix incorrectly initialized appDir
		$configurator->addParameters(['appDir' => $this->appDir]);

		// Add root dir
		$configurator->addParameters(['rootDir' => $this->rootDir]);

		$configurator->setTempDirectory($this->appDir . '/temp');

		if($this->debug && $this->isDebugger()) {
			$configurator->setDebugMode($this->debuggers);
		} else {
			$configurator->setDebugMode(FALSE);
		}

		$configurator->enableDebugger($this->appDir . '/temp/log', $this->debugEmails);

		// Load app
		$loader = $configurator->createRobotLoader()
			->addDirectory($this->appDir . '/model')
			->addDirectory($this->appDir . '/components')
			->addDirectory($this->appDir . '/modules')
			->addDirectory($this->appDir . '/routers')
			->addDirectory(__DIR__ . '/../');
		// Load additional paths specified in index.php
		foreach($this->loaderPaths as $path) {
			$loader->addDirectory($path);
		}
		$loader->register();

		// load neon files from all config folders within the app
		foreach(Nette\Utils\Finder::findFiles('config/*.neon')->from($this->appDir) as $config) {
			$configurator->addConfig($config->getRealPath());
		}
		foreach($this->configs as $config) {
			$configurator->addConfig($config);
		}



		// enable inject annotations and methods
		if($this->enableInject) {
			$configurator->onCompile[] = function($configurator, $compiler) {
				$compiler->addExtension('InjectExtension', new Zax\DI\CompilerExtensions\InjectExtension);
			};
		}

		/** @var Nette\DI\Container $container */
		$container = $configurator->createContainer();

		//$container->parameters['appDir'] = $this->appDir;

		// Default messages for custom validators in forms
		if(isset($container->parameters['zax.formsMessages'])) {
			foreach($container->parameters['zax.formsMessages'] as $validator => $message) {
				Nette\Forms\Rules::$defaultMessages[$validator] = $message;
			}
		}

		return $container;
	}

	/**
	 * Runs the application
	 */
	public function boot() {

		$container = $this->setUp();

		$app = $container->application;
		if($this->debug && $this->isDebugger()) {
			$app->catchExceptions = FALSE;
		} else {
			$app->catchExceptions = TRUE;
		}

		if($this->errorPresenter !== NULL) {
			$app->errorPresenter = $this->errorPresenter;
		}

		$app->run();
	}


}