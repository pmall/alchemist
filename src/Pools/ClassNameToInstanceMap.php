<?php declare(strict_types=1);

namespace Ellipse\Resolving\Pools;

use Ellipse\Resolving\ArgumentList;

final class ClassNameToInstanceMap implements ArgumentsPoolInterface
{
    /**
     * The associative array of class name to instance pairs.
     *
     * @var array
     */
    private $map;

    /**
     * Set up a class name to instance map with the given associative array of
     * class name to instance pairs.
     *
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * Bind parameters with a class type hint to instances associated with those
     * class names.
     *
     * @param \ReflectionParameter[] $parameters
     * @return \Ellipse\Resolving\ArgumentList
     */
    public function arguments(array $parameters): ArgumentList
    {
        $arguments = new ArgumentList;

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && ! $type->isBuiltIn() && isset($this->map[(string) $type])) {
                $arguments = $arguments->with($parameter, function () use ($type) {
                    return $this->map[(string) $type];
                });
            }
        }

        return $arguments;
    }
}
