<?php

require __DIR__ . '/../factories.php';

use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\ValueMap;

describe('ValueMap', function () {

    beforeEach(function () {

        $this->strategy = new ValueMap([
            'name' => 'value',
        ]);

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should bind parameters to values associated with their names.', function () {

            $p1 = parameter(0);
            $p2 = parameter(1, ['name' => 'name']);
            $p3 = parameter(2);

            $test = $this->strategy->arguments([$p1, $p2, $p3]);

            expect($test->isBound($p1))->toBeFalsy();
            expect($test->isBound($p2))->toBeTruthy();
            expect($test->isBound($p3))->toBeFalsy();

            expect($test->argument($p2))->toEqual('value');

        });

    });

});
