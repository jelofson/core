<?php
/**
 * 
 * Provides an abstract TemplateView pattern implementation for Solar.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * 
 * @version $Id:$
 * 
 */

/**
 * 
 * Provides an abstract TemplateView pattern implementation for Solar.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @todo how to set up base helper dir?
 * 
 */
abstract class Solar_View extends Solar_Base {
    
    /**
     * 
     * Array of configuration parameters.
     * 
     * @var array
     * 
     */
    protected $_config = array(
        'template_path' => array(),
        'helper_path'   => array(),
        'escape'        => array(),
    );
    
    /**
     * Parameters for escaping.
     */
    protected $_escape = array();
    
    /**
     * 
     * Array of helper objects.
     * 
     * @var array
     * 
     */
    protected $_helper = array();
    
    /**
     * 
     * Path stack for helpers.
     * 
     * @var Solar_PathStack
     * 
     */
    protected $_helper_path;
    
    /**
     * 
     * Path stack for templates.
     * 
     * @var Solar_PathStack
     * 
     */
    protected $_template_path;
    
    /**
     * 
     * Constructor.
     * 
     * @param array $config User-provided configuration values.
     * 
     */
    public function __construct($config = null)
    {
        // base construction
        parent::__construct($config);
        
        // load the base helper class
        Solar::loadClass('Solar_View_Helper');
        
        // set the fallback helper path
        $this->_helper_path = Solar::factory('Solar_PathStack'); 
        $this->setHelperPath($this->_config['helper_path']);
        
        // set the fallback template path
        $this->_template_path = Solar::factory('Solar_PathStack'); 
        $this->setTemplatePath($this->_config['template_path']);
    }
    
    
    // -----------------------------------------------------------------
    //
    // Helpers
    //
    // -----------------------------------------------------------------
    
    /**
     *
     * Executes an internal helper method with arbitrary parameters.
     * 
     * @param string $name The helper name.
     *
     * @param array $args The parameters passed to the helper.
     *
     * @return string The helper output.
     * 
     */
    public function __call($name, $args)
    {
        $helper = $this->getHelper($name);
        return call_user_func_array(array($helper, $name), $args);
    }
    
    /**
     * 
     * Reset the helper directory path stack.
     * 
     * @param string|array The directories to set for the stack.
     * 
     */
    public function setHelperPath($path = null)
    {
        return $this->_helper_path->set($path);
    }
    
    /**
     * 
     * Add to the helper directory path stack.
     * 
     * @param string|array The directories to add to the stack.
     * 
     */
    public function addHelperPath($path)
    {
        return $this->_helper_path->add($path);
    }
    
    /**
     * 
     * Returns the internal helper object; creates it as needed.
     * 
     * @param string $name The helper name.  If this helper has not
     * been created yet, this method creates it automatically.
     *
     * @return Solar_Template_Helper
     * 
     */
    public function getHelper($name)
    {
        if (empty($this->_helper[$name])) {
            $this->_helper[$name] = $this->newHelper($name);
        }
        return $this->_helper[$name];
    }
    
    /**
     * 
     * Creates a new standalone helper object.
     * 
     * @param string $name The helper name.
     *
     * @return Solar_View_Helper
     * 
     */
    public function newHelper($name)
    {
        $key = $name;
        $name = ucfirst($name);
        $class = "Solar_View_Helper_$name";
        
        // has the class been loaded?
        if (! class_exists($class, false)) {
        
            // look for the named file in the helper stack.
            $file = $this->_helper_path->findInclude("$name.php");
            if (! $file) {
                throw $this->_exception(
                    'ERR_HELPER_NOT_FOUND',
                    array(
                        'name' => $name,
                        'path' => $this->_helper_path->get()
                    )
                );
            }
            require_once $file;
        }
        $config = array('_view' => $this);
        $this->_helper[$key] = new $class($config);
        return $this->_helper[$key];
    }
    
    // -----------------------------------------------------------------
    //
    // Templates
    //
    // -----------------------------------------------------------------
    
    /**
     * 
     * Reset the template directory path stack.
     * 
     * @param string|array The directories to set for the stack.
     * 
     */
    public function setTemplatePath($path = null)
    {
        return $this->_template_path->set($path);
    }
    
    /**
     * 
     * Add to the template directory path stack.
     * 
     * @param string|array The directories to add to the stack.
     * 
     */
    public function addTemplatePath($path)
    {
        return $this->_template_path->add($path);
    }
    
    /**
     * 
     * Sets variables for the view.
     * 
     * This method is overloaded; you can assign all the properties of
     * an object, an associative array, or a single value by name.
     * 
     * You are not allowed to assign any variable named '_config' as
     * it would conflict with internal configuration tracking.
     * 
     * In the following examples, the template will have two variables
     * assigned to it; the variables will be known inside the template as
     * "$this->var1" and "$this->var2".
     * 
     * <code>
     * $view = Solar::factory('Solar_View_Template');
     * 
     * // assign directly
     * $view->var1 = 'something';
     * $view->var2 = 'else';
     * 
     * // assign by associative array
     * $ary = array('var1' => 'something', 'var2' => 'else');
     * $view->assign($ary);
     * 
     * // assign by object
     * $obj = new stdClass;
     * $obj->var1 = 'something';
     * $obj->var2 = 'else';
     * $view->assign($obj);
     * 
     * // assign by name and value
     * $view->assign('var1', 'something');
     * $view->assign('var2', 'else');
     * </code>
     * 
     * @return bool True on success, false on failure.
     * 
     */
    public function assign($spec)
    {
        // assign from associative array
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $this->$key = $val;
            }
            return true;
        }
        
        // assign from object public properties
        if (is_object($spec)) {
            foreach (get_object_vars($spec) as $key => $val) {
                $this->$key = $val;
            }
            return true;
        }
        
        // assign by name and value
        if (is_string($spec) && func_num_args() > 1) {
            $this->$spec = func_get_arg(1);
            return true;
        }
        
        // $spec was not object, array, or string.
        return false;
    }
    
    /**
     * 
     * Displays a template directly (equivalent to <code>echo $tpl</code>).
     * 
     * @param string $tpl The template source to compile and display.
     * 
     */
    public function display($name)
    {
        echo $this->fetch($name);
    }
    
    /**
     * 
     * Fetches template output.
     * 
     * @param string $name The template to process.
     * 
     * @return string The template output.
     * 
     */
    public function fetch($name)
    {
        $file = $this->template($name);
        ob_start();
        $this->_run($file);
        return ob_get_clean();
    }
    
    /**
     *
     * Returns the path to the requested template script.
     * 
     * Used inside a template script like so:
     * 
     * <code>
     * include $this->template($name);
     * </code>
     * 
     * @param string $name The template name to look for in the template path.
     * 
     * @return string The full path to the template script.
     * 
     */
    public function template($name)
    {
        // get a path to the template
        $file = $this->_template_path->findInclude($name);
        if (! $file) {
            throw $this->_exception(
                'ERR_TEMPLATE_NOT_FOUND',
                array('name' => $name, 'path' => $this->_template_path->get())
            );
        }
        return $file;
    }
    
    /**
     * 
     * Runs a template script (allowing access to $this).
     * 
     * @param string The template script to run.
     * 
     */
    protected function _run()
    {
        require func_get_arg(0);
    }
    
    /**
     * 
     * Returns a value escaped for output.
     * 
     * @param scalar $val THe value to escape.
     * 
     */
    abstract public function escape($val);
}
?>