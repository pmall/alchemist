<?php

require __DIR__ . '/../factories.php';

use function Eloquent\Phony\mock;

use Psr\Container\ContainerInterface;

use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\DefaultArgumentsPool;

describe('DefaultArgumentsPool', function () {

    beforeEach(function () {

        $this->instance1 = new class {};
        $this->instance2 = new class {};

        $this->container = mock(ContainerInterface::class);
        $this->instances = ['SomeClass1' => $this->instance1];
        $this->placeholders = ['v1', 'v2', 'v3'];

        $this->strategy = new DefaultArgumentsPool($this->container->get(), $this->instances, $this->placeholders);

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should have the default behavior', function () {

            $p1 = parameter(0);                                         // placeholder
            $p2 = parameter(1, ['type' => type(false, 'SomeClass1')]);  // instance
            $p3 = parameter(2, ['type' => type(false, 'SomeClass2')]);  // container
            $p4 = parameter(3, ['type' => type(false, 'SomeClass3')]);  // placeholder
            $p5 = parameter(4, ['type' => type(true, 'SomeClass4')]);   // placeholder
            $p6 = parameter(5, ['type' => type(true, 'SomeClass5')]);   // null
            $p7 = parameter(6, ['default' => 'default']);               // default

            $ps = [$p1, $p2, $p3, $p4, $p5, $p6, $p7];

            $this->container->has->with('SomeClass1')->returns(true);
            $this->container->has->with('SomeClass2')->returns(true);
            $this->container->has->with('SomeClass3')->returns(false);
            $this->container->has->with('SomeClass4')->returns(false);
            $this->container->has->with('SomeClass5')->returns(false);
            $this->container->get->with('SomeClass2')->returns($this->instance2);

            $test = $this->strategy->arguments($ps);

            expect($test->isBound($ps))->toEqual([]);
            expect($test->value($p1))->toEqual('v1');
            expect($test->value($p2))->toEqual($this->instance1);
            expect($test->value($p3))->toEqual($this->instance2);
            expect($test->value($p4))->toEqual('v2');
            expect($test->value($p5))->toEqual('v3');
            expect($test->value($p6))->toBeNull();
            expect($test->value($p7))->toEqual('default');

        });

    });

});
