<?php

namespace ZaxCMS\Routers;
use Zax,
    Zax\Application\Routers\Route,
	Zax\Application\Routers\RouteList,
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
	    $ls = '[<locale=' . $this->defaultLocale . ' ' . implode('|', $this->availableLocales) . '>/]';

        $r = new RouteList;
	    $r[] = new Route($ls . 'page/<page>', [
		    'module' => 'Front',
		    'presenter' => 'Page',
		    'action' => 'default'
	    ]);

	    $r[] = new Route($ls . 'articles[/page-<p>]', Route::meta('Front:Articles', 'default', [
		    'p' => 'articles-paginator-page'
	    ]));

        $r[] = new Route($ls . '[<module=Front>][/<presenter>[/<action>]]', [
	        'presenter' => 'Default',
	        'action' => 'default'
        ]);
        return $r;
    }
}
