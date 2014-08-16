<?php

namespace Zax\Application\Routers;
use Zax,
	Nette;

class RouteList extends Nette\Application\Routers\RouteList {

	protected $cachedMetadata = [];

	public function urlToMetadata(Nette\Http\UrlScript $url) {
		$abs = $url->absoluteUrl;
		if(isset($this->cachedMetadata[$abs])) {
			return $this->cachedMetadata[$abs];
		}

		$meta = [];
		$request = $this->match(new Nette\Http\Request($url));
		if($request) {
			$params = $request->getParameters();
			$meta['presenter'] = $request->presenterName;
			return $this->cachedMetadata[$abs] = array_merge($meta, $params);
		}
		return NULL;
	}

	public function urlToNHref(Nette\Http\UrlScript $url) {
		$meta = $this->urlToMetadata($url);
		return ':' . $meta['presenter'] . ':' . $meta['action'];
	}

	public function urlToParams(Nette\Http\UrlScript $url, $ignoreKeys = []) {
		$meta = $this->urlToMetadata($url);
		unset($meta['presenter'], $meta['action']);
		foreach($ignoreKeys as $ignoreKey) {
			unset($meta[$ignoreKey]);
		}
		return $meta;
	}

}
