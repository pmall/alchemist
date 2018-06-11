<?php

require __DIR__ . '/../factories.php';

use function Eloquent\Phony\mock;
use function Eloquent\Phony\partialMock;

use Ellipse\Resolving\ArgumentList;
use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\AbstractArgumentsPoolDecorator;

describe('AbstractArgumentsPoolDecorator', function () {

    beforeEach(function () {

        $this->delegate = mock(ArgumentsPoolInterface::class);

        $this->strategy = partialMock(AbstractArgumentsPoolDecorator::class, [
            $this->delegate->get(),
        ])->get();

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should proxy the delegate', function () {

            $p1 = parameter(0);
            $p2 = parameter(1);

            $arguments = new ArgumentList;

            $this->delegate->arguments->with([$p1, $p2])->returns($arguments);

            $test = $this->strategy->arguments([$p1, $p2]);

            expect($test)->toBe($arguments);

        });

    });

});
