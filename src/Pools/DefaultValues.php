<?php declare(strict_types=1);

namespace Quanta\Injection\Pools;

use Quanta\Injection\ArgumentList;

final class DefaultValues implements ArgumentsPoolInterface
{
    /**
     * Bind parameters to their default values when defined.
     *
     * @param \ReflectionParameter[] $parameters
     * @return \Quanta\Injection\ArgumentList
     */
    public function arguments(array $parameters): ArgumentList
    {
        $arguments = new ArgumentList;

        foreach ($parameters as $parameter) {
            if ($parameter->isDefaultValueAvailable()) {
                $arguments = $arguments->with($parameter, function () use ($parameter) {
                    return $parameter->getDefaultValue();
                });
            }
        }

        return $arguments;
    }
}
