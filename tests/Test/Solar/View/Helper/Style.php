<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/Helper.php';

class Test_Solar_View_Helper_Style extends Test_Solar_View_Helper {
    
    public function testStyle()
    {
        $actual = $this->_view->style('styles.css');
        $expect = '<style type="text/css" media="screen">'
                . '@import url("/public/styles.css");</style>';
        $this->assertSame($actual, $expect);
    }
}
?>