<?php
/**
 * 
 * Factory class for SQL connections.
 * 
 * @category Solar
 * 
 * @package Solar_Sql
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id$
 * 
 */
class Solar_Sql extends Solar_Base {
    
    /**
     * 
     * User-provided configuration.
     * 
     * Keys are ...
     * 
     * `adapter`
     * : (string) The class to factory, for example 'Solar_Sql_Adapter_Mysql'.
     * 
     * @var array
     * 
     */
    protected $_Solar_Sql = array(
        'adapter' => 'Solar_Sql_Adapter_Sqlite',
    );
    
    /**
     * 
     * Factory method to create SQL adapter objects.
     * 
     * @return Solar_Sql_Adapter
     * 
     */
    public function solarFactory()
    {
        // bring in the config and get the adapter class.
        $config = $this->_config;
        $class = $config['adapter'];
        unset($config['adapter']);
        
        // return the factoried adapter object
        return Solar::factory($class, $config);
    }
}
