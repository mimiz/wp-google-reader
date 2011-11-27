<?php 
/**
 * Class to retreive your rss feeds used in Google Reader
 * thanks to :
 * @see http://www.niallkennedy.com/blog/2005/12/google-reader-api.html 
 * @see http://code.google.com/p/pyrfeed/wiki/GoogleReaderAPI
 * 
 * @author Rémi Goyard
 * @category   Core
 * @package    Core_Gdata
 * @subpackage Gdata
 * @copyright  Rémi Goyard
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    0.3
 *
 * Changelog : 
 * 0.1 : First version
 * 0.2 : add query param support (for number ...)
 * 0.3 : add Tag support
 */
/**
 * Zend_Gdata_HttpClient
 */
require_once 'Zend/Http/Client.php';

/**
 * Zend_Version
 */
require_once 'Zend/Version.php';

/**
 * Core_Gdata_Feed
 */
require_once 'Core/Gdata/Feed.php';

class Core_Gdata_Reader{
	
	 /**
     * The Google client login URI
     */
    const CLIENTLOGIN_URI = 'https://www.google.com/accounts/ClientLogin';

    /**
     * The default 'source' parameter to send to Google
     */
    const DEFAULT_SOURCE = 'Zend-ZendFramework-Google-Reader 1.0/Zend';
    
    /**
     * The Google reader Base URI
     */
    const GOOGLE_READER_BASE_URI = "http://www.google.com/reader/";
    
	
    /**
     * Default Google Reader Actions
     */
    private $GOOGLE_READER_ACTIONS = array(
    	"subscriptions" =>"atom/user/[USERID]/pref/com.google/subscriptions",
    	"starred" =>"atom/user/[USERID]/state/com.google/starred",
    	"shared" => "atom/user/[USERID]/state/com.google/broadcast",
    	"broadcast" => "atom/user/[USERID]/state/com.google/broadcast",
    	"read" => "atom/user/[USERID]/state/com.google/read", 
    	"reading-list" => "atom/user/[USERID]/state/com.google/reading-list",
    	"tag" => "atom/user/[USERID]/label/[TAG]"
    );
    
    /**
     * Default Params
     */
    private $_defaultParams = array("n"=>20);
    
    const SERVICE_NAME = "reader";
    
    private $_userID = "";
    private $_email = "";
    private $_password = ""; 
    
    /**
     * @var Zend_Http_Client
     */
    private $_client = null;
    
    
    /**
     * 
     * @param string $email
     * @param string $password
     * @param string $userID
     */
    public function __construct($email, $password, $params = array()){
   		if (! ($email && $password)) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception(
                   'Please set your Google credentials before trying to ' .
                   'authenticate');
        }
    	if(! empty($params))
    	{
    		$this->_defaultParams = $params;
    	}
        $this->_email = $email;
        $this->_password = $password;
        $this->_userID = "-";
        $this->_setHttpClient();
        
    }
	private function _setHttpClient()
	{
	
		if (! ($this->_email && $this->_password)) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception(
                   'Please set your Google credentials before trying to ' .
                   'authenticate');
        }
		$this->_client = Zend_Gdata_ClientLogin::getHttpClient($this->_email,$this->_password,self::SERVICE_NAME);
		
	}
	
	
	
	/**
	 * Import Atom Feed
	 * 
	 * @param String $type request type [subscriptions","starred","shared","broadcast","read", "reading-list"]
    	"tag" ]
	 * @param Array $params request params
	 * @return Zend_Feed_Abstract
	 */
	public function import($type, $tag = '')
	{
		if(array_key_exists($type, $this->GOOGLE_READER_ACTIONS)){
			
			// TODO : manage POST actions
			
			$url = $this->_setUrl($type, $tag);
			$gdata = new Zend_Gdata($this->_client);
			$gdata->getFeed($url, "Core_Gdata_Feed");
			return Core_Gdata_Feed::import($url);
		}else{
			require_once 'Zend/Exception.php';
            throw new Zend_Exception('The type '.$type.' does not exists or is not yet implemented');
		}
	}
	
	/**
	 * feed URL constructor, pass type and add params
	 * @param String $type
	 * @return String
	 */
	private function _setUrl($type, $tag = '')
	{
		
		$urlId = str_replace("[USERID]", $this->_userID, $this->GOOGLE_READER_ACTIONS[$type] ); 
		if($tag != '')
		{
			$urlId = str_replace("[TAG]", $tag, $urlId ); 
		}
		$u = self::GOOGLE_READER_BASE_URI.$urlId;
		$u = $u.'?'.http_build_query($this->_defaultParams);
		return $u;
	}
}
?> 