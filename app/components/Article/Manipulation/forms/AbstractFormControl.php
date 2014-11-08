<?php

namespace ZaxCMS\Components\Article;
use Nette,
	Zax,
	ZaxCMS\Model,
	Nette\Forms\Form,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI\Control,
	Zax\Application\UI\FormControl;

abstract class AbstractFormControl extends FormControl {

	use Zax\Utils\TInjectRootDir;

	protected function createImageUpload(Nette\Forms\Container $container, $image) {
		$id = $this->lookupPath('Nette\Application\UI\Presenter') . '-form';

		if($image !== NULL) {
			$container->addCheckbox('deleteImg', 'common.form.removeImage')
				->addCondition(Form::EQUAL, FALSE)
				->toggle($id . '-pic-customize');
		}

		$container->addFileUpload('img', 'common.form.image')
			->setOption('id', $id . '-pic-image')
			->addCondition(Form::FILLED)
			->addRule(Form::IMAGE);

		// upload is filled => show img options
		$container['img']
			->addCondition(Form::FILLED)
			->toggle($id . '-pic-customize');

		// has image and deleteImg is checked => show upload
		if($image !== NULL) {
			$container['deleteImg']
				->addCondition(Form::EQUAL, TRUE)
				->toggle($id . '-pic-image');
		}
	}

	protected function deleteImage($path) {
		$file = $this->rootDir . DIRECTORY_SEPARATOR . $path;
		if(file_exists($file))
			Nette\Utils\FileSystem::delete($file);
	}

	protected function processImageUpload(Nette\Http\FileUpload $upload, $targetDir, $id) {
		$dir = 'upload' . DIRECTORY_SEPARATOR . $targetDir . DIRECTORY_SEPARATOR . $id;
		if(!file_exists($this->rootDir . DIRECTORY_SEPARATOR . $dir)) {
			Nette\Utils\FileSystem::createDir($this->rootDir . DIRECTORY_SEPARATOR . $dir);
		}

		$path = $dir . DIRECTORY_SEPARATOR . $upload->getSanitizedName();
		$upload->move($this->rootDir . DIRECTORY_SEPARATOR . $path);
		return $path;
	}

}