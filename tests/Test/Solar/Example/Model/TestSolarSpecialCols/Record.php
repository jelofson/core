<?php
/**
 * 
 * Concrete class test.
 * 
 */
class Test_Solar_Example_Model_TestSolarSpecialCols_Record extends Solar_Test {
    
    /**
     * 
     * Configuration values.
     * 
     * @var array
     * 
     */
    protected $_Test_Solar_Example_Model_TestSolarSpecialCols_Record = array(
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
    public function __construct($config = null)
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
        $obj = Solar::factory('Solar_Example_Model_TestSolarSpecialCols_Record');
        $this->assertInstance($obj, 'Solar_Example_Model_TestSolarSpecialCols_Record');
    }
    
    /**
     * 
     * Test -- Magic getter for record properties; automatically calls __getColName() methods when they exist.
     * 
     */
    public function test__get()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Checks if a data key is set.
     * 
     */
    public function test__isset()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Magic setter for record properties; automatically calls __setColName() methods when they exist.
     * 
     */
    public function test__set()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Sets a key in the data to null.
     * 
     */
    public function test__unset()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Countable: how many keys are there?
     * 
     */
    public function testCount()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Iterator: get the current value for the array pointer.
     * 
     */
    public function testCurrent()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Deletes this record from the database.
     * 
     */
    public function testDelete()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Filter the data.
     * 
     */
    public function testFilter()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- 
     * 
     */
    public function testForm()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns the validation failure message for one or more properties.
     * 
     */
    public function testGetInvalid()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns the model from which the data originates.
     * 
     */
    public function testGetModel()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns the current page number for a named relation.
     * 
     */
    public function testGetRelatedPage()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns the status of this record.
     * 
     */
    public function testGetStatus()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Iterator: get the current key for the array pointer.
     * 
     */
    public function testKey()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Loads the struct with data from an array or another struct.
     * 
     */
    public function testLoad()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Iterator: move to the next position.
     * 
     */
    public function testNext()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- ArrayAccess: does the requested key exist?
     * 
     */
    public function testOffsetExists()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- ArrayAccess: get a key value.
     * 
     */
    public function testOffsetGet()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- ArrayAccess: set a key value.
     * 
     */
    public function testOffsetSet()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- ArrayAccess: unset a key.
     * 
     */
    public function testOffsetUnset()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Refreshes data for this record from the database.
     * 
     */
    public function testRefresh()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Iterator: move to the first position.
     * 
     */
    public function testRewind()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Saves this record to the database, inserting or updating as needed.
     * 
     */
    public function testSave()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Forces one property to be "invalid" and sets a validation failure message for it.
     * 
     */
    public function testSetInvalid()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- 
     * 
     */
    public function testSetInvalids()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Injects the model from which the data originates.
     * 
     */
    public function testSetModel()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Sets the page number for a named relation, so that only records from that page are loaded.
     * 
     */
    public function testSetRelatedPage()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Forces the status of this record.
     * 
     */
    public function testSetStatus()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Converts the properties of this model Record or Collection to an array, including related models stored in properties.
     * 
     */
    public function testToArray()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Iterator: is the current position valid?
     * 
     */
    public function testValid()
    {
        $this->todo('stub');
    }

}