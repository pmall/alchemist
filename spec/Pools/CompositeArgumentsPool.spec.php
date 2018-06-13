<?php

require __DIR__ . '/../factories.php';

use function Eloquent\Phony\stub;
use function Eloquent\Phony\mock;

use Quanta\Injection\ArgumentList;
use Quanta\Injection\Pools\ArgumentsPoolInterface;
use Quanta\Injection\Pools\CompositeArgumentsPool;

describe('CompositeArgumentsPool', function () {

    beforeEach(function () {

        $this->pool1 = mock(ArgumentsPoolInterface::class);
        $this->pool2 = mock(ArgumentsPoolInterface::class);
        $this->pool3 = mock(ArgumentsPoolInterface::class);

        $this->pool = new CompositeArgumentsPool([
            $this->pool1->get(),
            $this->pool2->get(),
            $this->pool3->get(),
        ]);

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should sequentially bind the unbound parameters to arguments using the arguments pools', function () {

            $p1 = parameter(0);
            $p2 = parameter(1);
            $p3 = parameter(2);
            $p4 = parameter(3);

            $arguments1 = new ArgumentList([
                1 => stub()->returns('v1'),
            ]);

            $arguments2 = new ArgumentList([
                0 => stub()->returns('v2'),
                1 => stub()->returns('v3'),
                2 => stub()->returns('v4'),
            ]);

            $arguments3 = new ArgumentList;

            $this->pool1->arguments->with([$p1, $p2, $p3, $p4])->returns($arguments1);
            $this->pool2->arguments->with([$p1, $p3, $p4])->returns($arguments2);
            $this->pool3->arguments->with([$p4])->returns($arguments3);

            $test = $this->pool->arguments([$p1, $p2, $p3, $p4]);

            expect($test->isBound($p1))->toBeTruthy();
            expect($test->isBound($p2))->toBeTruthy();
            expect($test->isBound($p3))->toBeTruthy();
            expect($test->isBound($p4))->toBeFalsy();

            expect($test->argument($p1))->toEqual('v2');
            expect($test->argument($p2))->toEqual('v1');
            expect($test->argument($p3))->toEqual('v4');

        });

    });

});
