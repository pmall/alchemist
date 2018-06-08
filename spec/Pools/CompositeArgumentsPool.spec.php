<?php

require __DIR__ . '/../factories.php';

use function Eloquent\Phony\stub;
use function Eloquent\Phony\mock;

use Ellipse\Resolving\Arguments;
use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\CompositeArgumentsPool;

describe('CompositeArgumentsPool', function () {

    beforeEach(function () {

        $this->strategy1 = mock(ArgumentsPoolInterface::class);
        $this->strategy2 = mock(ArgumentsPoolInterface::class);
        $this->strategy3 = mock(ArgumentsPoolInterface::class);

        $this->strategy = new CompositeArgumentsPool([
            $this->strategy1->get(),
            $this->strategy2->get(),
            $this->strategy3->get(),
        ]);

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should sequentially bind the unbound parameters using the arguments pools in the order they are listed', function () {

            $p1 = parameter(0);
            $p2 = parameter(1);
            $p3 = parameter(2);

            $arguments1 = new Arguments;
            $arguments2 = new Arguments([1 => stub()]);
            $arguments3 = new Arguments([0 => stub(), 1 => stub(), 2 => stub()]);

            $this->strategy1->arguments->with([$p1, $p2, $p3])->returns($arguments1);
            $this->strategy2->arguments->with([$p1, $p3])->returns($arguments2);
            $this->strategy3->arguments->with([])->returns($arguments3);

            $test = $this->strategy->arguments([$p1, $p2, $p3]);

            expect($test)->toBe($arguments3);

        });

    });

});
