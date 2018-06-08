<?php

require __DIR__ . '/../factories.php';

use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\Placeholders;

describe('Placeholders', function () {

    beforeEach(function () {

        $this->strategy = new Placeholders(['v1', 'v2']);

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should sequentially bind parameters to the next placeholder until the list is exhausted', function () {

            $p1 = parameter(0);
            $p2 = parameter(1);
            $p3 = parameter(2);

            $ps = [$p1, $p2, $p3];

            $test = $this->strategy->arguments($ps);

            expect($test->unbound($ps))->toEqual([$p3]);
            expect($test->value($p1))->toEqual('v1');
            expect($test->value($p2))->toEqual('v2');

        });

    });

});
