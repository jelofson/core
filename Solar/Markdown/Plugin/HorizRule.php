<?php
Solar::loadClass('Solar_Markdown_Plugin');
class Solar_Markdown_Plugin_HorizRule extends Solar_Markdown_Plugin {
    
    protected $_is_block = true;
    
    /**
     * 
     * Replaces markup for horizontal rules.
     * 
     * @param string $text Portion of the Markdown source text.
     * 
     * @return string The transformed XHTML.
     * 
     */
    public function parse($text)
    {
        return preg_replace_callback(
            array('{^[ ]{0,2}([ ]?\*[ ]?){3,}[ \t]*$}mx',
                  '{^[ ]{0,2}([ ]? -[ ]?){3,}[ \t]*$}mx',
                  '{^[ ]{0,2}([ ]? _[ ]?){3,}[ \t]*$}mx'),
            array($this, '_parse'),
            $text
        );
    }
    
    protected function _parse($matches)
    {
        return "\n<hr />\n";
    }
}
?>