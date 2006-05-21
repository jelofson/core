<?php
/**
 * 
 * Controller action script for viewing in RSS.
 * 
 * @category Solar
 * 
 * @package Solar_App
 * 
 * @subpackage Solar_App_HelloWorld
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * 
 * @version $Id$
 * 
 */

// get the locale code, default is en_US
$this->code = $this->_query('code', 'en_US');

// reset the locale strings to the new code
Solar::setLocale($this->code);

// set the translated text
$this->text = $this->locale('TEXT_HELLO_WORLD');

// turn off the site layout
$this->_layout = false;

// done!
?>