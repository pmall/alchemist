<?php declare(strict_types=1);

namespace Ellipse\Resolving\Pools;

use Ellipse\Resolving\ArgumentList;

interface ArgumentsPoolInterface
{
    /**
     * Return an argument list from the given list of parameters.
     *
     * @param \ReflectionParameter[] $parameters
     * @return \Ellipse\Resolving\ArgumentList
     */
    public function arguments(array $parameters): ArgumentList;
}
