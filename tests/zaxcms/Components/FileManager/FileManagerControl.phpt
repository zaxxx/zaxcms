<?php

namespace ZaxCMS\Tests\Components\FileManager;
use Tester,
	Tester\Assert,
	Nette,
	Zax,
	ZaxCMS;

$container = require __DIR__ . '/../../../bootstrap.php';

class FileManagerControlTest extends Tester\TestCase {

	/** @var Nette\DI\Container */
	private $container;

	/** @var ZaxCMS\Components\FileManager\IFileManagerFactory */
	private $fileManagerFactory;

	private $testsDir;

	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
		$this->fileManagerFactory = $container->getByType('ZaxCMS\Components\FileManager\IFileManagerFactory');
		$this->testsDir = realpath($container->getByType('Zax\Utils\RootDir') . '/tests');
	}

	public function testFileManagerControl() {
		$fileManager = $this->fileManagerFactory->create();

		Assert::exception(function() use ($fileManager) {$fileManager->getRoot();}, 'ZaxCMS\Components\FileManager\RootNotSetException');

		$fileManager->setRoot($this->testsDir);

		Assert::same($this->testsDir, $fileManager->getRoot());
		Assert::same('', $fileManager->getDirectory());
		Assert::same($this->testsDir, $fileManager->getAbsoluteDirectory());

		$fileManager->setDirectory('/config');
		Assert::same($this->testsDir . '/config', $fileManager->getAbsoluteDirectory());
		Assert::same('/config', $fileManager->getDirectory());

		Assert::same($fileManager, $fileManager->getFileManager());
		Assert::type('ZaxCMS\Components\FileManager\DirectoryListControl', $fileManager->getDirectoryList());
		Assert::type('ZaxCMS\Components\FileManager\FileListControl', $fileManager->getFileList());

		Assert::exception(function() use ($fileManager) {$fileManager->setDirectory('/..')->getAbsoluteDirectory();}, 'ZaxCMS\Components\FileManager\InvalidPathException');
	}

	public function testDirectoryListControl() {
		$fileManager = $this->fileManagerFactory->create()
			->setRoot($this->testsDir);

		$directoryList = $fileManager->getDirectoryList();
		Assert::same($this->testsDir, $directoryList->getAbsoluteDirectory());

		$fileManager->setDirectory('/config');
		Assert::same($this->testsDir . '/config', $directoryList->getAbsoluteDirectory());

		Assert::same($fileManager, $directoryList->getFileManager());

		Assert::exception(function() use($directoryList) {$directoryList->getCreateDir();}, 'Nette\UnexpectedValueException');
		$fileManager->enableCreateDir();
		Assert::type('ZaxCMS\Components\FileManager\CreateDirControl', $directoryList->getCreateDir());

		Assert::exception(function() use($directoryList) {$directoryList->getDeleteDir();}, 'Nette\UnexpectedValueException');
		$fileManager->enableDeleteDir();
		Assert::type('ZaxCMS\Components\FileManager\DeleteDirControl', $directoryList->getDeleteDir());

		Assert::exception(function() use($directoryList) {$directoryList->getRenameDir();}, 'Nette\UnexpectedValueException');
		$fileManager->enableRenameDir();
		Assert::type('ZaxCMS\Components\FileManager\RenameDirControl', $directoryList->getRenameDir());
	}

	public function testFileListControl() {
		$fileManager = $this->fileManagerFactory->create()
			->setRoot($this->testsDir);

		$fileList = $fileManager->getFileList();
		Assert::same($this->testsDir, $fileList->getAbsoluteDirectory());

		$fileManager->setDirectory('/config');
		Assert::same($this->testsDir . '/config', $fileList->getAbsoluteDirectory());

		Assert::same($fileManager, $fileList->getFileManager());

		Assert::exception(function() use($fileList) {$fileList->getRenameFile();}, 'Nette\UnexpectedValueException');
		$fileManager->enableRenameFile();
		Assert::type('ZaxCMS\Components\FileManager\RenameFileControl', $fileList->getRenameFile());

		Assert::exception(function() use($fileList) {$fileList->getDeleteFile();}, 'Nette\UnexpectedValueException');
		$fileManager->enableDeleteFile();
		Assert::type('ZaxCMS\Components\FileManager\DeleteFileControl', $fileList->getDeleteFile());
	}

}

$test = new FileManagerControlTest($container);
$test->run();
