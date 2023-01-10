<?php

namespace Hexlet\Code\Utils;

function isFileReadable(string $filePath): bool
{
    if (! is_readable($filePath)) {
        throw new \Exception("{'$filePath'} is not readble\n", 100);
    }
    return true;
}

function getFileType(string $filePath): string
{
    return pathinfo($filePath, PATHINFO_EXTENSION);
}

function isEqualFilesExtension(string $firstFilePath, string $secondFilePath): bool
{
    if (pathinfo($firstFilePath, PATHINFO_EXTENSION) !== pathinfo($secondFilePath, PATHINFO_EXTENSION)) {
        throw new \Exception("Files have different types\n", 200);
    }
    return true;
}

function getFileContents(string $fileName): mixed
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
