<?php

namespace ZaxCMS\Components\Auth;
use Nette,
    Zax,
	ZaxCMS,
    ZaxCMS\Model,
    Nette\Forms\Form,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control,
    Zax\Application\UI\FormControl;

class LoginFormControl extends FormControl {

	public $onLogin = [];

	public $onError = [];

	protected $groupLoginPasswordErrors = FALSE;

	protected $formStyle = 'form-horizontal';

	protected $placeholdersOnly = FALSE;

	protected $authenticator;

	protected $loginFacade;

	public function __construct(Model\LoginFacade $loginFacade) {
		$this->loginFacade = $loginFacade;
	}

	public function groupLoginPasswordErrors() {
		$this->groupLoginPasswordErrors = TRUE;
		return $this;
	}

	public function setFormStyle($style = 'form-horizontal') {
		$this->formStyle = $style;
		return $this;
	}

	public function showPlaceholdersOnly() {
		$this->placeholdersOnly = TRUE;
		return $this;
	}

    public function viewDefault() {}
    
    public function beforeRender() {
    }

	protected function formSolvePlaceholderAdd($type, Nette\Forms\Form $form, $name, $label = NULL) {
		$type = ucfirst($type);
		$control = call_user_func_array([$form, 'add' . $type], [$name, $this->placeholdersOnly ? NULL : $label]);
		if($this->placeholdersOnly) {
			$control->getControlPrototype()->placeholder($label);
		}
		return $control;
	}
    
    public function createForm() {
        $f = parent::createForm();

	    $po = $this->placeholdersOnly;

	    $loginLabel = 'auth.form.' .
		    ($this->loginFacade->getLoginType() === Model\LoginFacade::LOGIN_BY_EMAIL ? 'email' : 'username');

	    $this->formSolvePlaceholderAdd('text', $f, 'login', $loginLabel);
	    $this->formSolvePlaceholderAdd('password', $f, 'password', 'auth.form.password');
	    $f->addCheckbox('remember', 'auth.form.remember');
	    $f->addButtonSubmit('helloUser', 'auth.button.login', 'user');

	    $f->addProtection();

		$f->enableBootstrap(['success' => ['helloUser']], FALSE, 3, 'sm', $this->formStyle);

	    return $f;
    }
    
    public function formSuccess(Form $form, $values) {
	    $t = $this->getTranslator();
        try {
	        $this->user->login($values->login, $values->password);
        } catch (ZaxCMS\Security\UserLoginDisabledException $ex) {
	        $form->addError($t->translate('auth.error.loginDisabled'));
        } catch (ZaxCMS\Security\InvalidNameException $ex) {
	        if(!$this->groupLoginPasswordErrors) {
				$form['login']->addError($t->translate('auth.error.invalidName'));
	        }
        } catch (ZaxCMS\Security\InvalidEmailException $ex) {
	        if(!$this->groupLoginPasswordErrors) {
				$form['login']->addError($t->translate('auth.error.invalidEmail'));
	        }
        } catch (ZaxCMS\Security\InvalidPasswordException $ex) {
	        if(!$this->groupLoginPasswordErrors) {
				$form['password']->addError($t->translate('auth.error.invalidPassword'));
	        }
        } catch (ZaxCMS\Security\InvalidCredentialsException $ex) {
	        if(!$this->groupLoginPasswordErrors) {
		        $form->addError($t->translate('auth.error.invalidCredentials'));
	        }
        } catch (ZaxCMS\Security\UnverifiedUserException $ex) {
			$form->addError($t->translate('auth.error.unverifiedUser'));
        } catch (ZaxCMS\Security\BannedUserException $ex) {
			$form->addError($t->translate('auth.error.bannedUser'));
        } catch (ZaxCMS\Security\AuthenticationException $ex) {
	        $this->onError($ex);
        }
    }
    
    public function formError(Form $form) {
        
    }

}