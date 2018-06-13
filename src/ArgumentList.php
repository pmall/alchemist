<?php declare(strict_types=1);

namespace Quanta\Injection;

use Throwable;
use IteratorAggregate;
use ReflectionParameter;

use Quanta\Injection\Exceptions\ParameterBindingException;
use Quanta\Injection\Exceptions\ArgumentResolutionException;

final class ArgumentList implements IteratorAggregate
{
    /**
     * The associative array of position to argument pairs.
     *
     * @var array
     */
    private $map;

    /**
     * Set up an argument list with the given associative array of position to
     * argument pairs.
     *
     * @param array $map
     */
    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    /**
     * Generator yielding position to argument pairs.
     *
     * @return \Generator
     */
    public function getIterator()
    {
        yield from $this->map;
    }

    /**
     * Return a new argument list with the given callable bound to the given
     * parameter position. Using callable allows to throw exceptions during the
     * resolution of the argument (ex: container could fail when producing
     * a value).
     *
     * @param \ReflectionParameter  $parameter
     * @param callable              $value
     * @return \Quanta\Injection\ArgumentList
     */
    public function with(ReflectionParameter $parameter, callable $argument): ArgumentList
    {
        $position = $parameter->getPosition();

        // not using array_merge because it won't merge integer keys.
        return new ArgumentList($this->map + [$position => $argument]);
    }

    /**
     * Return whether the given parameter position is bound to an argument.
     *
     * @param \ReflectionParameter $parameter
     * @return bool
     */
    public function isBound(ReflectionParameter $parameter): bool
    {
        $position = $parameter->getPosition();

        return isset($this->map[$position]);
    }

    /**
     * Return the argument bound to the given parameter position.
     *
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws \Quanta\Injection\Exceptions\ParameterBindingException
     * @throws \Quanta\Injection\Exceptions\ArgumentResolutionException
     */
    public function argument(ReflectionParameter $parameter)
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
}
