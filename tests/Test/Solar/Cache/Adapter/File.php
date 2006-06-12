<?php
class Test_Solar_Cache_Adapter_File extends Solar_Test {
    
    protected $_cache;
    
    protected $_config = array(
        'adapter' => 'Solar_Cache_Adapter_File',
        'config'  => array(
            'path'   => '/tmp/Solar_Cache_Testing',
            'life'   => 7, // 7 seconds
        ),
    );
    
    public function setup()
    {
        // create a Solar_Cache with the Solar_Cache_File adapter
        $this->_cache = Solar::factory('Solar_Cache', $this->_config);
        
        // remove all previous entries
        $this->_cache->deleteAll();
    }
    
    public function testDelete()
    {
        $id = 'coyote';
        $data = 'Wile E. Coyote';
        
        // data has not been stored yet
        $this->assertFalse($this->_cache->fetch($id));
        
        // store it
        $this->assertTrue($this->_cache->save($id, $data));
        
        // and we should be able to fetch now
        $this->assertSame($this->_cache->fetch($id), $data);
        
        // delete it, should not be able to fetch again
        $this->_cache->delete($id);
        $this->assertFalse($this->_cache->fetch($id));
    }
    
    public function testDeleteAll()
    {
        $list = array(1, 2, 'five');
        $data = 'Wile E. Coyote';
        
        foreach ($list as $id) {
            // data has not been stored yet
            $this->assertFalse($this->_cache->fetch($id));
            // so store some data
            $this->assertTrue($this->_cache->save($id, $data));
            // and we should be able to fetch now
            $this->assertSame($this->_cache->fetch($id), $data);
        }
        
        // delete everything
        $this->_cache->deleteAll();
        
        // should not be able to fetch again
        foreach ($list as $id) {
            $this->assertFalse($this->_cache->fetch($id));
        }
    }
    
    public function testFetch()
    {
        $id = 'coyote';
        $data = 'Wile E. Coyote';
        
        // data has not been stored yet
        $this->assertFalse($this->_cache->fetch($id));
        
        // store it
        $this->assertTrue($this->_cache->save($id, $data));
        
        // and we should be able to fetch now
        $this->assertSame($this->_cache->fetch($id), $data);
        
        // deactivate then try to fetch
        $this->_cache->setActive(false);
        $this->assertFalse($this->_cache->isActive());
        $this->assertFalse($this->_cache->fetch($id));
        
        // re-activate then try to fetch
        $this->_cache->setActive(true);
        $this->assertTrue($this->_cache->isActive());
        $this->assertSame($this->_cache->fetch($id), $data);
    }
    
    public function testGetLife()
    {
        $id = 'coyote';
        $data = 'Wile E. Coyote';
        
        // configured from setup
        $this->assertSame($this->_cache->getLife(), $this->_config['config']['life']);
        
        // store something
        $this->assertTrue($this->_cache->save($id, $data));
        $this->assertSame($this->_cache->fetch($id), $data);
        
        // wait until just before the lifetime,
        // we should still get data
        sleep($this->_cache->getLife() - 1);
        $this->assertSame($this->_cache->fetch($id), $data);
        
        // wait until just after the lifetime,
        // we should get nothing
        sleep(2);
        $this->assertFalse($this->_cache->fetch($id));
    }
    
    public function testIsActive()
    {
        // should be active by default
        $this->assertTrue($this->_cache->isActive());
        
        // turn it off
        $this->_cache->setActive(false);
        $this->assertFalse($this->_cache->isActive());
        
        // turn it back on
        $this->_cache->setActive(true);
        $this->assertTrue($this->_cache->isActive());
    }
    
    public function testSave_Array()
    {
        $id = 'coyote';
        $data = array(
            'name' => 'Wile E.',
            'type' => 'Coyote',
            'eats' => 'Roadrunner',
            'flag' => 'Not again!',
        );
        $this->assertTrue($this->_cache->save($id, $data));
        $this->assertSame($this->_cache->fetch($id), $data);
    }
    
    public function testSave_Object()
    {
        $id = 'coyote';
        $data = Solar::factory('Solar_Test_Example');
        $this->assertTrue($this->_cache->save($id, $data));
        $this->assertEquals($this->_cache->fetch($id), $data);
    }
    
    public function testSave_String()
    {
        $id = 'coyote';
        $data = 'Wile E. Coyote';
        $this->assertTrue($this->_cache->save($id, $data));
        $this->assertSame($this->_cache->fetch($id), $data);
    }
}
?>