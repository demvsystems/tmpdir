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

    public function testCreateDirInSystemTmpFilename(): void
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
}
