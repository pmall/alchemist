<?php declare(strict_types=1);

namespace Ellipse\Resolving\Pools;

use Ellipse\Resolving\Arguments;

final class NullValues implements ArgumentsPoolInterface
{
    /**
     * Bind the parameters to null when they are nullable.
     *
     * @param \ReflectionParameter[]        $parameters
     * @return \Ellipse\Resolving\Arguments
     */
    public function arguments(array $parameters): Arguments
    {
        $arguments = new Arguments;

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && $type->allowsNull()) {
                $arguments = $arguments->with($parameter, function () {
                    return null;
                });
            }
        }

        return $arguments;
    }
}
