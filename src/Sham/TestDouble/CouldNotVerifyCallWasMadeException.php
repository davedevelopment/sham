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
        $message.= $this->indentCalls([[$methodName, $this->argsThatCouldNotBeMatched]]);
        $message.= "\nThis method was called with:\n\n";
        $message.= $this->indentCalls($this->callsThatWereMade);

        return $message;
    }

    private function indentCalls(array $calls)
    {
        return implode("\n", array_map(function ($call) {
            return $this->indent(CallFormatter::format($call));
        }, $calls));
    }

    private function indent($string)
    {
        return implode("\n", array_map(function ($line) {
            return $line != "" ? "    ".$line : $line;
        }, explode("\n", $string)));
    }
}
