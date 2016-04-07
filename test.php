<?php

namespace Foo {
    class User {
        const SOME_INT = 12;
    }
}

namespace Dave {

    require "vendor/autoload.php";
    require "src/Sham/TestDouble/Stub.php";

    use Foo\User;
    use Sham\TestDouble\StubTrait;
    use Sham\TestDouble\Api\Stub;

    class Thing 
    {
        const DEFAULT_INT = 32;

        public function foo(int $int = 123, \Foo\User $user = null, string $string = "12312312", bool $bool = true, float $float = 1.2, array $array = array()) : \Foo\User
        {
            return new User;
        }

        public function fooString(int $int = self::DEFAULT_INT, int $int2 = Thing::DEFAULT_INT, int $int3 = User::SOME_INT) : string
        {
            return "bas";
        }

        public function fooStdclass(int $int = PHP_INT_SIZE, \Foo\User $user) : stdclass
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

                if ($param->isDefaultValueAvailable()) {
                    if ($param->isDefaultValueConstant()) {
                        $defaultValue = $param->getDefaultValueConstantName();

                        if (substr($defaultValue, 0, 6) != 'self::') {
                            $defaultValue = "\\".$defaultValue;
                        }

                    } else {
                        $defaultValue = var_export($param->getDefaultValue(), true);
                    }

                    $paramString .= " = " . $defaultValue;
                }

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

    $stub->stub("foo")->toReturn(new \Foo\User());

    if (!$stub instanceof Thing) {
        throw new \Exception("STUB WOULD NOT SATISFY TYPE HINT");
    }

    if (! $stub->foo( 123, new \Foo\User) instanceof \Foo\User) {
        throw new \Exception("STUB DID NOT WORK");
    }

    var_dump('GOOD');
}
