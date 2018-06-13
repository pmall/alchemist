<?php

require __DIR__ . '/factories.php';

use function Eloquent\Phony\stub;
use function Eloquent\Phony\mock;

use Quanta\Injection\ArgumentList;
use Quanta\Injection\Exceptions\ParameterBindingException;
use Quanta\Injection\Exceptions\ArgumentResolutionException;

describe('ArgumentList', function () {

    beforeEach(function () {

        $this->argument1 = stub();
        $this->argument2 = stub();

        $this->arguments = new ArgumentList([0 => $this->argument1, 2 => $this->argument2]);

    });

    it('should implement IteratorAggregate', function () {

        expect($this->arguments)->toBeAnInstanceOf(IteratorAggregate::class);

    });

    context('when used as an iterator', function () {

        it('should yield position to arguments pairs', function () {

            $test = iterator_to_array($this->arguments);

            expect($test[0])->toBe($this->argument1);
            expect($test[2])->toBe($this->argument2);

        });

    });

    describe('->with()', function () {

        it('shoud return a new arguments object with the given callable bound to the given parameter position', function () {

            $parameter = parameter(1);
            $argument = stub();

            $test = $this->arguments->with($parameter, $argument);

            expect(iterator_to_array($test)[1])->toBe($argument);

        });

    });

    describe('->isBound()', function () {

        context('when the given parameter position is bound to an argument', function () {

            it('should return true', function () {

                $parameter = parameter(0);

                $test = $this->arguments->isBound($parameter);

                expect($test)->toBeTruthy();

            });

        });

        context('when the given parameter position is not bound to an argument', function () {

            it('should return true', function () {

                $parameter = parameter(1);

                $test = $this->arguments->isBound($parameter);

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->argument()', function () {

        context('when the given parameter position is bound to an argument', function () {

            context('when the argument resolution does not throw an exception', function () {

                it('should return the argument', function () {

                    $parameter = parameter(0);

                    $this->argument1->returns('value');

                    $test = $this->arguments->argument($parameter);

                    expect($test)->toEqual('value');

                });

            });

            context('when the argument evaluation throws an exception', function () {

                it('should throw a ArgumentResolutionException', function () {

                    $parameter = parameter(0);
                    $exception = mock(Throwable::class)->get();

                    $this->argument1->throws($exception);

                    $test = function () use ($parameter) {

                        $this->arguments->argument($parameter);

                    };

                    $exception = new ArgumentResolutionException($parameter, $exception);

                    expect($test)->toThrow($exception);

                });

            });

        });

        context('when the given parameter position is not bound to an argument', function () {

            it('should throw a ParameterBindingException', function () {

                $parameter = parameter(1);

                $test = function () use ($parameter) {

                    $this->arguments->argument($parameter);

                };

                $exception = new ParameterBindingException($parameter);

                expect($test)->toThrow($exception);

            });

        });

    });

});
