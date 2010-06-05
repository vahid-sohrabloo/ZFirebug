<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 * @version    $Id: File.php 62 2009-05-14 09:44:38Z gugakfugl $
 */

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug_Plugin_File implements ZFirebug_Controller_Plugin_Debug_Plugin_Interface
{
    /**
     * Contains plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'file';

    /**
     * Base path of this application
     * String is used to strip it from filenames
     *
     * @var string
     */
    protected $_basePath;

    /**
     * Stores included files
     *
     * @var array
     */
    protected $_includedFiles = null;

    /**
     * Stores name of own extension library
     *
     * @var string
     */
    protected $_library;
    
    /**
	 * Contains message object
	 *
	 * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
	 */

	public $message;

    /**
     * Setting Options
     *
     * basePath:
     * This will normally not your document root of your webserver, its your
     * application root directory with /application, /library and /public
     *
     * library:
     * Your own library extension(s)
     *
     * @param array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
    	
    	$this->message = new Zend_Wildfire_Plugin_FirePhp_TableMessage($this->getTab());
		$this->message->setBuffered(true);
		$this->message->setHeader(array('File'));
		$this->message->setOption('includeLineNumbers', false);
		
        isset($options['base_path']) || $options['base_path'] = $_SERVER['DOCUMENT_ROOT'];
        isset($options['library']) || $options['library'] = null;
        
        $this->_basePath = $options['base_path'];
        is_array($options['library']) || $options['library'] = array($options['library']);
        $this->_library = array_merge($options['library'], array('Zend', 'ZFirebug'));
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
    	
    	$included = $this->_getIncludedFiles();
        $size = 0;
        foreach ($included as $file) {
            $size += filesize($file);
        }
        return  ucfirst($this->getIdentifier()).' : '.count($included) . ' Files - Total Size: '. round($size/1024, 1).'K : Basepath: ' . $this->_basePath;
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        $included = $this->_getIncludedFiles();
        $libraryFiles = array();
        foreach ($included as $file) {
            $file = str_replace($this->_basePath, '', $file);
           	$this->message->addRow(array($file));
        }

    	$this->message->addRow($libraryFiles);

    }

    /**
     * Gets included files
     *
     * @return array
     */
    protected function _getIncludedFiles()
    {
        if (null !== $this->_includedFiles) {
            return $this->_includedFiles;
        }

        $this->_includedFiles = get_included_files();
        sort($this->_includedFiles);
        return $this->_includedFiles;
    }
}