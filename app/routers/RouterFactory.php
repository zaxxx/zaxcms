<?php

namespace ZaxCMS\Routers;
use Zax,
    Zax\Application\Routers\Route,
	Kdyby,
    Nette;


/**


$r[] = new Route('[<action>][/pohled-<pohled>][/<detailne>]',

    Route::meta('Web:Front:Default', NULL ,[
        'pohled' => 'testControl-view',
        'detailne' => 'testControl-detailni'
    ], ['detailne'])
);


 */
class RouterFactory extends Nette\Object {

	protected $availableLocales = [];

	protected $defaultLocale = 'cs_CZ';

	public function __construct(Kdyby\Translation\Translator $translator) {
		$this->availableLocales = $translator->getAvailableLocales();
	}

    /** @return Nette\Application\Routers\RouteList */
    public function create() {
        $r = new Nette\Application\Routers\RouteList;
        $r[] = new Route('[<locale=' . $this->defaultLocale . ' ' . implode('|', $this->availableLocales) . '>/][<module=Front>]', [
	        'presenter' => 'Default',
	        'action' => 'default'
        ]);
        return $r;
    }
}
