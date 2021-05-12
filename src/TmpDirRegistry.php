<?php

namespace Demv\Tmpdir;

use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;

/**
 * Class TmpDirRegistry
 * @package Demv\Tmpdir
 */
final class TmpDirRegistry
{
    /**
     * @var string[]
     */
    private $dirs = [];

    /**
     * @var TmpDirRegistry
     */
    private static $instance;

    public function __destruct()
    {
        foreach ($this->dirs as $dir) {
            self::deleteDir($dir);
        }
    }

    /**
     * @return static
     */
    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $dirname
     *
     * @return string
     * @throws RuntimeException
     */
    public function createDirInSystemTmp(string $dirname): string
    {
        $dirname = trim($dirname);
        if ($dirname === '') {
            throw new RuntimeException('Dirname must not be empty so we do not delete entire temp directory.');
        }

        $uniqueId = uniqid('', true);
        $dir      = sys_get_temp_dir() . DIRECTORY_SEPARATOR . sprintf('%s%s', $dirname, $uniqueId);
        if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
        $this->dirs[] = $dir;

        return $dir;
    }

    /**
     * @param string $dirPath
     *
     * @throws InvalidArgumentException
     */
    private static function deleteDir(string $dirPath): void
    {
        $dirPath = self::addSeperatorIfNecessary($dirPath);

        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }

        $files = self::getFiles($dirPath);

        foreach ($files as $fileinfo) {
            $rm = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $rm($fileinfo->getRealPath());
        }

        rmdir($dirPath);
    }

    /**
     * @param string $dirPath
     *
     * @return RecursiveIteratorIterator|SplFileInfo[]
     */
    private static function getFiles(string $dirPath): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
    }

    /**
     * @param string $dirPath
     *
     * @return string
     */
    private static function addSeperatorIfNecessary(string $dirPath): string
    {
        return rtrim(trim($dirPath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
}
