<?php

namespace ZaxCMS\Model\CMS\Entity;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Kdyby\Doctrine\Entities\BaseEntity,
	Doctrine,
	Gedmo\Translatable\Translatable,
	Gedmo\Mapping\Annotation as Gedmo,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @property-read int $id
 * @property string $name
 * @property-read string $icon
 */
class Privilege extends BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=63, unique=TRUE)
	 */
	protected $name;

	public function getIcon() {
		$icons = [
			'Add' => 'plus',
			'Edit' => 'pencil',
			'Delete' => 'trash',
			'Use' => 'user',
			'Upload' => 'upload',
			'Publish' => 'globe',
			'Ban' => 'ban-circle',
			'Secure' => 'lock'
		];
		if(!in_array($this->name, array_keys($icons))) {
			return 'question-sign';
		}
		return $icons[$this->name];
	}

}