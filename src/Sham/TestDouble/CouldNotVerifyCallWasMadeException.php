<?php

namespace Sham\TestDouble;

class CouldNotVerifyCallWasMadeException extends \Exception
{
    private $argsThatCouldNotBeMatched;
    private $callsThatWereMade;

    public function __construct($argsThatCouldNotBeMatched, $callsThatWereMade)
    {
        $this->argsThatCouldNotBeMatched = $argsThatCouldNotBeMatched;
        $this->callsThatWereMade = $callsThatWereMade;

        $message = $this->formatMessage();

        parent::__construct($message);
    }

    private function formatMessage()
    {
        $methodName = current($this->callsThatWereMade)[0];
        
        $message = "Couldn't verify this call was made\n\n";
        $message.= CallFormatter::format([$methodName, $this->argsThatCouldNotBeMatched]);
        $message.= "\nThis method was called with:\n\n";
        foreach ($this->callsThatWereMade as $call) {
            $message.= CallFormatter::format($call)."\n";
        }

        return $message;
    }
}
