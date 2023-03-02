<?php

use Swow\Coroutine;
use SwowCloud\SizedWaitGroup\SizedWaitGroup;

use function Swow\defer;


it('test sized wait groups', function () {
    $s = new SizedWaitGroup(5);
    for ($i = 0; $i < 3; ++$i) {
        $s->Add();
        Coroutine::run(function () use ($s) {
            defer(function () use ($s) {
                $s->Done();
            });
            sleep(3);
            $coroutine = Coroutine::getCurrent();
            $this->assertSame($coroutine->getState(), $coroutine::STATE_RUNNING);
            echo Coroutine::getCurrent()->getId() . PHP_EOL;
        });
    }
    $s->Wait();
    echo "sleep 3" . PHP_EOL;
});
it('TestThrottling', function () {
    $s = new SizedWaitGroup(3);
    for ($i = 0; $i < 5; ++$i) {
        $s->Add();
        Coroutine::run(function () use ($s) {
            defer(function () use ($s) {
                $s->Done();
            });
            if ($s->current->getLength() > 4) {
                echo "not never here" . PHP_EOL;
                expect($s->current->getLength())->toBeLessThan(3);

                return;
            }
        });
    }
    $s->Wait();
    expect('here')->toBe('here');
});

