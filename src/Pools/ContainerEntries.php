<?php declare(strict_types=1);

namespace Ellipse\Resolving\Pools;

use Psr\Container\ContainerInterface;

use Ellipse\Resolving\Arguments;

final class ContainerEntries implements ArgumentsPoolInterface
{
    /**
     * The container used to retrieve entries.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * Set up a container entries object with the given container.
     *
     * @param \Psr\Container\ContainerInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Bind parameters with a class type hint to container entries with this
     * class name as identifier.
     *
     * @param \ReflectionParameter[]        $parameters
     * @return \Ellipse\Resolving\Arguments
     */
    public function arguments(array $parameters): Arguments
    {
        $arguments = new Arguments;

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && ! $type->isBuiltIn() && $this->container->has((string) $type)) {
                $arguments = $arguments->with($parameter, function () use ($type) {
                    return $this->container->get((string) $type);
                });
            }
        }

        return $arguments;
    }
}
