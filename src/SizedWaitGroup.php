<?php

declare(strict_types=1);

namespace SwowCloud\SizedWaitGroup;


use Swow\Channel;
use Swow\Sync\WaitGroup;

class SizedWaitGroup
{
    public int $size;

    public Channel $current;

    public WaitGroup $waitGroup;

    public function __construct(int $limit)
    {
        $this->size = PHP_INT_MAX;
        if ($limit >= 1) {
            $this->size = $limit;
        }
        $this->current = new Channel($this->size);
        $this->waitGroup = new WaitGroup();
    }


    public function Add(): void
    {
        $this->current->push(true);
        $this->waitGroup->add(1);
    }


    public function Done(): void
    {
        $this->current->pop();
        $this->waitGroup->done();
    }

    public function Wait(): void
    {
        $this->waitGroup->wait();
    }

}

