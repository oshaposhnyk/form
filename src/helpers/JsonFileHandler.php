<?php

namespace app\src\helpers;

use core\Application;

class JsonFileHandler
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = Application::$ROOT_DIR."/db/$filePath.json";
    }

    public function read(): array|null
    {
        $jsonData = file_get_contents($this->filePath);
        return json_decode($jsonData, true);
    }

    public function write($data): void
    {
        $existingData = $this->read() ?? [];
        $existingData[] = $data;
        $jsonData = json_encode($existingData);
        file_put_contents($this->filePath, $jsonData);
    }

    public function isExist(array $field): bool
    {
        $data = $this->read();
        foreach ($data as $record) {
            $record = reset($record);
            if (!array_key_exists($field['name'], $record)) {
                continue;
            }
            if ($record[$field['name']] === $field['value']) {
                return true;
            }
        }
        return false;
    }
}




