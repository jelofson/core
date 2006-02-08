<?php
/**
 * 
 * Static methods for validating data.
 * 
 * @category Solar
 * 
 * @package Solar_Valid
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
 * Static methods for validating data.
 * 
 * Solar_Valid aggregates several validation routines as static
 * methods so you can make sure user input matches your requirements.
 *  This is useful for checking form values and validating database
 * fields.
 * 
 * Be sure to check the ClassMethods for a full list of
 * valdiation routines.  Note that all the methods are static, so
 * you never need to instantiate Solar_Valid (although you can if you
 * want to).
 * 
 * <code type="php">
 * require_once 'Solar.php';
 * Solar::start();
 * 
 * // load the validation class
 * Solar::loadClass('Solar_Valid');
 * 
 * // Fetch a copy of the $_GET['name'] value
 * $name = Solar::get('name');
 * 
 * // Does it match the "alpha" validation rule?
 * // (i.e., A-Z and a-z only).
 * if (! Solar_Valid::alpha($name)) {
 *     echo htmlspecialchars("Name '$name' is not valid.");
 * }
 * 
 * 
 * // Fetch a copy og the $_POST['date'] value
 * $date = Solar::post('date');
 * 
 * // Is it an ISO-formatted date?  (Alternatively,
 * // it can be completely blank.)
 * if (! Solar_Valid::isoDate($date, Solar_Valid::OR_BLANK)) {
 *     echo "The date must be in 'yyyy-mm-dd' format, or blank.";
 * }
 * </code>

 * @category Solar
 * 
 * @package Solar_Valid
 * 
 */
class Solar_Valid {
    
    /**
     * Flag for allowing validation on a blank value.
     */
    const OR_BLANK  = true;
    
    /**
     * Flag for disallowing validation on a blank value.
     */
    const NOT_BLANK = false;
    
    /**
     * 
     * Validate that a value is only letters (upper or lower case) and digits.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function alnum($value, $blank = Solar_Valid::NOT_BLANK)
    {
        $expr = '/^[a-zA-Z0-9]+$/'; 
        return self::regex($value, $expr, $blank);
    }
    
    /**
     * 
     * Validate that a value is letters only (upper or lower case).
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function alpha($value, $blank = Solar_Valid::NOT_BLANK)
    {
        $expr = '/^[a-zA-Z]+$/';
        return self::regex($value, $expr, $blank);
    }
    
    /**
     * 
     * Validate that a value is empty when trimmed of all whitespace.
     * 
     * The value is assessed as a string; thus, if you pass a numeric
     * zero, the value will not validate, becuse string '0' does not 
     * trim down to an empty string.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function blank($value)
    {
        return (trim((string)$value) == '');
    }
    
    /**
     * 
     * Validate against a custom callback function or method.
     * 
     * Use this to perform your own customized validations.  The value
     * will be passed as the first argument to the callback; the
     * results of the callback should indicate boolean true if the
     * value was valid, false if not.
     * 
     * <code type="php">
     * require_once 'Solar.php';
     * Solar::start();
     * 
     * Solar::loadClass('Solar_Valid');
     * 
     * // validate $value against a function
     * $result = Solar_Valid::custom($value, 'my_function');
     * 
     * // validate $value against a static method
     * $result = Solar_Valid::custom($value, array('SomeClass', 'StaticMethod'));
     * 
     * // validate $value against an object method
     * $result = Solar_Valid::custom($value, array($object, 'MethodName'));
     * 
     * // validate $value against a function, with added parameters for the function
     * $result = Solar_Valid::custom($value, 'my_function', $foo, 'bar', $etc);
     * </code>
     * 
     * @param mixed $value The value to validate.
     * 
     * @param callback $callback A string or array suitable for use
     * as the first argument to [[php call_user_func_array()]].
     * 
     * @return bool True if valid, false if not.
     * 
     * @see call_user_func_array()
     * 
     */
    static public function custom($value, $callback)
    {
        // keep all arguments so we can pass extras to the callback
        $args = func_get_args();
        // drop the value and the callback from the arglist
        array_shift($args);
        array_shift($args);
        // put the value back at the top of the argument list
        array_unshift($args, $value);
        // make the callback
        return call_user_func_array($callback, $args);
    }
    
