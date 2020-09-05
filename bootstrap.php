<?php

if (file_exists(__DIR__ . '/dumps.json') === false) {
    touch(__DIR__ . '/dumps.json');
    file_put_contents(__DIR__ . '/dumps.json', '[]');
}

function guidv4(string $data = null): string
{
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function storeDump(Dump $dump) {

    $jsonString = file_get_contents(__DIR__ . '/dumps.json');
    $dumps = json_decode($jsonString);
    array_push($dumps, $dump->toArray());
    file_put_contents(__DIR__ . '/dumps.json', json_encode($dumps, JSON_PRETTY_PRINT));
}


class Dump {
    protected $id;
    protected $make;
    protected $model;
    protected $content;
    protected $firmwareVersion;

    public static function fromData(stdClass $data): self
    {
        return new self($data->id, $data->make, $data->model, $data->content);
    }

    public function __construct($id, $make, $model, $content)
    {
        $this->id = $id;
        $this->make = $make;
        $this->model = $model;
        $this->content = $content;

        $this->extractFirmwareVersion();
    }

    public function getDownloadFileName(): string
    {
        return $this->make . '_' . $this->model . '.conf';
    }

    protected function extractFirmwareVersion()
    {
        preg_match('/Betaflight \/ [a-zA-Z0-9_-]+ \([a-zA-Z0-9_-]+\) (?<VERSION>[^(]+)/', $this->content, $matches);

        if (array_key_exists('VERSION', $matches) === false) {
            return;
        }

        $this->firmwareVersion = trim($matches['VERSION']);
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'make' => $this->make,
            'model' => $this->model,
            'firmwareVersion' => $this->firmwareVersion,
            'content' => $this->content,
        ];
    }

    public function getContent(): string
    {
        return $this->content;
    }

}