<?php
require_once(dirname(__FILE__)."/../src/com/Eobject.php");
class TestEobject extends PHPUnit_Framework_TestCase {
    public function test_Eobject_run(){
        $e =new  Eobject('127.0.0.1');
        $ret = $e->run('ls -l');
        $this->assertEquals(0,  $ret[0]);
        $this->assertGreaterThan(0, count($ret[1]));
        
        $ret = $e->run('touch ~/aa');
        $this->assertEquals(0,  $ret[0]);
        $this->assertEquals(0, count($ret[1]));

        $ret = $e->rcp('127.0.0.1:~/aa', '/tmp/');
        $this->assertEquals(0,  $ret[0]);
        $this->assertEquals(0, count($ret[1]));
    }

    public function test_get() {
        $Eo = new Eobject('127.0.0.1');
        $this->assertEquals('127.0.0.1', $Eo->host );
        $this->assertEquals(null, $Eo->aaaa);
    }

    public function test_set() {
        $Eo = new Eobject('127.0.0.1');
        $Eo->host = "127.0.0.2";
        $this->assertEquals('127.0.0.2', $Eo->host );

        $Eo->aaa = "aaa";
        $this->assertEquals('aaa', $Eo->aaa );
    }

    public function test_execute() {
        $Eo = new Eobject('127.0.0.1');
        $ret = $Eo->execute('ls -Rrt', True);
        $this->assertEquals(0,  $ret[0]);
        $this->assertGreaterThan(15, count($ret[1]));

        try {
            $ret = $Eo->execute('exit -1;', True);
        }
        catch (ExecuteFail $e) {
            $e->__toString();
            $this->assertEquals( -1, $e->getcode());
        }
    }

    public function test_rcp() {
        $Eo = new Eobject('127.0.0.1');
        $Eo->execute("rm -rf /tmp/aa && mkdir -p unittest/data/ && touch unittest/data/aa");
        $ret = $Eo->rcp("unittest/data/aa","/tmp");
        $this->assertEquals(0,  $ret[0]);
        $Eo->execute("rm -rf ~/aa && mkdir -p unittest/data/ && touch unittest/data/aa");
        $ret = $Eo->rcp("unittest/data/aa","~/");
        $this->assertEquals(0,  $ret[0]);
        $Eo->execute("rm -rf unittest/data");

        $Eo->execute("rm -rf ~/aa && mkdir -p unittest/data/ && touch unittest/data/aa");
        $ret = $Eo->rcp("unittest/data/aa","127.0.0.1:~/", True);
        $this->assertEquals(0,  $ret[0]);
        $Eo->execute("rm -rf unittest/data");
        
        $Eo->execute("rm -rf ~/aa && mkdir -p unittest/data/ && touch unittest/data/aa && touch /tmp/aa");
        $ret = $Eo->rcp("/tmp/aa","127.0.0.1:~/", True);
        $this->assertEquals(0,  $ret[0]);
        $Eo->execute("rm -rf unittest/data");
    }

}
?>
