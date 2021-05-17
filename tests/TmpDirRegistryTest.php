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

    public function setUp()
    {
        parent::setUp();
        $this->instance = TmpDirRegistry::instance();
    }

    public function tearDown()
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

    public function testCreateDirInSystemTmpDirname(): void
    {
        $dirname       = 'testdirectory';
        $uniqueDirname = $this->instance->createDirInSystemTmp($dirname);
        self::assertContains($dirname, $uniqueDirname);
        self::assertTrue(strlen($dirname) < strlen($uniqueDirname));
    }

    public function testCreateDirInSystemTmp(): void
    {
        $dirname       = 'testdirectory';
        $uniqueDirname = $this->instance->createDirInSystemTmp($dirname);
        self::assertFileExists($uniqueDirname);
    }

    //    public function testCreateFileInSystemTmpFilename(): void
    //    {
    //        $dirname        = 'testdirectory';
    //        $filename       = 'testfilename.txt';
    //        $instance       = TmpDirRegistry::instance();
    //        $uniqueDirname  = $instance->createDirInSystemTmp($dirname);
    //        $uniqueFilename = $instance->createFileInSystemTmp($uniqueDirname, $filename);
    //        $info           = pathinfo($uniqueFilename);
    //
    //        self::assertContains($filename, $info['filename']);
    //        self::assertTrue(strlen($filename) < strlen($uniqueFilename));
    //    }
    //
    //    public function testCreateFileInSystemTmp(): void
    //    {
    //        $dirname        = 'testdirectory';
    //        $filename       = 'testfilename.txt';
    //        $instance       = TmpDirRegistry::instance();
    //        $uniqueDirname  = $instance->createDirInSystemTmp($dirname);
    //        $uniqueFilename = $instance->createFileInSystemTmp($uniqueDirname, $filename);
    //        self::assertFileExists($uniqueFilename);
    //    }
}
