<?php
require_once(dirname(__FILE__)."/../src/com/file.php");
class TestfileSuite extends PHPUnit_Framework_TestCase {
    public function test_host() {
        $fp = new file($filename="./data/aaaa.conf", $host="127.0.0.1", $init_from="", $auto_bak=False);
        $this->assertEquals($fp->host(), '127.0.0.1');
    }

    public function test_reset() {
        $fp = new file($filename="./data/aaaa.conf", $host="127.0.0.1", $init_from="", $auto_bak=False);
        $this->assertEquals($fp->reset(), True);

        $fp1 = new file($filename="./data/aaaa.conf", $host="127.0.0.1", $init_from="./data/bbb.conf", $auto_bak=False);
        $this->assertEquals($fp1->reset(), True);
         
        $fp2 = new file($filename="./data/aaaa.conf", $host="127.0.0.1", $init_from="./data/bbb.conf", $auto_bak=True);
        $this->assertEquals($fp2->reset(), True);
        
        $fp3 = new file($filename="./data/aaaa.conf", $host="127.0.0.1", $init_from="./aaaa/bbb.conf", $auto_bak=True);
        $this->assertEquals($fp3->reset(), True);
        $this->assertEquals($fp3->reset(), True);
        $this->assertEquals($fp3->reset(), True);
    }
    
    public function test_fullname() {
        $fp = new file($filename=dirname(__FILE__)."/data/aaaa.conf", $host="127.0.0.1", $init_from="./data/bbb.conf", $auto_bak=True);
        $this->assertEquals($fp->fullname(), dirname(__FILE__)."/data/aaaa.conf");
        $this->assertEquals($fp->pathname(), dirname(__FILE__)."/data");
        $this->assertEquals($fp->basename(), "aaaa.conf");
        exec('rm -rf '.dirname(__FILE__).'/data');

        $fp = new file($filename="./data/aaaa.conf", $host="127.0.0.1", $init_from="./data/bbb.conf", $auto_bak=True);
        $this->assertEquals($fp->fullname(), getcwd()."/data/aaaa.conf");
        $this->assertEquals($fp->pathname(), getcwd()."/data");
        $this->assertEquals($fp->basename(), "aaaa.conf");
        exec('rm -rf '.getcwd().'/data');
        
        $fp = new file($filename="/home/dongjiang.dongj/tools/../tools/tmp/aaaa.conf", $host="127.0.0.1", $init_from="./data/bbb.conf", $auto_bak=True);
        $this->assertTrue(in_array($fp->fullname(), array("/home/dongjiang.dongj/tools/tmp/aaaa.conf", "/home/dongjiang.dongj/tools/../tools/tmp/aaaa.conf")));
        $this->assertTrue(in_array($fp->pathname(), array("/home/dongjiang.dongj/tools/tmp", "/home/dongjiang.dongj/tools/../tools/tmp")));
        $this->assertEquals($fp->basename(), "aaaa.conf");
        exec('rm -rf /home/dongjiang.dongj/tools/tmp/');
    }

    public function test_size() {
        exec('rm -rf '.dirname(__FILE__).'/aa');
        exec('touch '.dirname(__FILE__).'/aa');
        $fp = new file($filename=dirname(__FILE__).'/aa', $host="127.0.0.1", $auto_bak=True);
        $this->assertEquals(0, $fp->size());
        exec('echo test1 >> '.dirname(__FILE__).'/aa');
        exec('echo test2 >> '.dirname(__FILE__).'/aa');
        $this->assertEquals(12, $fp->size());
        exec('rm -rf '.dirname(__FILE__).'/aa');
        $this->assertEquals(0, $fp->size());
         
    }
    
    function test_cmd() {
        exec('rm -rf aaa bbb');
        $fp = new file($filename=dirname(__FILE__).'/aaa',$host="127.0.0.1", $auto_bak=False);
        $fp->saveto("");
        $this->assertFileExists(getcwd().'/aaa');

        $fp->saveto("bbb");
        $this->assertFileExists('bbb');
        exec('rm -rf aaa bbb');

        $this->assertEquals($fp->head(), array());
        $this->assertEquals($fp->tail(), array());

        $fp->add('aaa');
        $this->assertEquals(4,  $fp->size());

        $fp->feed('');
        $this->assertEquals(1,  $fp->size());

        exec('rm -rf '.dirname(__FILE__).'/aaa && touch '.dirname(__FILE__).'/aaa && echo "a=1" > '.dirname(__FILE__).'/aaa' );

        $line = $fp->lines();
        $this->assertEquals(1, $line);
        
        $output = $fp->grep('-c', 'a');
        var_dump($output);
        $this->assertEquals(1, $output[0]);

        $output = $fp->cat('');
        var_dump($output);
        $this->assertEquals("a=1", $output[0]);

        exec('rm -rf '.dirname(__FILE__).'/aaa && touch '.dirname(__FILE__).'/aaa' );
        $line = $fp->lines();
        $this->assertEquals(0, $line);
    
        try {
            $fp->delete();
            $this->assertEquals(1, 0);
        }catch (AbstractInterface $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }
        
        try {
            $fp->set();
            $this->assertEquals(1, 0);
        }catch (AbstractInterface $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }

        try {
            $fp->get();
            $this->assertEquals(1, 0);
        }catch (AbstractInterface $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
        }

        $fp->remove();
        $this->assertFileNotExists(dirname(__FILE__).'/aaa');
    }

    function test_InitFail() {
        try {
            throw new InitFail('test', -1);
            $this->assertEquals(1, 0);
        } 
        catch (InitFail $e) {
            $e->__toString();
            $this->assertEquals(RETTYPE::ERR, $e->getcode());
            $this->assertEquals("test", $e->getmessage());
        }
    }

    function test_md5sum() {
        $fp = new file($filename="aaa.log");
        var_dump($fp->md5sum());
        $this->assertEquals(32, strlen($fp->md5sum()));
        $this->assertEquals(True, ctype_alnum($fp->md5sum()));
        exec("rm -rf ".getcwd()."/aaa.log");
        $this->assertEquals(Null, $fp->md5sum());
    }
}
?>
