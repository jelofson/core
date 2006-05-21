<?php

/**
* 
* Class to collect and return localization strings.
* 
* @category Solar
* 
* @package Solar
* 
* @subpackage Solar_Locale
* 
* @author Paul M. Jones <pmjones@solarphp.com>
* 
* @license LGPL
* 
* @version $Id$
* 
*/

/**
* 
* Class to collect and return localization strings.
* 
* @category Solar
* 
* @package Solar
* 
*/

class Solar_Locale extends Solar_Base {
	
	
	/**
	* 
	* User-provided configuration values.
	* 
	*/
	
	protected $config = array(
		'locale' => 'Solar/Locale/',
		'code'   => 'en_US',
	);
	
	
	/**
	* 
	* Array of translated strings organized by class and key.
	* 
	* @access protected
	* 
	* @var array
	* 
	*/
	
	public $string = array();
	
	
	/**
	* 
	* Constructor.
	* 
	* @access public
	* 
	*/
	
	public function __construct($config = null)
	{
		// basic construction
		parent::__construct();
		
		// set the locale code
		$this->code($this->config['code']);
		
		// load the baseline Solar translation strings
		$this->load('Solar', $this->config['locale']);
	}
	
	
	/**
	* 
	* Sets a new locale code and clears current strings.
	* 
	* @access public
	* 
	* @param $code string The new locale code.
	* 
	*/
	
	public function setCode($code)
	{
		$this->string = array();
		$this->config['code'] = $code;
		$this->load('Solar', $this->config['locale']);
	}
	
	
	/**
	* 
	* Gets the locale code.
	* 
	* @access public
	* 
	* @return string The current local code.
	* 
	*/
	
	public function code()
	{
		return $this->config['code'];
	}
	
	
	/**
	* 
	* Sets the locale translation for a class and key.
	* 
	* The locale translation may be a string, or an array of
	* two elements.  If an array, element 0 is the "singular"
	* form of the translation, and element 1 is the "plural"
	* form.
	* 
	* @access public
	* 
	* @param string $class The class for the translation key, e.g.
	* 'Solar_Cell_Talk'.
	* 
	* @param string $key The translation key, e.g. 'LABEL_EMAIL'.
	* 
	* @param atring|array $val A singular string, or a two-elements
	* array of singular string and plural string.
	* 
	* @return void
	* 
	*/
	
	public function setString($class, $key, $val)
	{
		$this->string[$class][$key] = $val;
	}
	
	
	/**
	* 
	* Sets the locale translation for an entire class of keys.
	* 
	* @access public
	* 
	* @param string $class The class for the translation key, e.g.
	* 'Solar_Cell_Talk'.
	* 
	* @param array $list An associative array of keys and translation values.
	* 
	* @return void
	* 
	*/
	
	public function setStrings($class, $list)
	{
		foreach ($list as $key => $val) {
			$this->string[$class][$key] = $val;
		}
	}

	
	/**
	* 
	* Loads a locale class from a PHP array file in the specified directory.
	* 
	* @access public
	* 
	* @param string $class The class for the translation key, e.g.
	* 'Solar_Cell_Talk'.
	* 
	* @param string $dir The directory where the translation PHP array files
	* are located.  Will search this directory for a file named after the
	* locale code, ending in '.php'.  E.g., if $this->config['code'] is 'en_US' and
	* $dir is 'Solar/Locale/', load() will look for a file at the path
	* 'Solar/Locale/en_US.php'.
	* 
	* @return void
	* 
	*/

	public function load($class, $dir)
	{
		// create the file name
		$dir = Solar::fixdir($dir);
		$file = $dir . $this->config['code'] . '.php';
		
		// this hack is the equivalent of is_readable(), but it also
		// checks the include-path to see if the file exists.
		$fp = @fopen($file, 'r', true);
		$ok = ($fp) ? true : false;
		@fclose($fp);
		
		// could we find the file?
		if ($ok) {
			$this->string[$class] = (array) include $file;
		} else {
			// could not find file.
			// fail silently, as it's often the case that the
			// translation file simply doesn't exist yet.
			$this->string[$class] = array();
		}
	}

	
	/**
	* 
	* Returns the locale string for a class and key.
	* 
	* @access public
	* 
	* @param string $class The class for the translation key, e.g.
	* 'Solar_Cell_Comments'.
	* 
	* @param string $key The translation key to find.
	* 
	* @param int|float $num If set to 1, returns the singluar form of
	* the translated key.  Otherwise, returns the plural form of
	* the translated key (if one exists, else singular).
	* 
	* @return string The translated key, or the key itself if no
	* translated string was found.
	* 
	*/

	public function string($class, $key, $num = 1)
	{
		// if the key does not exist for the class,
		// return the key itself.
		if (! isset($this->string[$class][$key])) {
			return $key;
		}
		
		// get the translation of the key and force
		// to an array.
		$string = (array) $this->string[$class][$key];
		
		// return the number-appropriate version of the
		// translated key, if multiple values exist.
		if ($num != 1 && isset($string[1])) {
			return $string[1];
		} else {
			return $string[0];
		}
	}
}

?>