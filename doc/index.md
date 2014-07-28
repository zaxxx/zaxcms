ZaxCMS is CMSFW built on top of some popular frameworks and libraries, which include:
- [Nette framework](https://github.com/nette/nette)
- [Doctrine 2](https://github.com/doctrine/doctrine2) (with [Kdyby/Doctrine](https://github.com/Kdyby/Doctrine), [Gedmo](https://github.com/l3pp4rd/DoctrineExtensions) and [rixxi/gedmo](https://github.com/rixxi/gedmo))
- [Symfony translation](https://github.com/symfony/Translation) (with [Kdyby/Translation](https://github.com/Kdyby/Translation))
- [Texy](https://github.com/dg/texy)
- [jQuery](https://github.com/jquery/jquery)
- [Twitter Bootstrap](https://github.com/twbs/bootstrap) and [Filestyle](https://github.com/markusslima/bootstrap-filestyle)

Getting started
===============

**Keep in mind that this thing is unstable and under heavy development.**

Installing jQuery, Bootstrap...
-------------------------------

The fastest way is to put Bootstrap css into '/pub/libs/css' directory
and JS libraries (jquery.js, bootstrap.js, nette.ajax.js, nette.forms.js, bootstrap.filestyle.js) into
'/pub/libs/js' directory. These files will be automatically found by Nette Finder (in alphabetical order), combined into 2 files (css and js), cached
and included in layout. Actual implementation is in [BasePresenter](https://github.com/zaxxx/zaxcms/blob/master/app/modules/BasePresenter.php).

Custom CSS and JS
-----------------

The same rules apply, but change 'libs' for 'default'. Or any name, thanks to UI\Multiplier. Don't forget to add it in your [@layout](https://github.com/zaxxx/zaxcms/blob/master/app/modules/%40layout.latte).

Installing the CMS
------------------

Let's assume that the base URL is http://zaxcms. Go to http://zaxcms/install and follow the installer. It should carry you through setting up the database
and registering you as admin. **Don't forget to delete '/app/modules/Install' directory on production.** *(but you shouldn't be using this on production atm anyway)*

When installed, you should see an empty page with menu bar and a bunch of pencil icons.

Framework behavior
==================

There are certain things that need to be kept in mind:
----------------------------------------------------------------------

@inject annotations and inject* methods are ON for all services and factories.
I use inject* methods to prevent constructor hell.
**Do not use @inject annotations, though! They're bad, mmmkay!**

Config autoloader is ON by default -
this thing uses [Finder](https://github.com/nette/finder) and
**recursively** searches for any *.neon files within 'config' directories
inside application dir. This way I can have separated configs for just
about anything, but I mainly use it for components. You can turn this off
in index.php, but you'll get only annoyed by the amount of config files
you have to specify manually ;-)

Components
==========

Components are abit "hacked". They use views (done simply by persistent parameter $view) and
working with AJAX is quite different.

Simple component
----------------

All components in ZaxCMS extend class 'Zax\Application\UI\Control'.

This is the simplest example of a component:

```php
class SomeControl extends Zax\Application\UI\Control {

	public function beforeRender() {
		// gets called every time
	}
	
	public function viewDefault() {
		// gets called only if view is "Default"
	}
	
}
```

You also need to add a template. Just create 'Default.latte' and put it in 'templates'
directory. The structure should look like this:
- SomeControl.php
- ISomeControlFactory.php
- /templates
	- Default.latte

Views
-----

Views are defined by view<View> methods, if such method doesn't exist, an exception will be thrown
when trying to access that view. Default view is 'Default'. Every view has a template in 'templates' directory.

Renders
-------

Now, you might probably want to use stuff like {control someControl:foo}. Well, it's easy. Just add a 'beforeRenderFoo' method and it should work.
Again, you also need to add a template, this time called 'Default.Foo.latte'.

*The pattern for naming templates is '<View>.latte' or '<View>.<Render>.latte'.*

AJAX
----

AJAX works magically here. I admit I'm not quite entirely sure *how* it works, but it just does.

Components have enableAjax() method. Just call it. It will instantly enable AJAX on the component
and all subcomponents, *including forms*. Note that you need to enable AJAX on links in templates
by yourself, just check $ajaxEnabled variable in your template.

If you want to enable AJAX on a component, but want to exclude some sub-components, use
disableAjaxFor() method, which takes array of sub-component names.

I almost forgot - you need to wrap all content in your component templates by a snippet, preferably without a name.

$this->go()
-----------

This method is kind of a replacement for $this->redirect(), but it saves you from writing 'if($this->presenter->isAjax())' all the time. It also
ensures that AJAX request or not, you'll always end up on the same destination.

$this->flashMessage() behavior
------------------------------

If you call flashMessage() on a component, it will just get delegated to the presenter.

Snippet ID format
------------------

I don't like Nette's default 'snippet-someComponent-subComponent-', I like 'someComponent-subComponent' better and I can't think of any downsides of using it.

Secured components
------------------

All secured components in ZaxCMS extend class 'Zax\Application\UI\SecuredControl'.

Security is important, but also quite hard to grasp in Nette. It's not nice to repeat {if $user->isAllowed(...)} in templates, but at the same time I can't quite come
up with much better solution. Also you should secure your component factories to prevent them from being created when user isn't allowed to. That's alot of annoying work,
that I want to get rid of (as much as possible).

I think annotations are very nice to use, so currently, all signal, view, beforeRender and createComponent<Name> methods in components can have this simple annotation:

```php
/** @secured Resource, Privilege **/
```

And if the user isn't allowed to do it, an exception will be thrown.

For comfort in templates, an exception will not be thrown in createComponent<Name> methods and instead an empty component will be returned.

I also added one simple macro for checking security in templates:

```
{secured Resource, Privilege}
	hidden stuff
{else}
	login please
{/secured}
```

You can also use it as n:macro:

```
<a n:secured="Resource, Privilege" n:href="Some:Hidden:action">go somewhere</a>
```

Forms
=====

The only "correct" way to make forms in ZaxCMS is to extend 'Zax\Application\UI\FormControl'. Don't forget
to give it a factory, since it's a regular component, and a 'Default.latte' template. To render the form, just
use {control form}.

The class has two abstract methods that need to be defined:

- formError(Form $form);
- formSuccess(Form $form, $values);

And the actual form should be defined in 'createForm' method:

```php
public function createForm() {
	$form = parent::createForm();
	$form->addText('name');
	//...
	return $form;
}
```

*Tip: FormControl extends SecuredControl, so feel free to use security features.*

Latte
=====

There are few helpers that you can use in components:

- {$month|beautifulMonth} where $month is 1-12  (outputs 'january')
- {$day|beautifulDayOfWeek} where $day is 1-7 (outputs 'sunday')
- {$day|shortDayOfWeek} where $day is 1-7 (outputs 'tue')
- {$dateTime|beautifulDate} (outputs 'sunday 2. january 2000')
- {$dateTime|beautifulTime} (outputs '03:04')
- {$dateTime|beautifulDateTime} (outputs 'sunday 2. january 2000 - 03:04')
- {$dateTime|relativeDate} (outputs '5 days ago')




















