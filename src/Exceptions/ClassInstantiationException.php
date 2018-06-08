<?php declare(strict_types=1);

namespace Ellipse\Resolving\Exceptions;

use RuntimeException;

final class ClassInstantiationException extends RuntimeException implements ResolvingExceptionInterface
{
    public function __construct(string $class, ResolvingExceptionInterface $previous)
    {
        $template = "Failed to instantiate class %s";

        $msg = sprintf($template, $class);

        parent::__construct($msg, 0, $previous);
    }
}
