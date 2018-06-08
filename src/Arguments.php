<?php declare(strict_types=1);

namespace Ellipse\Resolving;

use Throwable;
use ReflectionParameter;

use Ellipse\Resolving\Exceptions\ParameterBindingException;
use Ellipse\Resolving\Exceptions\ArgumentResolutionException;

final class Arguments
{
    /**
     * The associative array of position to argument pairs.
     *
     * @var array
     */
    private $map;

    /**
     * Set up an arguments with the given associative array of position to
     * argument pairs.
     *
     * @param array $map
     */
    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    /**
     * Return the parameters whose positions are not bound to an argument.
     *
     * @param \ReflectionParameter[] $parameters
     * @return bool
     */
    public function unbound(array $parameters): array
    {
        $unbound = array_filter($parameters, function ($parameter) {
            $position = $parameter->getPosition();

            return ! isset($this->map[$position]);
        });

        return array_values($unbound);
    }

    /**
     * Return the argument bound to the given parameter position.
     *
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws \Ellipse\Resolving\Exceptions\ParameterBindingException
     * @throws \Ellipse\Resolving\Exceptions\ArgumentResolutionException
     */
    public function value(ReflectionParameter $parameter)
    {
        $position = $parameter->getPosition();

        if (isset($this->map[$position])) {
            try {
                return $this->map[$position]();
            } catch (Throwable $e) {
                throw new ArgumentResolutionException($parameter, $e);
            }
        }

        throw new ParameterBindingException($parameter);
    }

    /**
     * Return a new arguments with the given callable bound to the given
     * parameter position. Using callable allows to throw exceptions during the
     * resolution of the argument value (ex: ContainerResolvingStrategy).
     *
     * @param \ReflectionParameter  $parameter
     * @param callable              $value
     * @return \Ellipse\Resolving\Arguments
     */
    public function with(ReflectionParameter $parameter, callable $value): Arguments
    {
        $position = $parameter->getPosition();

        // warning: not using array_merge because it wont marge integer keys.
        $map = $this->map + [$position => $value];

        return new Arguments($map);
    }
}
