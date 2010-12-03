<?php
/**
 * 
 * Abstract Authentication Logout Protocol.
 * 
 * @category Solar
 * 
 * @package Solar_Auth
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id: Adapter.php 4533 2010-04-23 16:35:15Z pmjones $
 * 
 */
abstract class Solar_Auth_Logout_Adapter extends Solar_Base {
    
    /**
     * 
     * Details on the current request.
     * 
     * @var Solar_Request
     * 
     */
    protected $_request;
    
    /**
     * 
     * Post-construction tasks to complete object construction.
     * 
     * @return void
     * 
     */
    protected function _postConstruct()
    {
        parent::_postConstruct();
        
        // get the current request environment
        $this->_request = Solar_Registry::get('request');
    }
    
    public function getProtocol()
    {
        return $this;
    }
    
    /**
     * 
     * Tells if the current page load appears to be the result of
     * an attempt to log out.
     * 
     * @return bool
     * 
     */
    abstract public function isLogoutRequest();
    
    /**
     * 
     * Determine the location to redirect to after logout
     * 
     * @return string|null The url to redirect to or null if no redirect
     * 
     */
    abstract function getLogoutRedirect();
    
}