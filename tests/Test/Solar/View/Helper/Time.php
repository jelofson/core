<?php
/**
 * 
 * Concrete class test.
 * 
 */
class Test_Solar_View_Helper_Time extends Solar_Test {
    
    /**
     * 
     * Configuration values.
     * 
     * @var array
     * 
     */
    protected $_Test_Solar_View_Helper_Time = array(
    );
    
    // -----------------------------------------------------------------
    // 
    // Support methods.
    // 
    // -----------------------------------------------------------------
    
    /**
     * 
     * Constructor.
     * 
     * @param array $config User-defined configuration parameters.
     * 
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }
    
    /**
     * 
     * Destructor; runs after all methods are complete.
     * 
     * @param array $config User-defined configuration parameters.
     * 
     */
    public function __destruct()
    {
        parent::__destruct();
    }
    
    /**
     * 
     * Setup; runs before each test method.
     * 
     */
    public function setup()
    {
        parent::setup();
    }
    
    /**
     * 
     * Setup; runs after each test method.
     * 
     */
    public function teardown()
    {
        parent::teardown();
    }
    
    // -----------------------------------------------------------------
    // 
    // Test methods.
    // 
    // -----------------------------------------------------------------
    
    /**
     * 
     * Test -- Constructor.
     * 
     */
    public function test__construct()
    {
        $obj = Solar::factory('Solar_View_Helper_Time');
        $this->assertInstance($obj, 'Solar_View_Helper_Time');
    }
    
    /**
     * 
     * Test -- Outputs a formatted time using [[php::date() | ]] format codes.
     * 
     */
    public function testTime()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Outputs a formatted timestamp using [[php::date() | ]] format codes.
     * 
     */
    public function testTimestamp()
    {
        $this->todo('stub');
    }


}
