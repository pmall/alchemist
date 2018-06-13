<?php declare(strict_types=1);

namespace Quanta\Injection\Pools;

use Quanta\Injection\ArgumentList;

final class ValueMap implements ArgumentsPoolInterface
{
    /**
     * The associative array of name to value pairs.
     *
     * @var array
     */
    private $map;

    /**
     * Set up a value map with the given associative array of name to value
     * pairs.
     *
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * Bind parameters to values associated with their names.
     *
     * @param \ReflectionParameter[] $parameters
     * @return \Quanta\Injection\ArgumentList
     */
    public function arguments(array $parameters): ArgumentList
    {
        $arguments = new ArgumentList;

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();

            if (isset($this->map[$name])) {
                $arguments = $arguments->with($parameter, function () use ($name) {
                    return $this->map[$name];
                });
            }
        }

        return $arguments;
    }
}
