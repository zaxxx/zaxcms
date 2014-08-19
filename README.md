zaxcms
======

**Unstable and under heavy development** - do not use for important stuff!

This is my first attempt at building a CMS on top of Nette framework. This is definitely nowhere near finished, alot of stuff is done in very dirty ways and I'm fully aware of that, and many other stuff is simply being hardcoded for my needs.

I want a CMS which is easy for me to understand, fun to build apps on top of and simply has most of the common stuff figured out. I also want something where admin can just log in, browse his website and change stuff "inline", without having to open any admin panel. Mostly such actions will be represented with a small pencil icon, floating in top-right corner of the component it belongs to. This should lead to cleaner admin panel and better workflow for the user.

## It uses:
- [Nette framework](https://github.com/nette/nette)
- [Doctrine 2](https://github.com/doctrine/doctrine2) (with [Kdyby/Doctrine](https://github.com/Kdyby/Doctrine), [Gedmo](https://github.com/l3pp4rd/DoctrineExtensions) and [rixxi/gedmo](https://github.com/rixxi/gedmo))
- [Symfony translation](https://github.com/symfony/Translation) (with [Kdyby/Translation](https://github.com/Kdyby/Translation))
- [Texy](https://github.com/dg/texy)
- [jQuery](https://github.com/jquery/jquery)
- [Twitter Bootstrap](https://github.com/twbs/bootstrap) and [Filestyle](https://github.com/markusslima/bootstrap-filestyle)

## Task list:
(this list will grow)

Security layer:
- [x] Nette-based ACL
 - [x] Latte macro `{secured Resource, Privilege}`
 - [x] Annotation `@secured Resource, Privilege`
- [ ] Identity-based permissions

Custom abstract component:
- [x] Views support
- [x] Better AJAX support
- [x] Sending 'anchor' and 'focus' in payload
- [x] Factory for translated forms
- [x] Delegating flash messages to presenter
- [x] Cleaner snippet IDs

Translations
- [x] TranslatedNestedTree
- [ ] (might need refactor)

Services for injecting appDir and rootDir
- [x] `__toString()`

PathHelpers
- [ ] (need rewrite)

Custom bootstrap
- [x] Simple API to easily setup common stuff

JS and CSS combining (and optionally minifying)
- [x] Cache
- [x] Base features
- [ ] LESS support...
- [ ] (might need refactor)

Enhanced forms
- [x] Bootstrap rendering
- [x] Better AJAX support
- [x] Very ~~simple~~ lame form <--> entity binding
- [ ] (need refactor custom inputs into extension)
- [ ] Custom form inputs
 - [x] Abstract input empowered by `UI\Control`
 - [x] Static input
 - [x] Button submit
 - [x] Link submit
 - [x] NEON textarea
 - [x] Datetime input
 - [ ] ...

FileManager component
- [x] Allow browsing only within specified folder
- [x] Possible to specify enabled features (createDir, renameDir, deleteDir, truncateDir, uploadFile, renameFile, renameExtension, deleteFile)
- [x] Possible to add custom messages to upload form
- [x] Possible to specify allowed mime type(s) and/or extension(s) for uploading
- [x] Possible to generate URL for a file
- [x] Possible to show space usage
- [x] Ajax

WebContent component
- [x] Texy support
- [x] Localization
- [x] Embedded file manager
- [x] Preview
- [x] Cache
- [x] Ajax (when editing)

Menu component
- [x] Renderable menu
- [x] GUI for customizing menu
- [x] Detect URL within the app and convert to Nette format
- [x] Ajax (when editing)
- [x] Cache
- [ ] Secured menu items
- [ ] Submenus

Installer module
- [x] Asking for database login and saving to neon
- [x] Installing database
- [x] Registering admin user

... (tons of stuff)

Backend and administration
- [ ] Custom pages
 - [x] Embedded WebContent component
 - [x] Ajax
 - [ ] Title
 - [ ] Localization
 - [ ] Secured pages
- [ ] User settings
- [ ] Security settings
- [ ] ...
