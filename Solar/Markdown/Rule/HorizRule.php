<?php
Solar::loadClass('Solar_Markdown_Rule');
class Solar_Markdown_Rule_HorizRule extends Solar_Markdown_Rule {
    
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
        return preg_replace(
            array('{^[ ]{0,2}([ ]?\*[ ]?){3,}[ \t]*$}mx',
                  '{^[ ]{0,2}([ ]? -[ ]?){3,}[ \t]*$}mx',
                  '{^[ ]{0,2}([ ]? _[ ]?){3,}[ \t]*$}mx'),
            array($this, '_parse'),
            $text
        );
    }
    
    protected function _parse($matches)
    {
        return "\n" . $this->_tokenize('<hr />') . "\n";
    }
}
?>