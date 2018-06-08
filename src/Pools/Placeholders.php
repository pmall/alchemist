<?php declare(strict_types=1);

namespace Ellipse\Resolving\Pools;

use Ellipse\Resolving\Arguments;

final class Placeholders implements ArgumentsPoolInterface
{
    /**
     * The list of placeholders.
     *
     * @var array
     */
    private $placeholders;

    /**
     * Set up a placeholders object with the given list of placeholders.
     *
     * @param array $placeholders
     */
    public function __construct(array $placeholders)
    {
        $this->placeholders = $placeholders;
    }

    /**
     * Sequentially bind parameters to the next placeholder until the list is
     * exhausted.
     *
     * @param \ReflectionParameter[]        $parameters
     * @return \Ellipse\Resolving\Arguments
     */
    public function arguments(array $parameters): Arguments
    {
        $arguments = new Arguments;

        $placeholders = $this->placeholders;

        foreach ($parameters as $parameter) {
            if (count($placeholders) > 0) {
                $placeholder = array_shift($placeholders);

                $arguments = $arguments->with($parameter, function () use ($placeholder) {
                    return $placeholder;
                });
            }
        }

        return $arguments;
    }
}
