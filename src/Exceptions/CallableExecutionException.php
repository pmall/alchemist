<?php declare(strict_types=1);

namespace Ellipse\Resolving\Exceptions;

use RuntimeException;

final class CallableExecutionException extends RuntimeException implements ResolvingExceptionInterface
{
    public function __construct(callable $callable, ResolvingExceptionInterface $previous)
    {
        $template = "Failed to execute the callable";

        $msg = sprintf($template);

        parent::__construct($msg, 0, $previous);
    }
}
