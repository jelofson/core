<?php
Solar::loadClass('Solar_Markdown_Plugin');
class Solar_Markdown_Plugin_HeaderSetext extends Solar_Markdown_Plugin {
    
    protected $_Solar_Markdown_Plugin_HeaderSetext = array(
        'top' => 'h1',
        'sub' => 'h2',
    );
    
    protected $_is_block = true;
    
    /**
     * 
     * Turns setext-style headers into XHTML <h?> tags.
     * 
     * @param string $text Portion of the Markdown source text.
     * 
     * @return string The transformed XHTML.
     * 
     */
    public function parse($text)
    {
        $text = preg_replace_callback(
            '{ ^(.+)[ \t]*\n=+[ \t]*\n+ }mx',
            array($this, '_parse'),
            $text
        );
        
        $text = preg_replace_callback(
            '{ ^(.+)[ \t]*\n-+[ \t]*\n+ }mx',
            array($this, '_parse_sub'),
            $text
        );
        
        return $text;
    }

    /**
     * 
     * Support callback for top-level setext headers ("h1").
     * 
     * @param array $matches Matches from preg_replace_callback().
     * 
     * @return string The replacement text.
     * 
     */
    protected function _parse($matches)
    {
        $tag = $this->_config['top'];
        return $this->_tokenize("<$tag>")
             . $this->_processSpans($matches[1])
             . $this->_tokenize("</$tag>")
             . "\n\n";
    }
    
    /**
     * 
     * Support callback for sub-level setext headers ("h2").
     * 
     * @param array $matches Matches from preg_replace_callback().
     * 
     * @return string The replacement text.
     * 
     */
    protected function _parse_sub($matches)
    {
        $tag = $this->_config['sub'];
        return $this->_tokenize("<$tag>")
             . $this->_processSpans($matches[1])
             . $this->_tokenize("</$tag>")
             . "\n\n";
    }
}
?>