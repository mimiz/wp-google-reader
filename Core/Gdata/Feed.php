<?php 
/**
 * Class just created to prevent errors when using Zend_Feed with Zend_Gdata::getFeed() ...
 * calling some methods Gdata specifics so just override thoose methods with no data.
 * @author mimiz
 *
 */
class Core_Gdata_Feed extends Zend_Feed
{
	/**
	 * 
	 * @param unknown_type $majorProtocolVersion
	 */
	public function setMajorProtocolVersion($majorProtocolVersion)
	{
		
	}
    public function setMinorProtocolVersion($minorProtocolVersion){
    	
    }
    public function transferFromXML($string)
    {
    	
    }	
}


?>