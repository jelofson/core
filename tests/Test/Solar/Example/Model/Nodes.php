<?php
/**
 * 
 * Concrete class test.
 * 
 */
class Test_Solar_Example_Model_Nodes extends Solar_Test {
    
    /**
     * 
     * Configuration values.
     * 
     * @var array
     * 
     */
    protected $_Test_Solar_Example_Model_Nodes = array(
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
        $obj = Solar::factory('Solar_Example_Model_Nodes');
        $this->assertInstance($obj, 'Solar_Example_Model_Nodes');
    }
    
    /**
     * 
     * Test -- Magic call implements "fetchOneBy...()" and "fetchAllBy...()" for columns listed in the method name.
     * 
     */
    public function test__call()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Read-only access to protected model properties.
     * 
     */
    public function test__get()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Fetches count and pages of available records.
     * 
     */
    public function testCountPages()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Counts the number of records in a related model for a given record.
     * 
     */
    public function testCountPagesRelated()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Deletes rows from the model table.
     * 
     */
    public function testDelete()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Fetches a record or collection by primary key value(s).
     * 
     */
    public function testFetch()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Fetches all records by arbitrary parameters.
     * 
     */
    public function testFetchAll()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- The same as fetchAll(), except the record collection is keyed on the first column of the results (instead of being a strictly sequential array.)  Recognized parameters for the fetch are:  `cols` : (string|array) Return only these columns.
     * 
     */
    public function testFetchAssoc()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Fetches a sequential array of values from the model, using only the first column of the results.
     * 
     */
    public function testFetchCol()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns a new record with default values.
     * 
     */
    public function testFetchNew()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Fetches one record by arbitrary parameters.
     * 
     */
    public function testFetchOne()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Fetches an array of key-value pairs from the model, where the first column is the key and the second column is the value.
     * 
     */
    public function testFetchPairs()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- 
     * 
     */
    public function testFetchRelatedArray()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Given a record, fetches a related record or collection for a named relationship.
     * 
     */
    public function testFetchRelatedObject()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Fetches a single value from the model (i.e., the first column of the  first record of the returned page set).
     * 
     */
    public function testFetchValue()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Gets the number of records per page.
     * 
     */
    public function testGetPaging()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- 
     * 
     */
    public function testGetRelatedModel()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Inserts one row to the model table.
     * 
     */
    public function testInsert()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns the appropriate collection object for this model.
     * 
     */
    public function testNewCollection()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns the appropriate record object for an inheritance model.
     * 
     */
    public function testNewRecord()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns a new Solar_Sql_Select tool, with the proper SQL object injected automatically, and with eager "to-one" associations joined.
     * 
     */
    public function testNewSelect()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Returns a new Solar_Sql_Select tool for selecting related records.
     * 
     */
    public function testNewSelectRelated()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Serializes data values in-place based on $this->_serialize_cols.
     * 
     */
    public function testSerializeCols()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Sets the number of records per page.
     * 
     */
    public function testSetPaging()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Unerializes data values in-place based on $this->_serialize_cols.
     * 
     */
    public function testUnserializeCols()
    {
        $this->todo('stub');
    }
    
    /**
     * 
     * Test -- Updates rows in the model table.
     * 
     */
    public function testUpdate()
    {
        $this->todo('stub');
    }

}
