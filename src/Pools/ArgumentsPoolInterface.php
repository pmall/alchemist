<?php declare(strict_types=1);

namespace Quanta\Injection\Pools;

use Quanta\Injection\ArgumentList;

interface ArgumentsPoolInterface
{
    /**
     * Return an argument list from the given list of parameters.
     *
     * @param \ReflectionParameter[] $parameters
     * @return \Quanta\Injection\ArgumentList
     */
    public function arguments(array $parameters): ArgumentList;
}
