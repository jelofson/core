<?php
/**
 * Make sure Solar_Base is loaded even before Solar::start() is called.
 * DO NOT use spl_autoload() in this case, it causes segfaults from recursion
 * in some environments.
 */
if (! class_exists('Solar_Base', false)) {
    require dirname(__FILE__) . DIRECTORY_SEPARATOR
          . 'Solar' . DIRECTORY_SEPARATOR . 'Base.php';
}

/**
 * Make sure Solar_File is loaded even before Solar::start() is called.
 * DO NOT use spl_autoload() in this case, it causes segfaults from recursion
 * in some environments.
 */
if (! class_exists('Solar_File', false)) {
    require dirname(__FILE__) . DIRECTORY_SEPARATOR
          . 'Solar' . DIRECTORY_SEPARATOR . 'File.php';
}

/**
 * Register Solar::autoload() with SPL.
 */
spl_autoload_register(array('Solar', 'autoload'));

/**
 * 
 * The Solar arch-class provides static methods needed throughout the
 * framework environment.
 * 
 * @category Solar
 * 
 * @package Solar
 * 
 * @author Paul M. Jones <pmjones@solarphp.net>
 * 
 * @version $Id$
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * Copyright (c) 2005-2007, Paul M. Jones.  All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 
 * * Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 * 
 * * Redistributions in binary form must reproduce the above
 *   copyright notice, this list of conditions and the following
 *   disclaimer in the documentation and/or other materials provided
 *   with the distribution.
 * 
 * * Neither the name of the Solar project nor the names of its
 *   contributors may be used to endorse or promote products derived
 *   from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 */
class Solar {
    
    /**
     * 
     * Default config values for the Solar arch-class.
     * 
     * Keys are:
     * 
     * `ini_set`
     * : (array) An array of key-value pairs where the key is an
     * [[php::ini_set | ]] key, and the value is the value for that setting.
     * 
     * `registry_set`
     * : (array) An array of key-value pairs to use in pre-setting registry
     *   objects.  The key is a registry name to use.  The value is either
     *   a string class name, or is a sequential array where element 0 is
     *   a string class name and element 1 is a configuration array for that
     *   class.  Cf. [[Solar_Registry::set()]].
     * 
     * `start`
     * : (array) Run these scripts at the end of Solar::start().
     * 
     * `stop`
     * : (array) Run these scripts in Solar::stop().
     * 
     * @var array
     * 
     */
    protected static $_Solar = array(
        'ini_set'      => array(),
        'registry_set' => array(),
        'start'        => array(),
        'stop'         => array(),
    );
    
    /**
     * 
     * The values read in from the configuration file.
     * 
     * @var array
     * 
     */
    public static $config = array();
    
    /**
     * 
     * Parent hierarchy for all classes.
     * 
     * We keep track of this so configs, locale strings, etc. can be
     * inherited properly from parent classes.
     * 
     * Although this property is public, you generally shouldn't need
     * to manipulate it in any way.
     * 
     * @var array
     * 
     */
    public static $parents = array();
    
    /**
     * 
     * Status flag (whether Solar has started or not).
     * 
     * @var bool
     * 
     */
    protected static $_status = false;
    
    /**
     * 
     * Constructor is disabled to enforce a singleton pattern.
     * 
     */
    final private function __construct() {}
    
