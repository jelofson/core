<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/Helper.php';

class Test_Solar_View_Helper_Timestamp extends Test_Solar_View_Helper {
    
    public function testTimestamp_string()
    {
        $string = 'Nov 7, 1970, 12:34:56';
        $actual = $this->_view->timestamp($string);
        $expect = '1970-11-07T12:34:56';
        $this->assertSame($actual, $expect);
    }
    
    public function testTimestamp_int()
    {
        $int = strtotime('Nov 7, 1970 12:34:56pm');
        $actual = $this->_view->timestamp($int);
        $expect = '1970-11-07T12:34:56';
        $this->assertSame($actual, $expect);
    }
    
    public function testTimestamp_reformat()
    {
        $string = 'Nov 7, 1970, 11:45pm';
        $actual = $this->_view->timestamp($string, 'U');
        $expect = strtotime($string);
        $this->assertEquals($actual, $expect);
    }
    
    public function testTimestamp_configFormat()
    {
        $helper = $this->_view->newHelper('timestamp', array('format' => 'U'));
        $string = 'Nov 7, 1970, 12:34:56 pm';
        $actual = $helper->timestamp($string);
        $expect = strtotime($string);
        $this->assertEquals($actual, $expect);
    }
}
?>