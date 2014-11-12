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

	protected $tempDir;

	protected $debuggers = [];

	protected $debugEmails = [];

	protected $debug = FALSE;

	protected $maintenance = '';

	protected $loaderPaths = [];

	protected $configs = [];

	protected $autoloadConfig = FALSE;

	protected $defaultTimezone = 'Europe/Prague';

	protected $isDebugger;

	protected $errorPresenter;

	protected $enableLog = TRUE;

	protected $catchExceptions = TRUE;

	protected $errorPresenters;

	/**
	 * @param $appDir
	 * @param $rootDir
	 * @param $tempDir
	 */
	public function __construct($appDir, $rootDir, $tempDir) {
		$this->appDir = $appDir;
		$this->rootDir = $rootDir;
		$this->tempDir = $tempDir;
	}

	/** Debugger by IP
	 *
	 * @param $ip
	 * @return $this
	 */
	public function addDebugger($ip) {
		$this->debuggers[] = $ip;
		return $this;
	}

	/** Debugger by e-mail
	 *
	 * @param $email
	 * @return $this
	 */
	public function addDebuggerEmail($email) {
		$this->debugEmails[] = $email;
		return $this;
	}

	/** Add a .neon config file
	 *
	 * @param $config
	 * @return $this
	 */
	public function addConfig($config) {
		$this->configs[] = $config;
		return $this;
	}

	/** Autoload all 'config/*.neon' recursively in application dir
	 * Note that the configs will be loaded in alphabetical order
	 *
	 * @param bool $autoload
	 * @return $this
	 */
	public function enableConfigAutoload($autoload = TRUE) {
		$this->autoloadConfig = $autoload;
		return $this;
	}

	/** Debuggers by IP and e-mail
	 *
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
	 * @param bool $enableTracy
	 * @param bool $catchExceptions
	 * @param bool $enableLog - log errors and send e-mails
	 * @return $this
	 */
	public function enableDebugger($enableTracy = TRUE, $catchExceptions = FALSE, $enableLog = TRUE) {
		$this->debug = $enableTracy;
		$this->catchExceptions = $catchExceptions;
		$this->enableLog = $enableLog;
		return $this;
	}

	/**
	 * 'die's this message when web is in debug mode and visitor isn't a debugger
	 * TODO: better solution
	 *
	 * @param $maintenance
	 * @return $this
	 */
	public function setMaintenanceMessage($maintenance) {
		$this->maintenance = $maintenance;
		return $this;
	}

	/** Add a path for RobotLoader to sniff in
	 *
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

	public function setErrorPresenters($presenter, $control) {
		$this->errorPresenters = [
			'presenter' => $presenter,
			'control' => $control
		];
		return $this;
	}

	/** Does IP match any debugger's IP?
	 *
	 * @return bool
	 */
	protected function isDebugger() {
		if($this->isDebugger === NULL) {
			return $this->isDebugger = isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $this->debuggers);
		} else {
			return $this->isDebugger;
		}
	}

	protected function loadConfigFiles(Nette\Configurator $configurator) {
		// load neon files from all config folders within the app
		if($this->autoloadConfig) {
			$cacheJournal = new Nette\Caching\Storages\FileJournal($this->tempDir);
			$cacheStorage = new Nette\Caching\Storages\FileStorage($this->tempDir . '/cache', $cacheJournal);
			$cache = new Nette\Caching\Cache($cacheStorage, 'configFiles');
			$files = $cache->load('configFiles');
			if($files === NULL) {
				$files = [
					0 => []
				];
				foreach(Nette\Utils\Finder::findFiles('config/*.neon')->from($this->appDir, $this->rootDir . '/zax') as $path => $file) {

					// check neon file contents for priority
					$content = Nette\Neon\Neon::decode(file_get_contents($path));
					if(isset($content['priority']) && isset($content['priority'][0])) {
						$priority = $content['priority'][0];
						if(!isset($files[$priority])) {
							$files[$priority] = [];
						}
						$files[$priority][] = $path;
					} else {
						$files[0][] = $path;
					}
				}
				$cache->save('configFiles', $files);
			}
			foreach($files as $priorityFiles) {
				foreach($priorityFiles as $config) {
					$configurator->addConfig($config);
				}
			}
		}
		foreach($this->configs as $config) {
			$configurator->addConfig($config);
		}
	}

	/** Create and setup Nette\Configurator
	 *
	 * @return Nette\Configurator
	 */
	protected function setUpConfigurator() {
		$configurator = new Nette\Configurator;

		// Fix incorrectly initialized appDir
		$configurator->addParameters(['appDir' => $this->appDir]);

		// Add root dir
		$configurator->addParameters(['rootDir' => $this->rootDir]);

		// Add temp dir
		$configurator->addParameters(['tempDir' => $this->tempDir]);

		$configurator->setTempDirectory($this->tempDir);

		if($this->debug && $this->isDebugger()) {
			$configurator->setDebugMode($this->debuggers);
		} else {
			$configurator->setDebugMode(FALSE);
		}

		if($this->enableLog) {
			$configurator->enableDebugger($this->tempDir . '/log', $this->debugEmails);
		}

		// Load app
		$loader = $configurator->createRobotLoader()
			->addDirectory($this->appDir)       // app dir
			->addDirectory(__DIR__ . '/../');   // zax dir

		// Load additional paths specified in index.php
		foreach($this->loaderPaths as $path) {
			$loader->addDirectory($path);
		}
		$loader->register();

		$this->loadConfigFiles($configurator);

		return $configurator;
	}

	/** Set up the environment and return DI container
	 *
	 * @return Nette\DI\Container
	 */
	public function setUp() {
		date_default_timezone_set($this->defaultTimezone);

		if($this->debug && !$this->isDebugger()) {
			die($this->maintenance);
		}

		$configurator = $this->setUpConfigurator();

		/** @var Nette\DI\Container $container */
		$container = $configurator->createContainer();

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

		$app = $container->getByType('Zax\Application\Application');

		$app->catchExceptions = $this->catchExceptions;

		if($this->errorPresenter !== NULL) {
			$app->errorPresenter = $this->errorPresenter;
		} else if($this->errorPresenters !== NULL) {
			$app->errorPresenters = $this->errorPresenters;
		}

		$app->run();
	}


}