    /**
     * 
     * Starts Solar: loads configuration values and and sets up the environment.
     * 
     * Note that this method is overloaded; you can pass in different
     * value types for the $config parameter.
     * 
     * * `null|false` -- This will not load any new configuration values;
     *   you will get only the default [[Solar::$config]] array values defined
     *   in the Solar class.
     * 
     * * `string` -- The string is treated as a path to a Solar.config.php
     *   file; the return value from that file will be used for [[Solar::$config]].
     * 
     * * `array` -- This will use the passed array for the [[Solar::$config]]
     *   values.
     * 
     * * `object` -- The passed object will be cast as an array, and those
     *   values will be used for [[Solar::$config]].
     * 
     * Here are some examples of starting with alternative configuration parameters:
     * 
     * {{code: php
     *     require_once 'Solar.php';
     * 
     *     // don't load any config values at all
     *     Solar::start();
     * 
     *     // point to a config file (which returns an array)
     *     Solar::start('/path/to/another/config.php');
     * 
     *     // use an array as the config source
     *     $config = array(
     *         'Solar' => array(
     *             'ini_set' => array(
     *                 'error_reporting' => E_ALL,
     *             ),
     *         ),
     *     );
     *     Solar::start($config);
     * 
     *     // use an object as the config source
     *     $config = new StdClass;
     *     $config->Solar = array(
     *         'ini_set' => array(
     *             'error_reporting' => E_ALL,
     *         ),
     *     );
     *     Solar::start($config);
     * }}
     *  
     * @param mixed $config The configuration source value.
     * 
     * @return void
     * 
     * @see Solar::cleanGlobals()
     * 
     * @see Solar::fetchConfig()
     * 
     */
    public static function start($config = null)
    {
        // don't re-start if we're already running.
        if (Solar::$_status) {
            return;
        }
        
        // clear out registered globals
        if (ini_get('register_globals')) {
            Solar::cleanGlobals();
        }
        
        // fetch config values from file or other source
        Solar::$config = Solar::fetchConfig($config);
        
        // make sure we have the Solar arch-class configs
        if (empty(Solar::$config['Solar'])) {
            Solar::$config['Solar'] = Solar::$_Solar;
        } else {
            Solar::$config['Solar'] = array_merge(
                Solar::$_Solar,
                (array) Solar::$config['Solar']
            );
        }
        
        // process ini settings from config file
        $settings = Solar::config('Solar', 'ini_set', array());
        foreach ($settings as $key => $val) {
            ini_set($key, $val);
        }
        
        // auto-set registry entries
        $register = Solar::config('Solar', 'registry_set', array());
        foreach ($register as $name => $list) {
            // make sure we have the class-name and a config
            $list = array_pad((array) $list, 2, null);
            list($spec, $config) = $list;
            // register the item
            Solar_Registry::set($name, $spec, $config);
        }
        
        // make sure a locale object is registered
        if (! Solar_Registry::exists('locale')) {
            Solar_Registry::set(
                'locale',
                'Solar_Locale'
            );
        }
        
        // make sure a request-environment object is registered
        if (! Solar_Registry::exists('request')) {
            Solar_Registry::set(
                'request',
                'Solar_Request'
            );
        }
        
        // run any 'start' hook scripts
        foreach ((array) Solar::config('Solar', 'start') as $file) {
            Solar_File::load($file);
        }
        
        // and we're done!
        Solar::$_status = true;
    }
    
    /**
     * 
     * Stops Solar: runs stop scripts and cleans up the Solar environment.
     * 
     * @return void
     * 
     */
    public static function stop()
    {
        // run the user-defined stop scripts.
        foreach ((array) Solar::config('Solar', 'stop') as $file) {
            Solar_File::load($file);
        }
        
        // clean up
        Solar::$config = array();
        Solar::$parents = array();
        
        // reset the status flag, and we're done.
        Solar::$_status = false;
    }
    
    /**
     * 
     * Returns the API version for Solar.
     * 
     * @return string A PHP-standard version number.
     * 
     */
    public static function apiVersion()
    {
        return '@package_version@';
    }
    