    /**
     * 
     * Validate that a value is an email address.
     * 
     * The regular expression in this method was taken from HTML_QuickForm.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function email($value, $blank = Solar_Valid::NOT_BLANK)
    {
        $expr = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';
        return self::regex($value, $expr, $blank);
    }
    
    /**
     * 
     * Validate that the value is a key in the list of of allowed options.
     * 
     * Given the keys of the array (second parameter), the value
     * (first parameter) must match at least one of those keys.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param array $array An array of allowed options.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function inKeys($value, $array, $blank = Solar_Valid::NOT_BLANK)
    {
        if ($blank && self::blank($value)) {
            return true;
        }
        
        return array_key_exists($value, (array) $array);
    }
    
    /**
     * 
     * Validate that a value is in a list of allowed options.
     * 
     * Strict checking is enforced, so a string "1" is not the same as
     * an integer 1.  This helps to avoid matching 0 and empty, etc.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param array $array An array of allowed options.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function inList($value, $array, $blank = Solar_Valid::NOT_BLANK)
    {
        if ($blank && self::blank($value)) {
            return true;
        }
        
        return in_array($value, (array) $array, true);
    }
    
    /**
     * 
     * See a value has only a certain number of digits and decimals.
     * 
     * The value must be numeric, can be no longer than the //size//,
     * and can have no more decimal places than the //scope//.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param int $size The total number of digits allowed in the value,
     * excluding the negative sign and decimal point.
     * 
     * @param int $scope The maximum number of decimal places.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function inScope($value, $size, $scope, $blank = Solar_Valid::NOT_BLANK)
    {
        // allowed blank?
        if ($blank && self::blank($value)) {
            return true;
        }
        
        // scope has to be smaller than size.
        // both size and scope have to be positive numbers.
        if ($size < $scope || $size < 0 || $scope < 0 ||
            ! is_numeric($size) || ! is_numeric($scope)) {
            return false;
        }
        
        // value must be only numeric
        if (! is_numeric($value)) {
            return false;
        }
        
        // drop trailing and leading zeroes
        $value = (float) $value;
        
        // test the size (whole + decimal) and scope (decimal only).
        // does not include signs (+/-) or the decimal point itself.
        // 
        // use the @ signs in strlen() checks to suppress errors
        // when the match-element doesn't exist.
        $expr = "/^(\-)?([0-9]+)?((\.)([0-9]+))?$/";
        if (preg_match($expr, $value, $match) &&
            @strlen($match[2] . $match[5]) <= $size &&
            @strlen($match[5]) <= $scope) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * Validate that a value represents an integer (+/-).
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function integer($value, $blank = Solar_Valid::NOT_BLANK)
    {
        $expr = '/^[\+\-]?[0-9]+$/';
        return self::regex($value, $expr, $blank);
    }
    
    /**
     * 
     * Validate that a value is a legal IPv4 address.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function ipv4($value, $blank = Solar_Valid::NOT_BLANK)
    {
        if ($blank && self::blank($value)) {
            return true;
        }
        
        $expr = '/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/';
        $result = preg_match($expr, $value, $matches);
        
        // no match
        if (! $result) {
            return false;
        }
        
        // check that all four quads are 0-255
        for ($i = 1; $i <= 4; $i++) {
            if ($matches[$i] < 0 || $matches[$i] > 255) {
                return false;
            }
        }
        
        // done!
        return true;
    }
    
    /**
     * 
     * Validate that a value is an ISO 8601 date string.
     * 
     * The format is "yyyy-mm-dd".  Also checks to see that the date
     * itself is valid (e.g., no Feb 30).
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function isoDate($value, $blank = Solar_Valid::NOT_BLANK)
    {
        if ($blank && self::blank($value)) {
            return true;
        }
        
        // basic date format
        // yyyy-mm-dd
        $expr = '/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/';
        
        // validate
        if (preg_match($expr, $value, $match) &&
            checkdate($match[2], $match[3], $match[1])) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * Validate that a value is an ISO 8601 date-time string.
     * 
     * The format is "yyyy-mm-ddThh:ii:ss" (note the literal "T" in the
     * middle, which acts as a separator).
     * 
     * Also checks that the date itself is valid (e.g., no Feb 30).
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function isoDateTime($value, $blank = Solar_Valid::NOT_BLANK)
    {
        if ($blank && self::blank($value)) {
            return true;
        }
        
        // basic timestamp format (19 chars long)
        // yyyy-mm-ddThh:ii:ss
        // 0123456789012345678
        // get the individual portions
        $date = substr($value, 0, 10);
        $sep = substr($value, 10, 1);
        $time = substr($value, 11, 8);
        
        //echo "'$date' '$sep' '$time'\n";
        // now validate each portion
        if (strlen($value) == 19 &&
            self::isoDate($date) &&
            $sep == 'T' &&
            self::isoTime($time)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * Validate that a value is an ISO 8601 time string (hh:ii::ss format).
     * 
     * Per note from Chris Drozdowski about ISO 8601, allows two
     * midnight times ... 00:00:00 for the beginning of the day, and
     * 24:00:00 for the end of the day.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function isoTime($value, $blank = Solar_Valid::NOT_BLANK)
    {
        $expr = '/^(([0-1][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9]$/';
        return self::regex($value, $expr, $blank) || ($value == '24:00:00');
    }
    
    /**
     * 
     * Validate that a value is a locale code.
     * 
     * The format is two lower-case letters, an underscore, and two upper-case
     * letters.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function locale($value, $blank = Solar_Valid::NOT_BLANK)
    {
        $expr = '/^[a-z]{2}_[A-Z]{2}$/';
        return self::regex($value, $expr, $blank);
    }
    
    /**
     * 
     * Validate that a value is less than than or equal to a maximum.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param mixed $min The maximum valid value.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function max($value, $max, $blank = Solar_Valid::NOT_BLANK)
    {
        // reverse the blank-check so that empties are not
        // treated as zero.
        if (! $blank && self::blank($value)) {
            return false;
        }
        
        return $value <= $max;
    }
    
    /**
     * 
     * Validate that a string is no longer than a certain length.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param mixed $min The value must have no more than this many
     * characters.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function maxLength($value, $max, $blank = Solar_Valid::NOT_BLANK)
    {
        // reverse the blank-check so that empties are not
        // checked for length.
        if (! $blank && self::blank($value)) {
            return false;
        }
        
        return (strlen($value) <= $max);
    }
    
    /**
     * 
     * Validate that a value is formatted as a MIME type.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function mimeType($value, $allowed = null,
        $blank = Solar_Valid::NOT_BLANK)
    {
        // basically, anything like 'text/plain' or
        // 'application/vnd.ms-powerpoint' or
        // 'text/xml+xhtml'
        $word = '[a-zA-Z][\-\.a-zA-Z0-9+]*';
        $expr = '|^' . $word . '/' . $word . '$|';
        $ok = self::regex($value, $expr, $blank);
        $allowed = (array) $allowed;
        if ($ok && count($allowed) > 0) {
            $ok = in_array($value, $allowed);
        }
        return $ok;
    }
    
    /**
     * 
     * Validate that a value is greater than or equal to a minimum.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param mixed $min The minimum valid value.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function min($value, $min, $blank = Solar_Valid::NOT_BLANK)
    {
        if ($blank && self::blank($value)) {
            return true;
        }
        
        return $value >= $min;
    }
    
    /**
     * 
     * Validate that a string is at least a certain length.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param mixed $min The value must have at least this many
     * characters.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function minLength($value, $min, $blank = Solar_Valid::NOT_BLANK)
    {
        if ($blank && self::blank($value)) {
            return true;
        }
        
        return (strlen($value) >= $min);
    }
    
    /**
     * 
     * Check the value against multiple validations.
     * 
     * Use this to perform multiple validations on a single value. 
     * All of the validations must be successful for the value to be
     * valid.  If any of the validations fails, then the value is
     * treated as not valid.
     * 
     * The array describing the validations must itself consist of a
     * series of arrays where the first element is a Solar_Valid
     * method name, and the remaining elements are the parameters for
     * that method (not including the value, of course).
     * 
     * <code type="php">
     * require_once 'Solar.php';
     * Solar::start();
     * 
     * Solar::loadClass('Solar_Valid');
     * 
     * // the list of validations to perform
     * $validations = array(
     *     array('maxLength', 12),
     *     array('regex', '/^\w+$/', Solar_Valid::OR_BLANK),
     * );
     * 
     * // this will be valid
     * $valid = Solar_Valid::multiple('something', $validations);
     * 
     * // this will not be valid (too long)
     * $valid = Solar_Valid::multiple('somethingelse', $validations);
     * 
     * // this will not be valid (non-word character)
     * $valid = Solar_Valid::multiple('some~thing', $validations);
     * 
     * // this will be valid (not too long, and OR_BLANK)
     * $valid = Solar_Valid::multiple('', $validations);
     * </code>
     * 
     * @param mixed $value The value to validate.
     * 
     * @param array $validations A sequential array of validations; each
     * element can be a string method name, or an array where element 0 is
     * the string method name and elements 1-N is are the arguments for
     * that method.  The method must be a Solar_Valid method.
     * 
     * @return bool True if the value passes all validations, false if not.
     * 
     */
    static public function multiple($value, $validations)
    {
        // loop through all the requested validations
        settype($validations, 'array');
        foreach ($validations as $params) {
            
            // the first element is the method name
            settype($params, 'array');
            $method = array_shift($params);
            
            // put the value at the top of the remaining parameters.
            array_unshift($params, $value);
            
            // call the validation method
            $result = call_user_func_array(
                array('self', $method),
                $params
            );
            
            // if it failed, cancel further validation
            if (! $result) {
                return false;
            }
        }
        
        // passed all validations
        return true;
    }
    
