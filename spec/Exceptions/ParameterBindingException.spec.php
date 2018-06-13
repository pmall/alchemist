<?php

use function Eloquent\Phony\mock;

use Quanta\Injection\Exceptions\ResolvingExceptionInterface;
use Quanta\Injection\Exceptions\ParameterBindingException;

describe('ParameterBindingException', function () {

    it('should implement ResolvingExceptionInterface', function () {

        $parameter = mock(ReflectionParameter::class);

        $parameter->getName->returns('parameter');
        $parameter->__toString->returns('description');

        $test = new ParameterBindingException($parameter->get());

        expect($test)->toBeAnInstanceOf(ResolvingExceptionInterface::class);

    });

});
