<?php

namespace Zax\DI\CompilerExtensions;
use Nette,
	Nette\DI,
	Nette\DI\CompilerExtension;

final class LessExtension extends CompilerExtension {

	public $defaults = [
		'files' => [],
		'urlRoot' => 'http://localhost',
		'options' => [
			'compress' => TRUE
		],
		'variables' => [],

	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$less = $builder->addDefinition($this->prefix('parser'))
			->setClass('Less_Parser', [$config['options']]);
		foreach($config['files'] as $file) {
			$less->addSetup('parseFile', [$file, $config['urlRoot']]);
		}
		if(!empty($variables)) {
			$less->addSetup('modifyVars', $config['variables']);
		}

		$lessCache = $builder->addDefinition($this->prefix('cache'))
			->setClass('Less_Cache');

	}

	public function beforeCompile() {
		$builder = $this->getContainerBuilder();
	}

}