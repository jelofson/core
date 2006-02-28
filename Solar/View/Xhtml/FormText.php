<?php
/**
 * 
 * Helper for a 'text' element.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@ciaweb.net>
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * 
 * @version $Id: Solar_View_Helper_FormText.php 654 2006-01-11 17:10:06Z pmjones $
 * 
 */

/**
 * The abstract FormElement class.
 */
require_once 'Solar/View/Xhtml/FormElement.php';

/**
 * 
 * Helper for a 'text' element.
 * 
 * @category Solar
 * 
 * @package Solar_View
 * 
 * @author Paul M. Jones <pmjones@ciaweb.net>
 * 
 */
class Solar_View_Helper_FormText extends Solar_View_Helper_FormElement {
    
    /**
     * 
     * Generates a 'text' element.
     * 
     * @param array $info An array of element information.
     * 
     * @return string The element XHTML.
     * 
     */
    public function formText($info)
    {
        extract($this->_info($info));
        
        if ($disable) {
            // disabled
            $xhtml = $this->_view->formHidden(array('name' => $name, 'value' => $value))
                   . $this->_view->escape($value);
        } else {
            // enabled
            $xhtml = '<input type="text"'
                   . ' name="' . $this->_view->escape($name) . '"'
                   . ' value="' . $this->_view->escape($value) . '"'
                   . $this->_view->attribs($attribs)
                   . ' />';
        }
        
        return $xhtml;
    }
}
?>