<?php

namespace Hexlet\Code\Utils;

use function PHPUnit\Framework\isReadable;

function isFileReadable($filePath): bool
{
    if (! is_readable($filePath)) {
        throw new \Exception("{'$filePath'} is not readble\n", 100);
    }
    return true;
}

function getFileType($filePath)
{
    return pathinfo($filePath, PATHINFO_EXTENSION);
}

function isEqualFilesExtension($firstFilePath, $secondFilePath)
{
    if (pathinfo($firstFilePath, PATHINFO_EXTENSION) !== pathinfo($secondFilePath, PATHINFO_EXTENSION)) {
        throw new \Exception("Files have different types\n", 200);
    }
    return true;
}

function getFixtureFullPath($fixtureName)
{
    $parts = [__DIR__, 'fixtures', $fixtureName];
    return realpath(implode('/', $parts));
}

function getFileFullPath($fileName, $currentDir, ...$args)
{
    $parts = [$currentDir,...$args, $fileName];
    $path = implode(DIRECTORY_SEPARATOR, $parts);
    $realPath = realpath($path);
    if (!$realPath) {
        throw new \Exception("{$path}  is not readble or doesn't exist\n", 100);
    }
    return $path;
}

function getFileContents(string $fileName)
{
    $absolutePath = realpath($fileName);
    if ($absolutePath) {
        isFileReadable($absolutePath);
        return file_get_contents($absolutePath);
    }

    $workingDirectory = getcwd();
    $parts = [$workingDirectory, $fileName];
    $realPath = realpath(implode(DIRECTORY_SEPARATOR, $parts));
    if (!$realPath) {
        throw new \Exception("{$fileName}  is not readble or doesn't exist\n", 100);
    }

    isFileReadable($realPath);
    return file_get_contents($realPath);
}
