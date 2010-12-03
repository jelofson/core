<?php
/**
 * 
 * Authenticate against an IMAP or POP3 mail server.
 * 
 * @category Solar
 * 
 * @package Solar_Auth
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id$
 * 
 */
class Solar_Auth_Storage_Adapter_Mail extends Solar_Auth_Storage_Adapter
{
    /**
     * 
     * Default configuration values.
     * 
     * @config string mailbox An imap_open() mailbox string, for example
     *   "mail.example.com:143/imap" or "mail.example.com:110/pop3".
     * 
     * @var array
     * 
     */
    protected $_Solar_Auth_Storage_Adapter_Mail = array(
        'mailbox' => null,
    );
    
    /**
     * 
     * Checks to make sure the IMAP extension is available.
     * 
     * @return void
     * 
     */
    protected function _preConfig()
    {
        parent::_preConfig();
        if (! extension_loaded('imap')) {
            throw $this->_exception('ERR_EXTENSION_NOT_LOADED', array(
                'extension' => 'imap',
            ));
        }
    }
    
    /**
     * 
     * Verifies set of credentials.
     *
     * @param array $credentials A list of credentials to verify
     * 
     * @return mixed An array of verified user information, or boolean false
     * if verification failed.
     * 
     */
    public function validateCredentials($credentials)
    {
    
        if (empty($credentials['handle'])) {
            return false;
        }
        if (empty($credentials['passwd'])) {
            return false;
        }
        $handle = $credentials['handle'];
        $passwd = $credentials['passwd'];
    
        $mailbox = '{' . $this->_config['mailbox'] . '}';
        $conn = @imap_open($mailbox, $handle, $passwd, OP_HALFOPEN);
        if (is_resource($conn)) {
            @imap_close($conn);
            return array('handle' => $handle);
        } else {
            return false;
        }
    }
}
