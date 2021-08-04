<?php

namespace Test\Demv\Tmpdir;

use Demv\Tmpdir\TmpDirRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Class TmpDirRegistryTest
 * @package Test\Demv\Tmpdir
 */
final class TmpDirRegistryTest extends TestCase
{
    /**
     * @var TmpDirRegistry
     */
    private $instance;

    public function setUp(): void
    {
        parent::setUp();
        $this->instance = TmpDirRegistry::instance();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->instance->__destruct();
    }

    public function testGetInstance(): void
    {
        self::assertNotNull($this->instance);
        $anotherInstance = TmpDirRegistry::instance();
        self::assertEquals($this->instance, $anotherInstance);
    }

    public function testDestructDirectory(): void
    {
        $dirname       = 'testdirectory';
        $uniqueDirname = $this->instance->createDirInSystemTmp($dirname);
        $this->instance->__destruct();
        self::assertDirectoryNotExists($uniqueDirname);
    }

    public function testDestructFile(): void
    {
        $filename       = 'testfilename.txt';
        $dirname        = 'testdirectory';
        $dir            = $this->instance->createDirInSystemTmp($dirname);
        $uniqueFilename = $this->instance->createFileInSystemTmp($dir, $filename);
        $this->instance->__destruct();
        self::assertFileNotExists($uniqueFilename);
    }

    public function testCreateDirInSystemTmpDirname(): void
    {
        $dirname       = 'testdirectory';
        $uniqueDirname = $this->instance->createDirInSystemTmp($dirname);
        self::assertContains($dirname, $uniqueDirname);
        self::assertTrue(strlen($dirname) < strlen($uniqueDirname));
    }

    /**
     * @dataProvider provideTestCreateDirHasSeparatorAtTheEnd
     *
     * @param string $dir
     */
    public function testCreateDirHasSeparatorAtTheEnd(string $dir): void
    {
        $uniqueDirname  = $this->instance->createDirInSystemTmp($dir);
        $lastChar       = substr($uniqueDirname, -1);
        $nextToLastChar = substr($uniqueDirname, -2, 1);
        self::assertEquals('/', $lastChar);
        self::assertNotEquals('/', $nextToLastChar);
    }

    public function testCreateDirInSystemTmp(): void
    {
        $dirname       = 'testdirectory';
        $uniqueDirname = $this->instance->createDirInSystemTmp($dirname);
        self::assertFileExists($uniqueDirname);
    }

    public function testCreateFileInSystemTmpFilename(): void
    {
        $dirname            = $this->instance->createDirInSystemTmp('testdirectory');
        $filename           = 'testfilename.txt';
        $uniqueFilename     = $this->instance->createFileInSystemTmp($dirname, $filename);
        $uniqueFilenameInfo = pathinfo($uniqueFilename);
        $filenameInfo       = pathinfo($filename);

        self::assertContains($filenameInfo['filename'], $uniqueFilenameInfo['filename']);
        self::assertEquals($filenameInfo['extension'], $uniqueFilenameInfo['extension']);
        self::assertTrue(strlen($filename) < strlen($uniqueFilename));
    }

    public function testCreateFileInSystemTmpHasOnlyOneSeperator(): void
    {
        $dirname        = $this->instance->createDirInSystemTmp('testdirectory');
        $filename       = 'testfilename.txt';
        $uniqueFilename = $this->instance->createFileInSystemTmp($dirname, $filename);

        self::assertNotContains('//', $uniqueFilename);
    }

    public function testCreateFileInSystemTmp(): void
    {
        $dirname        = 'testdirectory';
        $filename       = 'testfilename.txt';
        $instance       = TmpDirRegistry::instance();
        $uniqueDirname  = $instance->createDirInSystemTmp($dirname);
        $uniqueFilename = $instance->createFileInSystemTmp($uniqueDirname, $filename);
        self::assertFileExists($uniqueFilename);
    }

    /**
     * @return string[][]
     */
    public function provideTestCreateDirHasSeparatorAtTheEnd(): array
    {
        return [
            ['testdirectory'],
            ['testdirectory/'],
        ];
    }
}
