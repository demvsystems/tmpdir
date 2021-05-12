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
}
