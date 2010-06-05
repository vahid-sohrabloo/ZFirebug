<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license    New BSD License
 */

/**
 * @see Zend_Controller_Exception
 */
require_once 'Zend/Controller/Exception.php';

/**
 * @see Zend_Version
 */
require_once 'Zend/Version.php';

/**
 * @see ZFirebug_Controller_Plugin_Debug_Plugin_Text
 */
require_once 'ZFirebug/Controller/Plugin/Debug/Plugin/Text.php';

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Contains registered plugins
	 *
	 * @var array
	 */
	protected $_plugins = array();

	/**
	 * Contains options to change Debuger behavior
	 */
	protected $_options = array(
        'plugins'           => array(
            'Variables' => null,
            'Time' => null,
            'Memory' => null),
	);

	/**
	 * Standard plugins
	 *
	 * @var array
	 */
	public static $standardPlugins = array('Html','File', 'Memory', 'Registry', 'Time', 'Variables',"Auth");

	/**
	 * Debuger Version Number
	 * for internal use only
	 *
	 * @var string
	 */
	protected $_version = '1.5';

	/**
	 * Creates a new instance of the Debuger
	 *
	 * @param array|Zend_Config $options
	 * @throws Zend_Controller_Exception
	 * @return void
	 */
	public function __construct($options = null)
	{
		if (isset($options)) {
			if ($options instanceof Zend_Config) {
				$options = $options->toArray();
			}

			/*
			 * Verify that adapter parameters are in an array.
			 */
			if (!is_array($options)) {
				throw new Zend_Exception('Debug parameters must be in an array or a Zend_Config object');
			}

			$this->setOptions($options);
		}

		/**
		 * Creating ZF Version Log
		 */
		$version = new ZFirebug_Controller_Plugin_Debug_Plugin_Text();
		$version->setPanel($this->_getVersionPanel())
		->setTab($this->_getVersionLable())
		->setIdentifier('copyright');
		$this->registerPlugin($version);

		/**
		 * Loading aready defined plugins
		 */
		$this->_loadPlugins();
	}

	/**
	 * Sets options of the Debuger
	 *
	 * @param array $options
	 * @return ZFirebug_Controller_Plugin_Debug
	 */
	public function setOptions(array $options = array())
	{
		if (isset($options['plugins'])) {
			$this->_options['plugins'] = $options['plugins'];
		}
		return $this;
	}

	/**
	 * Register a new plugin in the Debuger
	 *
	 * @param ZFirebug_Controller_Plugin_Debug_Plugin_Interface
	 * @return ZFirebug_Controller_Plugin_Debug
	 */
	public function registerPlugin(ZFirebug_Controller_Plugin_Debug_Plugin_Interface $plugin)
	{
		$this->_plugins[$plugin->getIdentifier()] = $plugin;
		return $this;
	}

	/**
	 * Unregister a plugin in the Debuger
	 *
	 * @param string $plugin
	 * @return ZFirebug_Controller_Plugin_Debug
	 */
	public function unregisterPlugin($plugin)
	{
		if (false !== strpos($plugin, '_')) {
			foreach ($this->_plugins as $key => $_plugin) {
				if ($plugin == get_class($_plugin)) {
					unset($this->_plugins[$key]);
				}
			}
		} else {
			$plugin = strtolower($plugin);
			if (isset($this->_plugins[$plugin])) {
				unset($this->_plugins[$plugin]);
			}
		}
		return $this;
	}

	/**
	 * Get a registered plugin in the Debuger
	 *
	 * @param string $identifier
	 * @return ZFirebug_Controller_Plugin_Debug_Plugin_Interface
	 */
	public function getPlugin($identifier)
	{
		$identifier = strtolower($identifier);
		if (isset($this->_plugins[$identifier])) {
			return $this->_plugins[$identifier];
		}
		return false;
	}

	/**
	 * Defined by Zend_Controller_Plugin_Abstract
	 */
	public function dispatchLoopShutdown()
	{
		
		$html = '';

		if ($this->getRequest()->isXmlHttpRequest())
		return;

		/**
		 * Creating menu tab for all registered plugins
		 */
		foreach ($this->_plugins as $plugin)
		{
			
			$plugin->getPanel();
			
			$message=$plugin->message;
			if(!$message){
				continue;
			}
			
			if ($message) {
				
				Zend_Wildfire_Plugin_FirePhp::getInstance()->send($message);
			}
		}

	}

	### INTERNAL METHODS BELOW ###

	/**
	 * Load plugins set in config option
	 *
	 * @return void;
	 */
	protected function _loadPlugins()
	{
		foreach($this->_options['plugins'] as $plugin => $options) {
			if (is_numeric($plugin)) {
				# Plugin passed as array value instead of key
				$plugin = $options;
				$options = array();
			}
			$plugin = (string)$plugin;
			if (in_array($plugin, ZFirebug_Controller_Plugin_Debug::$standardPlugins)) {
				// standard plugin
				$pluginClass = 'ZFirebug_Controller_Plugin_Debug_Plugin_' . $plugin;
			} else {
				// we use a custom plugin
				if (!preg_match('~^[\w]+$~D', $plugin)) {
					throw new Zend_Exception("ZFirebug: Invalid plugin name [$plugin]");
				}
				$pluginClass = $plugin;
			}

			require_once str_replace('_', DIRECTORY_SEPARATOR, $pluginClass) . '.php';
			$object = new $pluginClass($options);
			$this->registerPlugin($object);
		}
	}

	/**
	 * Return version
	 *
	 * @return string
	 */
	protected function _getVersionLable()
	{
		return 'Zend Version' . Zend_Version::VERSION . '/PHP version '.phpversion();
	}

	/**
	 * Returns version panel
	 *
	 * @return string
	 */
	protected function _getVersionPanel()
	{
		$panel = "ZFirebug v".$this->_version.
                 '©2010 - ' .
                 'The project is hosted at http://github.com/vahid-sohrabloo/ZFirebug and released under the BSD License- this project extent from Zfdebug ';
		return $panel;
	}

}