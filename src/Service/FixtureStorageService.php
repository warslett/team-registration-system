<?php


namespace App\Service;

/**
 * Used for registering and retrieving fixtures for tests
 * @package App\Service
 */
class FixtureStorageService
{

    private $storage = [];

    /**
     * @param string $type
     * @param string $reference
     * @param mixed $entity
     * @throws \Exception
     */
    public function set(string $type, string $reference, $entity)
    {
        if (is_null($this->storage[$type])) {
            $this->storage[$type] = [];
        }
        if (!is_null($this->storage[$type][$reference])) {
            throw new \Exception(sprintf(
                "A fixture with reference %s is already registered for the type %s",
                $reference,
                $type
            ));
        }
        $this->storage[$type][$reference] = $entity;
    }

    /**
     * @param string $type
     * @param string $reference
     * @throws \Exception
     * @return mixed
     */
    public function get(string $type, string $reference)
    {
        if (is_null($this->storage[$type])) {
            throw new \Exception(sprintf(
                "No fixtures registered for the type %s",
                $type
            ));
        }
        if (is_null($this->storage[$type][$reference])) {
            throw new \Exception(sprintf(
                "No fixture registered with reference %s for the type %s",
                $reference,
                $type
            ));
        }

        return $this->storage[$type][$reference];
    }
}