    /**
     * 
     * Loads a class or interface file from the include_path.
     * 
     * Thanks to Robert Gonzalez  for the report leading to this method.
     * 
     * @param string $name A Solar (or other) class or interface name.
     * 
     * @return void
     * 
     * @todo Add localization for errors
     * 
     */
    public static function autoload($name)
    {
        // did we ask for a non-blank name?
        if (trim($name) == '') {
            throw Solar::exception(
                'Solar',
                'ERR_AUTOLOAD_EMPTY',
                'No class or interface named for loading',
                array('name' => $name)
            );
        }
        
        // pre-empt further searching for the named class or interface.
        // do not use autoload, because this method is registered with
        // spl_autoload already.
        if (class_exists($name, false) || interface_exists($name, false)) {
            return;
        }
        
        // convert the class name to a file path.
        $file = str_replace('_', DIRECTORY_SEPARATOR, $name) . '.php';
        
        // include the file and check for failure. we use Solar_File::load()
        // instead of require() so we can see the exception backtrace.
        Solar_File::load($file);
        
        // if the class or interface was not in the file, we have a problem.
        // do not use autoload, because this method is registered with
        // spl_autoload already.
        if (! class_exists($name, false) && ! interface_exists($name, false)) {
            throw Solar::exception(
                'Solar',
                'ERR_AUTOLOAD_FAILED',
                'Class or interface does not exist in loaded file',
                array('name' => $name, 'file' => $file)
            );
        }
    }
    
    /**
     * 
     * Convenience method to instantiate and configure an object.
     * 
     * @param string $class The class name.
     * 
     * @param array $config Additional configuration array for the class.
     * 
     * @return object A new instance of the requested class.
     * 
     */
    public static function factory($class, $config = null)
    {
        Solar::autoload($class);
        $obj = new $class($config);
        
        // is it an object factory?
        if (method_exists($obj, 'solarFactory')) {
            // return an instance from the object factory
            return $obj->solarFactory();
        }
        
        // return the object itself
        return $obj;
    }
    
    /**
     * 
     * Combination dependency-injection and service-locator method; returns
     * a dependency object as passed, or an object from the registry, or a 
     * new factory instance.
     * 
     * @param string $class The dependency object should be an instance of
     * this class. Technically, this is more a hint than a requirement, 
     * although it will be used as the class name if [[Solar::factory()]] 
     * gets called.
     * 
     * @param mixed $spec If an object, check to make sure it's an instance 
     * of $class. If a string, treat as a [[Solar_Registry::get()]] key. 
     * Otherwise, use this as a config param to [[Solar::factory()]] to 
     * create a $class object.
     * 
     * @return object The dependency object.
     * 
     */
    public static function dependency($class, $spec)
    {
        // is it an object already?
        if (is_object($spec)) {
            return $spec;
        }
        
        // check for registry objects
        if (is_string($spec)) {
            return Solar_Registry::get($spec);
        }
        
        // not an object, not in registry.
        // try to create an object with $spec as the config
        return Solar::factory($class, $spec);
    }
    
    /**
     * 
     * Safely gets a configuration group array or element value.
     * 
     * @param string $group The name of the group.
     * 
     * @param string $elem The name of the element in the group.
     * 
     * @param mixed $default If the group or element is not set, return
     * this value instead.  If this is not set and group was requested,
     * returns an empty array; if not set and an element was requested,
     * returns null.
     * 
     * @return mixed The value of the configuration group or element.
     * 
     */
    public static function config($group, $elem = null, $default = null)
    {
        // are we looking for a group or an element?
        if (is_null($elem)) {
            
            // looking for a group. if no default passed, set up an
            // empty array.
            if ($default === null) {
                $default = array();
            }
            
            // find the requested group.
            if (empty(Solar::$config[$group])) {
                return $default;
            } else {
                return Solar::$config[$group];
            }
            
        } else {
            
            // find the requested group and element.
            if (! isset(Solar::$config[$group][$elem])) {
                return $default;
            } else {
                return Solar::$config[$group][$elem];
            }
        }
    }
    
