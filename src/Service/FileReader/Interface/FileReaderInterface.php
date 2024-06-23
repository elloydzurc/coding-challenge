<?php

namespace App\Service\FileReader\Interface;

interface FileReaderInterface
{
    public function read(string $file, ?string $storage = null): iterable;
}
