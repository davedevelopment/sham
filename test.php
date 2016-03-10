<?php

namespace Foo {
    class User {}
}

namespace {

    require "vendor/autoload.php";
    require "src/Sham/TestDouble/Stub.php";

    use Sham\TestDouble\StubTrait;
    use Sham\TestDouble\Api\Stub;

    class Thing 
    {
        public function foo(int $int, Foo\User $user) : Foo\User
        {
            return new User;
        }

        public function fooString(int $int, Foo\User $user) : string
        {
            return "bas";
        }

        public function fooStdclass(int $int, Foo\User $user) : stdclass
        {
            return "bas";
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

            $returnType = "";

            if ($method->hasReturnType()) {
                $asString = (string) $method->getReturnType();
                if (!$method->getReturnType()->isBuiltin()) {
                    $asString = "\\".$asString;
                }
                $returnType = ": $asString";
            }

            $methods.= <<<EOS
            public function {$method->getName()}($paramsString)$returnType
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

    $stub->stub("foo")->toReturn(new Foo\User());
     
    if (!$stub instanceof Thing) {
        throw new \Exception("STUB WOULD NOT SATISFY TYPE HINT");
    }

    if (! $stub->foo( 123, new Foo\User) instanceof Foo\User) {
        throw new \Exception("STUB DID NOT WORK");
    }

    var_dump('GOOD');
}
