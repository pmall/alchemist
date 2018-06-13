<?php

require __DIR__ . '/../factories.php';

use Quanta\Injection\Pools\ArgumentsPoolInterface;
use Quanta\Injection\Pools\PlaceholderList;

describe('PlaceholderList', function () {

    beforeEach(function () {

        $this->strategy = new PlaceholderList(['v1', 'v2']);

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should sequentially bind parameters to the next placeholder until the list is exhausted', function () {

            $p1 = parameter(0);
            $p2 = parameter(1);
            $p3 = parameter(2);

            $test = $this->strategy->arguments([$p1, $p2, $p3]);

            expect($test->isBound($p1))->toBeTruthy();
            expect($test->isBound($p2))->toBeTruthy();
            expect($test->isBound($p3))->toBeFalsy();

            expect($test->argument($p1))->toEqual('v1');
            expect($test->argument($p2))->toEqual('v2');

        });

    });

});
