<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 * @version    $Id: Text.php 62 2009-05-14 09:44:38Z gugakfugl $
 */

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug_Plugin_Text implements ZFirebug_Controller_Plugin_Debug_Plugin_Interface
{
	/**
	 * @var string
	 */
	protected $_tab = '';

	/**
	 * @var string
	 */
	protected $_panel = '';

	/**
	 * Contains plugin identifier name
	 *
	 * @var string
	 */
	protected $_identifier = 'text';


	/**
	 * Contains message object
	 *
	 * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
	 */

	public $message;

	/**
	 * Create ZFirebug_Controller_Plugin_Debug_Plugin_Text
	 *
	 * @param string $tab
	 * @paran string $panel
	 * @return void
	 */
	public function __construct(array $options = array())
	{
		$this->message = new Zend_Wildfire_Plugin_FirePhp_Message("INFO","");
		$this->message->setBuffered(true);
		if (isset($options['tab'])) {
			$this->setTab($tab);
		}
		if (isset($options['panel'])) {
			$this->setPanel($panel);
		}

		 
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
	 * Sets identifier for this plugin
	 *
	 * @param string $name
	 * @return ZFirebug_Controller_Plugin_Debug_Plugin_Text Provides a fluent interface
	 */
	public function setIdentifier($name)
	{
		$this->_identifier = $name;
		return $this;
	}

	/**
	 * Gets menu tab for the Debugbar
	 *
	 * @return string
	 */
	public function getTab()
	{
		return $this->_tab;
	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{
		return $this->_panel;
	}

	/**
	 * Sets tab content
	 *
	 * @param string $tab
	 * @return ZFirebug_Controller_Plugin_Debug_Plugin_Text Provides a fluent interface
	 */
	public function setTab($tab)
	{
		$this->_tab = $tab;
		$this->message->setLabel($tab);
		return $this;
	}

	/**
	 * Sets panel content
	 *
	 * @param string $panel
	 * @return ZFirebug_Controller_Plugin_Debug_Plugin_Text Provides a fluent interface
	 */
	public function setPanel($panel)
	{
		$this->_panel = $panel;
		$this->message->setMessage($panel);
		return $this;
	}
}