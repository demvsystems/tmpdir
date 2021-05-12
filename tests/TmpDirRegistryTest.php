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
    public function testGetInstance(): void
    {
        $instance = TmpDirRegistry::instance();
        self::assertNotNull($instance);
        $anotherInstance = TmpDirRegistry::instance();
        self::assertEquals($instance, $anotherInstance);
    }

    //    public function testDestructDirectory(): void
    //    {
    //        $dirname       = 'testdirectory';
    //        $instance      = TmpDirRegistry::instance();
    //        $uniqueDirname = $instance->createDirInSystemTmp($dirname);
    //        $instance->__destruct();
    //        self::assertFileNotExists($uniqueDirname);
    //    }

    public function testCreateDirInSystemTmpDirname(): void
    {
        $dirname       = 'testdirectory';
        $instance      = TmpDirRegistry::instance();
        $uniqueDirname = $instance->createDirInSystemTmp($dirname);
        self::assertContains($dirname, $uniqueDirname);
        self::assertTrue(strlen($dirname) < strlen($uniqueDirname));
    }

    public function testCreateDirInSystemTmp(): void
    {
        $dirname       = 'testdirectory';
        $instance      = TmpDirRegistry::instance();
        $uniqueDirname = $instance->createDirInSystemTmp($dirname);
        self::assertFileExists($uniqueDirname);
    }

    public function testCreateFileInSystemTmpFilename(): void
    {
        $dirname        = 'testdirectory';
        $filename       = 'testfilename';
        $instance       = TmpDirRegistry::instance();
        $uniqueDirname  = $instance->createDirInSystemTmp($dirname);
        $uniqueFilename = $instance->createFileInSystemTmp($uniqueDirname, $filename);
        self::assertContains($filename, $uniqueFilename);
        self::assertTrue(strlen($filename) < strlen($uniqueFilename));
    }

    public function testCreateFileInSystemTmp(): void
    {
        $dirname        = 'testdirectory';
        $filename       = 'testfilename';
        $instance       = TmpDirRegistry::instance();
        $uniqueDirname  = $instance->createDirInSystemTmp($dirname);
        $uniqueFilename = $instance->createFileInSystemTmp($uniqueDirname, $filename);
        self::assertFileExists($uniqueFilename);
    }
}
