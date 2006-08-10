<?php
abstract class Solar_Markdown_Plugin extends Solar_Base {
    
    protected $_Solar_Markdown_Plugin = array(
        '_markdown' => null,
    );
    
    /**
     * 
     * Array of token keys with text values to replace them at
     * rendering time.
     * 
     * @var array
     * 
     */
    protected $_token = array();
    
    /**
     * 
     * The name of this class.
     * 
     * @var string
     * 
     */
    protected $_class = null;
    
    /**
     * 
     * The current token count.
     * 
     * @var int
     * 
     */
    protected $_count = 0;
    
    /**
     * 
     * Number of spaces per tab.
     * 
     * @var int
     * 
     */
    protected $_tab_width = 4;
    
    /**
     * 
     * "Parent" Markdown object.
     * 
     * @var Solar_Markdown
     * 
     */
    protected $_markdown;
    
    /**
     * 
     * Is this a block-level plugin?
     * 
     * (It is possible for a plugin to be neither block nor span.)
     * 
     * @var bool
     * 
     */
    protected $_is_block = false;
    
    /**
     * 
     * Is this a span-level plugin?
     * 
     * (It is possible for a plugin to be neither block nor span.)
     * 
     * @var bool
     * 
     */
    protected $_is_span = false;
    
    /**
     * 
     * Constructor.
     * 
     * @param array $config User-defined configuration values.
     * 
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->_class = get_class($this);
    }
    
    /**
     * 
     * Is this a block-level plugin?
     * 
     * Reports the value of $this->_is_block.
     * 
     * @var bool
     * 
     */
    public function isBlock()
    {
        return (bool) $this->_is_block;
    }
    
    /**
     * 
     * Is this a span-level plugin?
     * 
     * Reports the value of $this->_is_span.
     * 
     * @var bool
     * 
     */
    public function isSpan()
    {
        return (bool) $this->_is_span;
    }
    
    /**
     * 
     * Prepares the source text before any parsing occurs.
     * 
     * Returns the text as-is.
     * 
     * @param string $text The source text.
     * 
     * @return string $text The text after being filtered.
     * 
     */
    public function prepare($text)
    {
        return $text;
    }
    
    /**
     * 
     * Parses the source text using the regular expression.
     * 
     * @param string $text The source text.
     * 
     * @return string The text after parsed values have been replaced
     * with delimited tokens.
     * 
     */
    public function parse($text)
    {
        return $text;
    }
     
    /**
     * 
     * Cleans up the source text after all parsing occurs.
     * 
     * Returns the text as-is.
     * 
     * @param string $text The source text.
     * 
     * @return string $text The text after being filtered.
     * 
     */
    public function cleanup($text)
    {
        return $text;
    }
    
    /**
     * 
     * Renders tokenized values back into the source text.
     * 
     * @param string $text The source text.
     * 
     * @return string The source text with replaced token values.
     * 
     */
    public function render($text)
    {
        foreach ($this->_token as $key => $val) {
            $text = str_replace(
                $this->_getToken($key),
                $val,
                $text
            );
        }
        
        return $text;
    }
    
    /**
     * 
     * Returns a delimited token representing a piece of text.
     * 
     * @param string $text The text to represent as a token.
     * 
     * @return string A delimited token identifier.
     * 
     */
    protected function _tokenize($text)
    {
        $this->_token[$this->_count] = $text;
        return $this->_getToken($this->_count ++);
    }
    
    /**
     * 
     * Returns a delimited token key for this class.
     * 
     * @param int $key The token key number.
     * 
     * @return string The delimited token key.
     * 
     */
    protected function _getToken($key)
    {
        return "\x0E"  // ctrl-n, "shift out"
             . md5($this->_class . ':' . $key)
             . "\x0F"; // ctrl-o, "shift in"
    }
    
    /**
     * 
     * Removes one level of leading tabs or space from a text block.
     * 
     * E.g., if a block of text is indented by 3 tabs, it will be
     * returned as indented with only 2 tabs.
     * 
     * @param string $text A block of text.
     * 
     * @return string The same text out-dented by one level of tabs
     * or spaces.
     * 
     */
    protected function _outdent($text)
    {
        return preg_replace(
            "/^(\\t|[ ]{1,$this->_tab_width})/m",
            "",
            $text
        );
    }
    
    /**
     * 
     * Escapes text using htmlspecialchars() with ENT_COMPAT and UTF-8.
     * 
     * @param string $text A line of text.
     * 
     * @return string The same text without leading whitespace.
     * 
     */
    protected function _escape($text)
    {
        return htmlspecialchars($text, ENT_COMPAT, 'UTF-8');
    }
    
    /**
     * 
     * Uses the "parent" Markdown object to parse blocks.
     * 
     * @param string $text Source text.
     * 
     * @return string The source text after block parsing.
     * 
     */
    protected function _processBlocks($text)
    {
        return $this->_config['_markdown']->processBlocks($text);
    }
    
    /**
     * 
     * Uses the "parent" Markdown object to parse spans.
     * 
     * @param string $text Source text.
     * 
     * @return string The source text after span parsing.
     * 
     */
    protected function _processSpans($text)
    {
        return $this->_config['_markdown']->processSpans($text);
    }
}
?>