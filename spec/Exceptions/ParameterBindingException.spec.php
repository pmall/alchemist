<?php

use function Eloquent\Phony\mock;

use Ellipse\Resolving\Exceptions\ResolvingExceptionInterface;
use Ellipse\Resolving\Exceptions\ParameterBindingException;

describe('ParameterBindingException', function () {

    it('should implement ResolvingExceptionInterface', function () {

        $parameter = mock(ReflectionParameter::class);

        $parameter->getName->returns('parameter');
        $parameter->__toString->returns('description');

        $test = new ParameterBindingException($parameter->get());

        expect($test)->toBeAnInstanceOf(ResolvingExceptionInterface::class);

    });

});
