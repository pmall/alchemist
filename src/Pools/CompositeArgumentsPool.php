<?php declare(strict_types=1);

namespace Quanta\Injection\Pools;

use Quanta\Injection\ArgumentList;

final class CompositeArgumentsPool implements ArgumentsPoolInterface
{
    /**
     * The list of arguments pools.
     *
     * @var \Quanta\Injection\Pools\ArgumentsPoolInterface[]
     */
    private $pools;

    /**
     * Set up a composite arguments pool with the given list of arguments pools.
     *
     * @param \Quanta\Injection\Pools\ArgumentsPoolInterface[] $pools
     */
    public function __construct(array $pools)
    {
        $this->pools = $pools;
    }

    /**
     * Sequentially bind the unbound parameters to arguments using the arguments
     * pools.
     *
     * @param \ReflectionParameter[] $parameters
     * @return \Quanta\Injection\ArgumentList
     */
    public function arguments(array $parameters): ArgumentList
    {
        return array_reduce($this->pools, function ($arguments, $pool) use ($parameters) {
            $diff = $this->diff($arguments, $parameters);

            $new = $pool->arguments($diff);

            // not using array_merge because it won't merge integer keys.
            return new ArgumentList(iterator_to_array($arguments) + iterator_to_array($new));
        }, new ArgumentList);
    }

    /**
     * Return the unbound parameters from the given list of parameters.
     *
     * @param \Quanta\Injection\ArgumentList    $arguments
     * @param \ReflectionParameter[]            $parameters
     * @return array
     */
    private function diff(ArgumentList $arguments, array $parameters): array
    {
        $diff = array_filter($parameters, function ($parameter) use ($arguments) {
            return ! $arguments->isBound($parameter);
        });

        return array_values($diff);
    }
}
