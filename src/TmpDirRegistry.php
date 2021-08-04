<?php

namespace Demv\Tmpdir;

use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

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
     * @var string[]
     */
    private $files = [];
    /**
     * @var TmpDirRegistry
     */
    private static $instance;

    public function __destruct()
    {
        array_map(
            static function (string $filepath): void {
                self::deletefile($filepath);
            },
            $this->files
        );
        $this->files = [];
        array_map(
            static function (string $dir): void {
                self::deleteDir($dir);
            },
            $this->dirs
        );
        $this->dirs = [];
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

        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . sprintf('%s_%s', $dirname, $this->getUniqid());
        $dir = self::addSeperatorIfNecessary($dir);
        if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
        $this->dirs[] = $dir;

        return $dir;
    }

    /**
     * @param string $dir
     * @param string $filename
     *
     * @return string
     */
    public function createFileInSystemTmp(string $dir, string $filename): string
    {
        $filename = trim($filename);
        if ($filename === '') {
            throw new RuntimeException('Filename must not be empty so we do not delete entire temp directory.');
        }

        $info = pathinfo($filename);
        if (!array_key_exists('extension', $info) || !array_key_exists('filename', $info)) {
            throw new RuntimeException('Can\'t read info from filename.');
        }

        $filepath = self::addSeperatorIfNecessary($dir . DIRECTORY_SEPARATOR) . sprintf('%s_%s.%s', $info['filename'], $this->getUniqid(), $info['extension']);
        $handle   = fopen($filepath, 'wb+');
        fclose($handle);
        if (!file_exists($filepath)) {
            throw new RuntimeException(sprintf('File "%s" was not created', $filepath));
        }
        $this->files[] = $filepath;

        return $filepath;
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
            throw new InvalidArgumentException(sprintf('%s must be a directory', $dirPath));
        }

        $files = self::getFiles($dirPath);

        foreach ($files as $fileinfo) {
            $rm = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $rm($fileinfo->getRealPath());
        }

        rmdir($dirPath);
    }

    /**
     * @param string $filepath
     */
    private static function deleteFile(string $filepath): void
    {
        if (!file_exists($filepath)) {
            throw new InvalidArgumentException(sprintf('%s must be a file', $filepath));
        }

        unlink($filepath);
    }

    /**
     * @param string $dirPath
     *
     * @return RecursiveIteratorIterator
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

    /**
     * @return string
     */
    public function getUniqid(): string
    {
        return uniqid('', true);
    }
}
