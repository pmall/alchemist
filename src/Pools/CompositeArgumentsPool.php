<?php declare(strict_types=1);

namespace Ellipse\Resolving\Pools;

use Ellipse\Resolving\Arguments;

final class CompositeArgumentsPool implements ArgumentsPoolInterface
{
    /**
     * The list of arguments pools.
     *
     * @var \Ellipse\Resolving\Pools\ArgumentsPoolInterface[]
     */
    private $pools;

    /**
     * Set up a composite arguments pool object with the given list of arguments
     * pools.
     *
     * @param \Ellipse\Resolving\Pools\ArgumentsPoolInterface[] $pools
     */
    public function __construct(array $pools)
    {
        $this->pools = $pools;
    }

    /**
     * Sequentially bind the unbound parameters using the arguments pools in the
     * order they are listed.
     *
     * @param \ReflectionParameter[]        $parameters
     * @return \Ellipse\Resolving\Arguments
     */
    public function arguments(array $parameters): Arguments
    {
        return array_reduce($this->pools, function ($arguments, $pool) use ($parameters) {
            $unbound = $arguments->unbound($parameters);

            $new = $pool->arguments($unbound);

            return $arguments->merge($new);
        }, new Arguments);
    }

    /**
     * Return the unbound parameters from the given list of parameters.
     *
     * @param \Ellipse\Resolving\Arguments  $arguments
     * @param \ReflectionParameter[]        $parameters
     * @return array
     */
    private function diff(Arguments $arguments, array $parameters): array
    {
        $diff = array_filter($parameters, function ($parameter) use ($arguments) {
            return ! $arguments->isBound($parameter);
        });

        return array_values($diff);
    }
}