    /**
     * 
     * Generates a simple exception, but does not throw it.
     * 
     * This method attempts to automatically load an exception class
     * based on the error code, falling back to parent exceptions
     * when no specific exception classes exist.  For example, if a
     * class named 'Vendor_Example' extended from 'Vendor_Base' throws an
     * exception or error coded as 'ERR_FILE_NOT_FOUND', the method will
     * attempt to return these exception classes in this order ...
     * 
     * 1. Vendor_Example_Exception_FileNotFound (class specific)
     * 
     * 2. Vendor_Base_Exception_FileNotFound (parent specific)
     * 
     * 3. Vendor_Example_Exception (class generic)
     * 
     * 4. Vendor_Base_Exception (parent generic)
     * 
     * 5. Vendor_Exception (generic for all of vendor)
     * 
     * The final fallback is always the generic Solar_Exception class.
     * 
     * Note that this method only generates the object; it does not
     * throw the exception.
     * 
     * {{code: php
     *     $class = 'My_Example_Class';
     *     $code = 'ERR_SOMETHING_WRONG';
     *     $text = 'Something is wrong.';
     *     $info = array('foo' => 'bar');
     *     $exception = Solar::exception($class, $code, $text, $info);
     *     throw $exception;
     * }}
     * 
     * In general, you shouldn't need to use this directly in classes
     * extended from [[Class::Solar_Base]].  Instead, use
     * [[Solar_Base::_exception() | $this->_exception()]] for automated
     * picking of the right exception class from the $code, and
     * automated translation of the error message.
     * 
     * @param string|object $spec The class name (or object) that generated the exception.
     * 
     * @param mixed $code A scalar error code, generally a string.
     * 
     * @param string $text Any error message text.
     * 
     * @param array $info Additional error information in an associative
     * array.
     * 
     * @return Solar_Exception
     * 
     */
    public static function exception($spec, $code, $text = '',
        $info = array())
    {
        // is the spec an object?
        if (is_object($spec)) {
            // yes, find its class
            $class = get_class($spec);
        } else {
            // no, assume the spec is a class name
            $class = (string) $spec;
        }
        
        // drop 'ERR_' and 'EXCEPTION_' prefixes from the code
        // to get a suffix for the exception class
        $suffix = $code;
        if (substr($suffix, 0, 4) == 'ERR_') {
            $suffix = substr($suffix, 4);
        } elseif (substr($suffix, 0, 10) == 'EXCEPTION_') {
            $suffix = substr($suffix, 10);
        }
        
        // convert "STUDLY_CAP_SUFFIX" to "Studly Cap Suffix" ...
        $suffix = ucwords(strtolower(str_replace('_', ' ', $suffix)));
        
        // ... then convert to "StudlyCapSuffix"
        $suffix = str_replace(' ', '', $suffix);
        
        // build config array from params
        $config = array(
            'class' => $class,
            'code'  => $code,
            'text'  => $text,
            'info'  => (array) $info,
        );
        
        // get all parent classes, including the class itself
        $stack = Solar::parents($class, true);
        
        // add the vendor namespace, (for example, 'Solar') to the stack as a
        // final fallback, even though it's not strictly part of the
        // hierarchy, for generic vendor-wide exceptions.
        $pos = strpos($class, '_');
        if ($pos !== false) {
            $stack[] = substr($class, 0, $pos);
        }
        
        // track through class stack and look for specific exceptions
        foreach ($stack as $class) {
            try {
                $obj = Solar::factory("{$class}_Exception_$suffix", $config);
                return $obj;
            } catch (Exception $e) {
                // do nothing
            }
        }
        
        // track through class stack and look for generic exceptions
        foreach ($stack as $class) {
            try {
                $obj = Solar::factory("{$class}_Exception", $config);
                return $obj;
            } catch (Exception $e) {
                // do nothing
            }
        }
        
        // last resort: a generic Solar exception
        return Solar::factory('Solar_Exception', $config);
    }
    
