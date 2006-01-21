<?php
/**
 * 
 * Class to track code execution times.
 * 
 * @category Solar
 * 
 * @package Solar_Debug
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
 * Class to track code execution times.
 * 
 * @category Solar
 * 
 * @package Solar_Debug
 * 
 */
class Solar_Debug_Timer extends Solar_Base {
    
    /**
     * 
     * User-provided configuration.
     * 
     * Keys are:
     * 
     * html => (bool) enable/disable encoding output for HTML
     * 
     * @access protected
     * 
     * @var array
     * 
     */
    protected $_config = array(
        'locale' => 'Solar/Debug/Locale/',
        'output' => 'html',
    );
    
    /**
     * 
     * Array of time marks.
     * 
     * @access public
     * 
     * @var array
     * 
     */
    protected $_marks = array();
    
    /**
     * 
     * When we call profile(), what is the longest marker name length?
     * 
     * @access protected
     * 
     * @var boolean
     * 
     */
    protected $_maxlen = 8;
    
    /**
     * 
     * Solar hooks when instantiated as an autoshared object.
     * 
     * @access public
     * 
     * @param string $hook The hook name to activate.
     * 
     * @return void
     * 
     */
    public function solar($hook)
    {
        switch ($hook) {
        case 'start':
            $this->start();
            break;
        case 'stop':
            $this->stop();
            break;
        }
    }
    
    /**
     * 
     * Resets the profile and marks a new starting time.
     * 
     * @access public
     * 
     */
    public function start()
    {
        $this->_marks = array();
        $this->mark('__start');
    }
    
    /**
     * 
     * Stop the timer.
     * 
     * @access public
     * 
     */
    public function stop()
    {
        $this->mark('__stop');
    }
    
    /**
     * 
     * Mark the time.
     * 
     * @access public
     * 
     * @param string $name Name of the marker to be set
     * 
     * @return void
     * 
     */
    public function mark($name)
    {
        $this->_marks[$name] = microtime(true);
    }
    
    /**
     * 
     * Returns profiling information as an array.
     * 
     * @access public
     * 
     * @return array An array of profile information.
     * 
     */
    public function profile()
    {
        // previous time
        $prev = 0;
        
        // total elapsed time
        $total = 0;
        
        // result array
        $result = array();
        
        // loop through all the marks
        foreach ($this->_marks as $name => $time) {
            
            // figure the time difference
            $diff = $time - $prev;
            
            // keep a running total; we always start at zero time.
            if ($name == '__start') {
                $total = 0;
            } else {
                $total = $total + $diff;
            }
            
            // record the profile result for this iteration
            $result[] = array(
                'name'  => $name,
                'time'  => $time,
                'diff'  => $diff,
                'total' => $total
            );
            
            // track the longest marker name
            if (strlen($name) > $this->_maxlen) {
                $this->_maxlen = strlen($name);
            }
            
            // track the previous time
            $prev = $time;
        }
        
        // by definition, the starting-point time-difference is zero
        $result[0]['diff'] = 0;
        
        // done!
        return $result;
    }
    
    /**
     * 
     * Outputs the current profile.
     * 
     * @access public
     * 
     * @param string $title A title for the output.
     * 
     * @return void
     * 
     */
    public function display($title = null)
    {
        // get the profile info
        $profile = $this->profile();
        
        // format the localized column names
        $colname = array(
            'name'  => $this->locale('LABEL_NAME'),
            'time'  => $this->locale('LABEL_TIME'),
            'diff'  => $this->locale('LABEL_DIFF'),
            'total' => $this->locale('LABEL_TOTAL')
        );
        
        foreach ($colname as $key => $val) {
            // reduce to max 8 chars
            $val = substr($val, 0, 8);
            // pad to 8 spaces
            $colname[$key] = str_pad($val, 8);
        }
        
        // prep the output rows
        $row = array();
        
        // add a title
        if (trim($title != '')) {
            $row[] = $title;
        }
        
        // add the column names
        $row[] = sprintf(
            "%-{$this->_maxlen}s : {$colname['diff']} : {$colname['total']}",
            $colname['name']
        );
        
        // add each timer mark
        foreach ($profile as $key => $val) {
            $row[] = sprintf(
                "%-{$this->_maxlen}s : %f : %f",
                $val['name'],
                $val['diff'],
                $val['total']
            );
        }
        
        // finalize output and display
        $output = implode("\n", $row);
        
        if ($this->_config['output'] == 'html') {
            $output = '<pre>' . htmlspecialchars($output) . '</pre>';
        }
        
        echo $output;
    }
}
?>