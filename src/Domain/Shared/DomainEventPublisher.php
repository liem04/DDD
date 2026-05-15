<?php

namespace Testcenter\Domain\Shared;

use Illuminate\Contracts\Events\Dispatcher;

class DomainEventPublisher
{
    public function __construct(
        private readonly Dispatcher $dispatcher
    ) {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}
