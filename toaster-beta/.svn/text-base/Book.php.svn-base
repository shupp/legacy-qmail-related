<?php

/**
 * Book 
 * 
 * Book is a class to process DocBook XML, and display using XSLT and BTS
 * (simple template class)
 * 
 * @package ToasterDoc
 * @copyright 2007 Bill Shupp
 * @author Bill Shupp <hostmaster@shupp.org> 
 * @license GPL 2.0  {@link http://www.gnu.org/licenses/gpl.txt}
 */

require_once 'BTS.php';
require_once 'PEAR.php';
require_once  'I18Nv2.php';
require_once  'I18Nv2/Negotiator.php';

/**
 * Book 
 * 
 * Book is a class to process DocBook XML, and display using XSLT and BTS
 * (simple template class)
 * 
 * @package DocBook
 * @copyright 2007 Bill Shupp
 * @author Bill Shupp <hostmaster@shupp.org> 
 * @license GPL 2.0  {@link http://www.gnu.org/licenses/gpl.txt}
 */
class Book
{

    /**
     * tpl 
     * 
     * BTS instance
     * 
     * @var mixed
     * @access public
     */
    public $tpl = null;
    /**
     * outline 
     * 
     * Outline loaded from outline.xml
     * 
     * @var mixed
     * @access public
     */
    public $outline = null;
    /**
     * rendererConfig 
     * 
     * Renderer configuration
     * 
     * @var mixed
     * @access public
     */
    public $rendererConfig = null;
    /**
     * renderersAvailable 
     * 
     * Renderers Available
     * 
     * @var mixed
     * @access public
     */
    public $renderersAvailable = null;
    /**
     * renderer 
     * 
     * Selected Renderer
     * 
     * @var mixed
     * @access public
     */
    public $renderer = null;
    /**
     * currentPage 
     * 
     * Current Page (from _REQUEST)
     * 
     * @var mixed
     * @access public
     */
    public $currentPage = null;
    /**
     * previousPage 
     * 
     * Previous Page (based on currentPage)
     * 
     * @var mixed
     * @access public
     */
    public $previousPage = null;
    /**
     * nextPage 
     * 
     * Next Page (based on currentPage)
     * 
     * @var mixed
     * @access public
     */
    public $nextPage = null;
    /**
     * pages 
     * 
     * All Pages, built from outline
     * 
     * @var mixed
     * @access public
     */
    public $pages = null;

    /**
     * languages
     * 
     * @var mixed
     * @access public
     */
    public $languages = null;

    /**
     * __construct 
     * 
     * Constructor
     * 
     * @access protected
     * @return void
     */
    function __construct() {
        $this->tpl = new BTS;
        // Supported Languages
        $this->languages = $this->simpleLoad('languages.xml');
        if(PEAR::isError($this->languages)) return $this->languages;
        // outline
        $this->outline = $this->simpleLoad('outline.xml');
        if(PEAR::isError($this->outline)) return $this->outline;
        // Renderers
        $this->rendererConfig = $this->simpleLoad('renderers.xml');
        if(PEAR::isError($this->rendererConfig)) return $this->rendererConfig;
        $this->sessionInit();
        $this->setLocale();
    }

    /**
     * setLocale 
     * 
     * Set Locate info using I18Nv2_Negotiator
     * 
     * @access protected
     * @return void
     */
    protected function setLocale() {
        // Not using negotiation for the time being, static selection only
        // $neg = &new I18Nv2_Negotiator;
        // I18Nv2::setLocale($neg->getLocaleMatch());
        if(isset($_GET['language'])) {
            $array = $this->xmlObjToArray($this->languages->xpath('language'), 'name');
            if(in_array($_GET['language'], $array)) {
                $_SESSION['language'] = $_GET['language'];
            }
        }
        // print_r($_GET['language']);exit;
        if(isset($_SESSION['language'])) {
            I18Nv2::setLocale($_SESSION['language']);
            bindtextdomain("messages", "./locale");
            bind_textdomain_codeset("messages", 'UTF-8');
            textdomain("messages");
        }
    }

    /**
     * sessionInit 
     * 
     * Session initialization
     * 
     * @access protected
     * @return void
     */
    protected function sessionInit() {
        ini_set('session.use_cookies',1);
        ini_set('session.use_trans_sid',0);
        session_name('bookSession');
        session_start();
    }

