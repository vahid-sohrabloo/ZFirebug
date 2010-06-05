<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 * @version    $Id: $
 */

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug_Plugin_Auth implements ZFirebug_Controller_Plugin_Debug_Plugin_Interface
{
	/**
	 * Contains plugin identifier name
	 *
	 * @var string
	 */
	protected $_identifier = 'auth';


	/**
	 * Contains Zend_Auth object
	 *
	 * @var Zend_Auth
	 */
	protected $_auth;


	/**
	 * Contains message object
	 *
	 * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
	 */

	public $message;

	/**
	 * Create ZFirebug_Controller_Plugin_Debug_Plugin_Auth
	 *
	 * @var string $user
	 * @var string $role
	 * @return void
	 */
	public function __construct(array $options = array())
	{
		$this->message = new Zend_Wildfire_Plugin_FirePhp_TableMessage($this->getTab());
		$this->message->setBuffered(true);
		$this->message->setHeader(array('Property','Value'));
		$this->message->setOption('includeLineNumbers', false);
		$this->_auth = Zend_Auth::getInstance();
	}

	/**
	 * Gets identifier for this plugin
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Gets menu tab for the Debugbar
	 *
	 * @return string
	 */
	public function getTab()
	{
		return ucfirst($this->getIdentifier());

	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{
		$username = 'Not Authed';
		$role = 'Unknown Role';

		if($this->_auth->hasIdentity()) {
			foreach($this->_auth->getIdentity() as $property=>$value){
				$this->message->addRow(array((string)$property,(string)$value));
			}
		}
		else{
			//			$this->message->setMessage('Not authorized');
		}
		return '';
	}
}