    /**
     * 
     * Dumps a variable to output.
     * 
     * Essentially, this is an alias to the Solar_Debug_Var::dump()
     * method, which buffers the [[php::var_dump | ]] for a variable,
     * applies some simple formatting for readability, [[php::echo | ]]s
     * it, and prints with an optional label.  Use this for
     * debugging variables to see exactly what they contain.
     * 
     * @param mixed $var The variable to dump.
     * 
     * @param string $label A label for the dumped output.
     * 
     * @return void
     * 
     */
    public static function dump($var, $label = null)
    {
        $obj = Solar::factory('Solar_Debug_Var');
        $obj->display($var, $label);
    }
    
    /**
     * 
     * Returns an array of the parent classes for a given class.
     * 
     * Parents in "reverse" order ... element 0 is the immediate parent,
     * element 1 the grandparent, etc.
     * 
     * @param string|object $spec The class or object to find parents
     * for.
     * 
     * @param bool $include_class If true, the class name is element 0,
     * the parent is element 1, the grandparent is element 2, etc.
     * 
     * @return array
     * 
     */
    public static function parents($spec, $include_class = false)
    {
        if (is_object($spec)) {
            $class = get_class($spec);
        } else {
            $class = $spec;
        }
        
        // do we need to load the parent stack?
        if (empty(Solar::$parents[$class])) {
            // get the stack of classes leading to this one
            Solar::$parents[$class] = array();
            $parent = $class;
            while ($parent = get_parent_class($parent)) {
                Solar::$parents[$class][] = $parent;
            }
        }
        
        // get the parent stack
        $stack = Solar::$parents[$class];
        
        // add the class itself?
        if ($include_class) {
            array_unshift($stack, $class);
        }
        
        // done
        return $stack;
    }
    
    /**
     * 
     * Cleans the global scope of all variables that are found in other
     * super-globals.
     * 
     * This code originally from Richard Heyes and Stefan Esser.
     * 
     * @return void
     * 
     */
    public function cleanGlobals()
    {
        $list = array(
            'GLOBALS',
            '_POST',
            '_GET',
            '_COOKIE',
            '_REQUEST',
            '_SERVER',
            '_ENV',
            '_FILES',
        );
        
        // Create a list of all of the keys from the super-global values.
        // Use array_keys() here to preserve key integrity.
        $keys = array_merge(
            array_keys($_ENV),
            array_keys($_GET),
            array_keys($_POST),
            array_keys($_COOKIE),
            array_keys($_SERVER),
            array_keys($_FILES),
            // $_SESSION is null if you have not started the session yet.
            // This insures that a check is performed regardless.
            isset($_SESSION) && is_array($_SESSION) ? array_keys($_SESSION) : array()
        );
        
        // Unset the globals.
        foreach ($keys as $key) {
            if (isset($GLOBALS[$key]) && ! in_array($key, $list)) {
                unset($GLOBALS[$key]);
            }
        }
    }
    
    /**
     * 
     * Fetches config file values.
     * 
     * Note that this method is overloaded by the variable type of $spec ...
     * 
     * * `null|false` (or empty) -- This will not load any new configuration
     *   values; you will get only the default [[Solar::$config]] array values
     *   defined in the Solar class.
     * 
     * * `string` -- The string is treated as a path to a Solar.config.php
     *   file; the return value from that file will be used for [[Solar::$config]].
     * 
     * * `array` -- This will use the passed array for the [[Solar::$config]]
     *   values.
     * 
     * * `object` -- The passed object will be cast as an array, and those
     *   values will be used for [[Solar::$config]].
     * 
     * @param mixed $spec A config specification.
     * 
     * @return array A config array.
     * 
     */
    public static function fetchConfig($spec = null)
    {
        // load the config file values.
        // use alternate config source if one is given.
        if (is_array($spec) || is_object($spec)) {
            $config = (array) $spec;
        } elseif (is_string($spec)) {
            // merge from array file return
            $config = (array) Solar_File::load($spec);
        } else {
            // no added config
            $config = array();
        }
        
        return $config;
    }
}
