<?php
Solar::loadClass('Solar_Markdown_Plugin');
class Solar_Markdown_Plugin_List extends Solar_Markdown_Plugin {
    
    protected $_is_block = true;
    
    protected $_list_level = 0;
    
    /**
     * 
     * Makes ordered (numbered) and unordered (bulleted) XHTML lists.
     * 
     * @param string $text Portion of the Markdown source text.
     * 
     * @return string The transformed XHTML.
     * 
     */
    public function parse($text)
    {
        $less_than_tab = $this->_tab_width - 1;

        # Re-usable patterns to match list item bullets and number markers:
        $marker_ul  = '[*+-]';
        $marker_ol  = '\d+[.]';
        $marker_any = "(?:$marker_ul|$marker_ol)";

        $markers = array($marker_ul, $marker_ol);

        foreach ($markers as $marker) {
            # Re-usable pattern to match any entire ul or ol list:
            $whole_list = '
                (                                # $1 = whole list
                  (                              # $2
                    [ ]{0,'.$less_than_tab.'}    
                    ('.$marker.')                # $3 = first list item marker
                    [ \t]+                       
                  )                              
                  (?s:.+?)                       
                  (                              # $4
                      \z                         
                    |                            
                      \n{2,}                     
                      (?=\S)                     
                      (?!                        # Negative lookahead for another list item marker
                        [ \t]*
                        '.$marker.'[ \t]+
                      )
                  )
                )
            '; // mx
        
            # We use a different prefix before nested lists than top-level lists.
            # See extended comment in _ProcessListItems().
    
            if ($this->_list_level) {
                $text = preg_replace_callback('{
                        ^
                        '.$whole_list.'
                    }mx',
                    array($this, '_parse'),
                    $text
                );
            }
            else {
                $text = preg_replace_callback('{
                        (?:(?<=\n\n)|\A\n?)
                        '.$whole_list.'
                    }mx',
                    array($this, '_parse'),
                    $text
                );
            }
        }

        return $text;
    }
    
    /**
     * 
     * Support callback for top-level list blocks.
     * 
     * @param array $matches Matches from preg_replace_callback().
     * 
     * @return string The replacement text.
     * 
     */
    protected function _parse($matches)
    {
        // Re-usable patterns to match list item bullets and number markers:
        $marker_ul  = '[*+-]';
        $marker_ol  = '\d+[.]';
        $marker_any = "(?:$marker_ul|$marker_ol)";
    
        $list = $matches[1];
        $list_type = preg_match("/$marker_ul/", $matches[3]) ? "ul" : "ol";
    
        $marker_any = ( $list_type == "ul" ? $marker_ul : $marker_ol );
    
        // Turn double returns into triple returns, so that we can make a
        // paragraph for the last item in a list, if necessary:
        $list = preg_replace("/\n{2,}/", "\n\n\n", $list);
        
        // process the list items and tokenize
        $result = $this->_processItems($list, $marker_any);
        $result = "\n"
                . $this->_tokenize("<$list_type>")
                . "\n"
                . $result
                . $this->_tokenize("</$list_type>")
                . "\n\n";
        
        // done
        return $result;
    }


    /**
     * 
     * Process the contents of a single ordered or unordered
     * list, splitting it into individual list items.
     * 
     * @param string $list_str The source text of the list block.
     * 
     * @param string $marker_any The list-style markers to use when
     * identifying list items.
     * 
     * @return string The replacement text.
     * 
     */
    protected function _processItems($list_str, $marker_any)
    {
        // The $this->_list_level global keeps track of when we're inside a list.
        // Each time we enter a list, we increment it; when we leave a list,
        // we decrement. If it's zero, we're not in a list anymore.
        //
        // We do this because when we're not inside a list, we want to treat
        // something like this:
        //
        //        I recommend upgrading to version
        //        8. Oops, now this line is treated
        //        as a sub-list.
        //
        // As a single paragraph, despite the fact that the second line starts
        // with a digit-period-space sequence.
        //
        // Whereas when we're inside a list (or sub-list), that line will be
        // treated as the start of a sub-list. What a kludge, huh? This is
        // an aspect of Markdown's syntax that's hard to parse perfectly
        // without resorting to mind-reading. Perhaps the solution is to
        // change the syntax rules such that sub-lists must start with a
        // starting cardinal number; e.g. "1." or "a.".
    
        $this->_list_level ++;

        # trim trailing blank lines:
        $list_str = preg_replace("/\n{2,}\\z/", "\n", $list_str);

        $list_str = preg_replace_callback('{
                (\n)?                          # leading line = $1
                (^[ \t]*)                      # leading whitespace = $2
                ('.$marker_any.') [ \t]+       # list marker = $3
                ((?s:.+?)                      # list item text   = $4
                (\n{1,2}))
                (?= \n* (\z | \2 ('.$marker_any.') [ \t]+))
            }xm',
            array($this, '_processItemsCallback'),
            $list_str
        );

        $this->_list_level --;
        return $list_str;
    }
    
    /**
     * 
     * Support callback for processing list items.
     * 
     * @param array $matches Matches from preg_replace_callback().
     * 
     * @return string The replacement text.
     * 
     */
    protected function _processItemsCallback($matches)
    {
        $item = $matches[4];
        $leading_line =& $matches[1];
        $leading_space =& $matches[2];

        if ($leading_line || preg_match('/\n{2,}/', $item)) {
            $item = $this->_processBlocks($this->_outdent($item));
        } else {
            // Recursion for sub-lists:
            $item = $this->parse($this->_outdent($item));
            $item = preg_replace('/\n+$/', '', $item);
            $item = $this->_processSpans($item);
        }

        return $this->_tokenize("<li>")
              . $item
              . $this->_tokenize("</li>")
              . "\n";
    }
}
?>