    /**
     * simpleLoad 
     * 
     * Generic Simple XML Loader
     * 
     * @param mixed $file 
     * @access protected
     * @return void
     */
    protected function simpleLoad($file) {
        $xml = $this->tpl->display($file, 1);
        if(!($temp = simplexml_load_string($xml)))
           return PEAR::raiseError("Error: cannot load $file");
        return $temp;
    }

    /**
     * xmlObjToArray 
     * 
     * Return search for and return simple array within an object
     * 
     * @param mixed $inObject 
     * @param mixed $item 
     * @access protected
     * @return void
     */
    protected function xmlObjToArray($inObject, $item) {
        $outArray = array();
        $count = 0;
        foreach($inObject as $key => $val) {
            foreach($val->$item as $key1 => $val1) {
                $outArray[$count] = (string)$val1;
                $count++;
            }
        }
        return $outArray;
    }

    /**
     * setPages 
     * 
     * Set currentPage, nextPage, previousPage based on _REQUEST and outline
     * 
     * @access protected
     * @return void
     */
    protected function setPages() {
        // Build array of pages from outline
        $chapters = $this->outline->xpath('chapter');
        $this->pages = $this->xmlObjToArray($chapters, 'pages');

        // See if page was requested and set if appropriate
        if(isset($_REQUEST['page'])) {
            // See if all was requested
            if($_REQUEST['page'] == 'all') {
                $this->currentPage = 'all';
                return;
            }
            if(in_array($_REQUEST['page'], $this->pages)) {
                $this->currentPage = $_REQUEST['page'];
            }
        }
        // Otherwise, set default
        if(!isset($this->currentPage)) $this->currentPage = $this->outline->defaultPage;

        // Find previus and next pages
        $curKey = null;
        foreach($this->pages as $key => $val) {
            if($val == $this->currentPage) {
                $curKey = $key;
                break;
            }
        }
        // Set values
        if($curKey > 0) $this->previousPage = $this->pages[$curKey - 1];
        if($curKey < (count($this->pages) - 1)) $this->nextPage = $this->pages[$curKey + 1];
    }

    /**
     * setRenderer 
     * 
     * Choose renderer based on _REQUEST['renderer']
     * 
     * @access protected
     * @return void
     */
    protected function setRenderer() {
        $this->renderersAvailable = $this->xmlObjToArray($this->rendererConfig, 'type');
        if(isset($_REQUEST['renderer'])) {
            if(in_array($_REQUEST['renderer'], $this->renderersAvailable)) {
                $this->renderer = $_REQUEST['renderer'];
            }
        }
        if($this->renderer == null) 
            $this->renderer = $this->rendererConfig->defaultRenderer;
    }

    /**
     * display 
     * 
     * Display page
     * 
     * @access public
     * @return void
     */
    public function display() {
        $this->setPages();
        $this->setRenderer();
        $this->tpl->assign('previousPage', $this->previousPage);
        $this->tpl->assign('nextPage', $this->nextPage);
        $this->tpl->assign('defaultPage', (string)$this->outline->defaultPage);

        // Gather contents
        if($this->currentPage != 'all') {
            $contents = $this->tpl->display($this->currentPage . '.xml', 1);
        } else {
            $contents = '';
            foreach($this->pages as $key => $val) {
                $contents .= $this->tpl->display($val . '.xml', 1);
            }
        }

        $this->tpl->assign('contents', $contents);

        $xmlIn = $this->tpl->display((string)$this->outline->mainWrapper, 1);
        // Load the XML source
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xmlIn = "<?xml version='1.0' encoding='UTF-8'?>" . $xmlIn;
        $xml->loadXML($xmlIn);

        $xslIn = $this->tpl->display((string)$this->renderer . '.xsl', 1);
        $xslIn = "<?xml version='1.0' encoding='UTF-8'?>" . $xslIn;
        // Load the XSL source
        $xsl = new DOMDocument('1.0', 'UTF-8');
        $xsl->loadXML($xslIn);

        // Configure the transformer
        $proc = new XSLTProcessor;
        // attach the xsl rules
        $proc->importStyleSheet($xsl);

        echo $proc->transformToXML($xml);
        return;
    }

}
?>