    /**
     * 
     * Validate that a value is not exactly zero.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function nonZero($value, $blank = Solar_Valid::NOT_BLANK)
    {
        // reverse the blank-check so that empties are not
        // treated as zero.
        if (! $blank && self::blank($value)) {
            return false;
        }
        
        // +-000.000
        $expr = '/^(\+|\-)?0+(.0+)?$/';
        return ! self::regex($value, $expr);
    }
    
    /**
     * 
     * Validate that a string is not empty when trimmed.
     * 
     * Spaces, newlines, etc. will be trimmed, so a value consisting
     * only of whitespace is considered blank.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function notBlank($value)
    {
        return (trim((string)$value) != '');
    }
    
    /**
     * 
     * Validate a value against a regular expression.
     * 
     * Uses [[php preg_match]] to compare the value against the given
     * regular epxression.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param string $expr The regular expression to validate against.
     * 
     * @return bool True if the value matches the expression, false if not.
     * 
     */
    static public function regex($value, $expr, $blank = Solar_Valid::NOT_BLANK)
    {
        if ($blank && self::blank($value)) {
            return true;
        }
        return (bool) preg_match($expr, $value);
    }
    
    /**
     * 
     * Validate that a value is composed of separated words.
     * 
     * These include a-z, A-Z, 0-9, and underscore, indicated by a 
     * regular expression "\w".  By default, the separator is a space.
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function sepWords($value, $sep = ' ', $blank = Solar_Valid::NOT_BLANK)
    {
        $expr = '/^[\w' . preg_quote($sep) . ']+$/';
        return self::regex($value, $expr, $blank);
    }
    
    /**
     * 
     * Validate a value as a URI per RFC2396.
     * 
     * The value must match a generic URI format; e.g.,
     * ``http://example.com``, ``mms://example.org``, and so on.
     * 
     * If //$schemes// is null, any and all schemes (http,
     * ftp, mms, xyz) are allowed.  Otherwise, the URI scheme must be
     * one of the //$schemes// array values.
     * 
     * @param mixed $value The value to validate.
     * 
     * @param string|array $schemes Allowed schemes for the URI;
     * e.g., http, https, ftp.  If null, any scheme at all is
     * allowed.
     * 
     * @return bool True if the value is a URI and is one of the allowed
     * schemes, false if not.
     * 
     */
    static public function uri($value, $schemes = null, $blank = Solar_Valid::NOT_BLANK)
    {
        // allow blankness?
        if ($blank && self::blank($value)) {
            return true;
        }
        
        // TAKEN (almost) DIRECTLY FROM PEAR_VALIDATE::URI()
        $result = preg_match(
            '�^(?:([a-z][-+.a-z0-9]*):)?                                                # 1. scheme
            (?://                                                                       #    authority start
            (?:((?:%[0-9a-f]{2}|[-a-z0-9_.!~*\'();:&=+$,])*)@)?                         # 2. authority-userinfo
            (?:((?:[a-z0-9](?:[-a-z0-9]*[a-z0-9])?\.)*[a-z](?:[-a-z0-9]*[a-z0-9])?\.?)  # 3. authority-hostname OR
            |([0-9]{1,3}(?:\.[0-9]{1,3}){3}))                                           # 4. authority-ipv4
            (?::([0-9]*))?)?                                                            # 5. authority-port
            ((?:/(?:%[0-9a-f]{2}|[-a-z0-9_.!~*\'():@&=+$,;])*)+)?                       # 6. path
            (?:\?([^#]*))?                                                              # 7. query
            (?:\#((?:%[0-9a-f]{2}|[-a-z0-9_.!~*\'();/?:@&=+$,])*))?                     # 8. fragment
            $�xi', $value, $matches);
        
        if ($result) {
            
            $scheme = isset($matches[1]) ? $matches[1] : '';
            $authority = isset($matches[3]) ? $matches[3] : '' ;
            
            // we need some sort of scheme
            if (! $scheme) {
                return false;
            }
            
            // is the scheme allowed?
            settype($schemes, 'array');
            if ($schemes && ! in_array($scheme, $schemes)) {
                return false;
            }
            
            // check IPv4 addresses as domains
            if (isset($matches[4])) {
                $parts = explode('.', $matches[4]);
                foreach ($parts as $part) {
                    if ($part > 255) {
                        return false;
                    }
                }
            }
            
            // are we doing strict checks?
            $list = ';/?:@$,';
            $strict = '#[' . preg_quote($list, '#') . ']#';
            $test1 = (isset($matches[7]) && preg_match($strict, $matches[7]));
            $test2 = (isset($matches[8]) && preg_match($strict, $matches[8]));
            if ($test1 || $test2) {
                return false;
            }
            
            return true;
        }
        
        // default is to not-validate
        return false;
    }
    
    /**
     * 
     * Validate that a value is composed only of "word" characters.
     * 
     * These include a-z, A-Z, 0-9, and underscore, indicated by a 
     * regular expression "\w".
     * 
     * @param mixed $value The value to validate.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    static public function word($value, $blank = Solar_Valid::NOT_BLANK)
    {
        $expr = '/^\w+$/';
        return self::regex($value, $expr, $blank);
    }
}
?>