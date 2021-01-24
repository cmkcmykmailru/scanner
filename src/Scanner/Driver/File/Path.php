<?php

namespace Scanner\Driver\File;

class Path
{
    private string $source;
    private $parts = [];
    private $baseName;

    /**
     * Path constructor.
     * @param string|null $source
     */
    public function __construct(?string $source = null)
    {
        if ($source !== null) {
            $this->source = rtrim($source, DIRECTORY_SEPARATOR);
            $this->split($this->source);
            $this->baseName = basename($this->source);
        }
    }

    private function split(string $path): void
    {
        $tempParts = explode(DIRECTORY_SEPARATOR, trim($path, DIRECTORY_SEPARATOR));

        $temp = $path;
        $count = count($tempParts) - 1;
        for ($i = $count; $i >= 0; $i--) {
            $part = $tempParts[$i];
            $this->parts[$part] = $temp;
            $temp = dirname($temp);
        }
        $this->parts['/'] = '/';
        $this->parts = array_reverse($this->parts);
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getBaseName(): string
    {
        return $this->baseName;
    }

    public function parent(): Path
    {
        return new self(dirname($this->source));
    }

    public function child(string $baseName): Path
    {
        return new self(implode(DIRECTORY_SEPARATOR, [$this->source, $baseName]));
    }

    public function equalsBaseName(string $path): bool
    {
        return basename($path) === $this->baseName;
    }

    public function equalsPartOfPath(string $path): bool
    {
        $parts = explode(DIRECTORY_SEPARATOR, trim($path, DIRECTORY_SEPARATOR));
        array_unshift($parts, "/");
        if (count($parts) > count($this->parts)) {
            return false;
        }

        $keys = array_keys($this->parts);

        foreach ($parts as $i => $part) {
            if ($keys[$i] !== $part) {
                return false;
            }
        }
        return true;
    }

    public function containsPartOfPath(string $name): bool
    {
        return isset($this->parts[$name]);
    }

    public function equals(string $baseName): bool
    {
        return $baseName === $this->baseName;
    }
}