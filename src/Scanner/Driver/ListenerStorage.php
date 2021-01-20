<?php


namespace Scanner\Driver;

use InvalidArgumentException;
use Scanner\Event\Listener;

class ListenerStorage
{
    private array $listeners = [];

    public function add(Listener $listener, string $type): void
    {
        if (!isset($this->listeners[$type])) {
            $this->listeners[$type][] = $listener;
            return;
        }
        if (!in_array($listener, $this->listeners[$type], true)) {
            $this->listeners[$type][] = $listener;
            return;
        }
        throw new InvalidArgumentException('Listener is already exists.');
    }

    public function remove(Listener $listener, string $type): void
    {
        if (!isset($this->listeners[$type])) {
            throw new InvalidArgumentException(sprintf('This type of "%s" does not exist.', $type));
        }

        $key = array_search($listener, $this->listeners[$type], true);
        if (is_int($key)) {
            unset($this->listeners[$type][$key]);
            if (count($this->listeners[$type]) < 1) {
                unset($this->listeners[$type]);
            }
            return;
        }
        throw new InvalidArgumentException('This listener does not exist.');
    }

    public function getBy(string $type): ?array
    {
        if (!array_key_exists($type, $this->listeners)) {
            return null;
        }
        return $this->listeners[$type];
    }

    public function clearOf(string $type): ?array
    {
        $listeners = $this->getBy($type);
        if ($listeners !== null) {
            unset($this->listeners[$type]);
        }
        return $listeners;
    }

    public function getListeners(): array
    {
        return $this->listeners;
    }
}