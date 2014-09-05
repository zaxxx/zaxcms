<?php

namespace ZaxCMS\InstallModule\Components\Install;
use Nette,
    Zax,
	Kdyby,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class SetDatabaseControl extends FormControl {

	protected $appDir;

	public function __construct(Zax\Utils\AppDir $appDir) {
		$this->appDir = $appDir;
	}

    public function viewDefault() {}
    
    public function beforeRender() {}

	protected function loadConfig() {
		return Nette\Neon\Neon::decode(file_get_contents($this->appDir . '/config/connection.neon'));
	}

	protected function saveConfig($config) {
		return file_put_contents($this->appDir . '/config/connection.neon', Nette\Neon\Neon::encode($config, Nette\Neon\Encoder::BLOCK));
	}

    public function createForm() {
        $f = parent::createForm();

	    $config = $this->loadConfig();

	    $f->addText('host', 'system.form.dbHost');
	    $f->addText('user', 'system.form.dbUser');
	    $f->addText('password', 'system.form.dbPassword');
	    $f->addText('database', 'system.form.dbDatabase');

	    if(isset($config['parameters'])) {
		    $binder = new Zax\Forms\ArrayBinder;
		    $binder->entityToForm($config['parameters'], $f);
	    }

	    $f->addButtonSubmit('test', 'system.button.testConnection', 'refresh');

	    $f->enableBootstrap(['primary' => ['test']]);

	    $f->autofocus('host');

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
        $config = [
	        'parameters' => [
		        'host' => '',
		        'user' => '',
		        'password' => '',
		        'database' => ''
            ]
        ];
	    $binder = new Zax\Forms\ArrayBinder;
	    $config['parameters'] = $binder->formToEntity($form, $config['parameters']);

	    $this->saveConfig($config);

	    $this->redirect('this');
    }
    
    public function formError(Form $form) {
        
    }

}