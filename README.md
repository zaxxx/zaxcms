zaxcms
======

**Unstable and under heavy development** - do not use for important stuff!

## What is this?

This is my first attempt at building a CMS on top of Nette framework. This is still nowhere near finished, but it already has some pretty nice features.

I want a CMS which is easy for me to understand, fun to build apps on top of and simply has most of the common stuff figured out. I also want something where admin can just log in, browse his website and change stuff "inline", without having to open any admin panel. Mostly such actions will be represented with a small pencil icon, floating in top-right corner of the component it belongs to. This should lead to cleaner admin panel and better workflow for the user.

## Goals:

- CMS should be secured
- CMS should provide a solid base for creating dynamic websites (all dynamic websites have admin panel, login, ACL, some navigations...)
- CMS should serve well for managing multilanguage websites
- CMS should be as easy to use (and understand) as possible, including the installer
- CMS should be easily extensible and customizable
- CMS should work well without JavaScript (excluding the installer)

## Current weaknesses:

- Code is abit dirty
- Almost no documentation
- No licence (not yet sure that I can handle the responsibility of people actually using this :-))
- Tests are covering only core functionality
- Translations are abit buggy
- I suck at JavaScript :-(

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
- [x] TranslatedNestedTree (Gedmo kinda sucks though, might give KNP a try)
- [x] General component for choosing locale

Services for injecting appDir and rootDir
- [x] `__toString()`

PathHelpers

Latte
- [x] Custom template factory
- [x] `{icon}` macro and IIcon service to reduce the dependency on Glyphicons
- [x] Few helpers for outputting time in human-readable format

Custom bootstrap
- [x] Simple API to easily setup common stuff

JS and CSS combining (and optionally minifying)
- [x] Cache
- [x] Base features

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
 - [x] TexyArea input (buttons around textarea)
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
- [x] Submenus
- [ ] Secured menu items

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
 - [x] Roles
 - [ ] ACL
- [ ] ...
