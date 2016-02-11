<?php

require "vendor/autoload.php";
require "src/Sham/TestDouble/Stub.php";

use Sham\TestDouble\StubTrait;
use Sham\TestDouble\Api\Stub;

class User {}
class Thing 
{
    public function foo(int $int, User $user) #: void
    {
        return "baz";
    }
}

function stub($class)
{
    $rfc = new \ReflectionClass($class);
    $methods = "";

    foreach ($rfc->getMethods() as $method) {

        $paramArr = [];
        $params = $method->getParameters();
        foreach( $params as $param) {
            $paramString = '';
            if( $param->hasType()){
                $paramString .= $param->getType() . " ";
            }
            $paramString .= '$' . $param->getName();
            $paramArr[] = $paramString;

        }
        $paramsString = implode( ', ', $paramArr );

            var_dump( $paramsString );
        $methods.= <<<EOS
        public function {$method->getName()}($paramsString)
        {
            return call_user_func_array([\$this, '__call'], ['{$method->getName()}', func_get_args()]);
        }
EOS;

    }

    $stubClass = <<<EOS

    use Sham\TestDouble\StubTrait;
    use Sham\TestDouble\Api\Stub;

    return new class() extends $class implements Stub
    {
        use StubTrait;

        $methods
    };
EOS;

    return eval($stubClass);
}

$stub = stub(Thing::class);

$stub->stub("foo")->toReturn("bar");
 
if (!$stub instanceof Thing) {
    throw new \Exception("STUB WOULD NOT SATISFY TYPE HINT");
}

if ($stub->foo( 123, new User) !== "bar") {
    throw new \Exception("STUB DID NOT WORK");
}
