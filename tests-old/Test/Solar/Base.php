<?php

class Test_Solar_Base extends Solar_Test {
    
    public function test__construct_1()
    {
        // note that the boolean false cancels config overrides.
        $example = Solar::factory('Solar_Test_Example', false);
        $expect = array(
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        );
        $this->assertProperty($example, '_config', 'same', $expect);
    }
    
    public function test__construct_2()
    {
        // does the class merge Solar.config.php overrides?
        $example = Solar::factory('Solar_Test_Example');
        $expect = array(
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gaz',
        );
        $this->assertProperty($example, '_config', 'same', $expect);
        
    }
    
    public function test__construct_3()
    {
        // does the class merge internal config with Solar.config.php
        // and the factory-time config?
        $config = array('zim' => 'irk');
        $example = Solar::factory('Solar_Test_Example', $config);
        $expect = array(
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'irk',
        );
        $this->assertProperty($example, '_config', 'same', $expect);
    }
    
    public function test_exception()
    {
        // throw a specific exception for the class
        $example = Solar::factory('Solar_Test_Example');
        try {
            $example->classSpecificException();
            $this->fail('Expected exception not thrown.');
        } catch (Exception $e) {
            $this->assertInstance($e, 'Solar_Test_Example_Exception_CustomCondition');
        }
        
        // fall back to a specific exception for Solar as a whole
        $example = Solar::factory('Solar_Test_Example');
        try {
            $example->solarSpecificException();
            $this->fail('Expected exception not thrown.');
        } catch (Exception $e) {
            $this->assertInstance($e, 'Solar_Exception_FileNotFound');
        }
        
        // fall back to a generic exception for the class
        $example = Solar::factory('Solar_Test_Example');
        try {
            $example->classGenericException();
            $this->fail('Expected exception not thrown.');
        } catch (Exception $e) {
            $this->assertInstance($e, 'Solar_Test_Example_Exception');
        }
        
        // fall back to a generic exception for Solar as a whole.
        try {
            $example->solarGenericException();
            $this->fail('Expected exception not thrown.');
        } catch (Exception $e) {
            $this->assertInstance($e, 'Solar_Exception');
        }
    }
    
    public function testLocale()
    {
        $example = Solar::factory('Solar_Test_Example');
        
        // English
        Solar::setLocale('en_US');
        $this->assertSame(
            $example->locale('HELLO_WORLD'),
            'hello world'
        );
        
        // Italian
        Solar::setLocale('it_IT');
        $this->assertSame(
            $example->locale('HELLO_WORLD'),
            'ciao mondo'
        );
        
        // Espa�ol
        Solar::setLocale('es_ES');
        $this->assertSame(
            $example->locale('HELLO_WORLD'),
            'hola mundo'
        );
        
        // Language code not available, shows key instead of string.
        Solar::setLocale('xx_XX');
        $this->assertSame(
            $example->locale('HELLO_WORLD'),
            'HELLO_WORLD'
        );
        
        // Language code available, but key not in class translations.
        // Falls back to Solar-wide translations.
        Solar::setLocale('en_US');
        $this->assertSame(
            $example->locale('SUCCESS_FORM'),
            'Saved.'
        );
    }
}
?>