<?php

namespace Scanner\Event;

interface MethodCallListener extends Listener
{
    public function methodCalled(CallMethodEvent $evt);
}