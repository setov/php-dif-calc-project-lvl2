<?php

declare(strict_types=1);

namespace Hexlet\Code\Utils;

class FileException extends \Exception
{
}

function isFileReadable(string $filePath): bool
{
    if (!is_readable($filePath)) {
        throw new FileException("File '{$filePath}' is not readable", 100);
    }
    return true;
}

function getFileType(string $filePath): string
{
    return pathinfo($filePath, PATHINFO_EXTENSION);
}

function isEqualFilesExtension(string $firstFilePath, string $secondFilePath): bool
{
    if (getFileType($firstFilePath) !== getFileType($secondFilePath)) {
        throw new FileException("Files '{$firstFilePath}' and '{$secondFilePath}' have different types", 200);
    }
    return true;
}

function getFileContents(string $fileName): string
{
    $absolutePath = realpath($fileName) ?: realpath(getcwd() . DIRECTORY_SEPARATOR . $fileName);

    if (!$absolutePath || !isFileReadable($absolutePath)) {
        throw new FileException("File '{$fileName}' is not readable or doesn't exist", 100);
    }

    return file_get_contents($absolutePath);
}
