<?php

namespace li3_jade\extensions\adapter\template\view;

use \jade\Everzet\Jade\Jade as Renderer;
use \jade\Everzet\Jade\Parser;
use \jade\Everzet\Jade\Dumper\PHPDumper;
use \jade\Everzet\Jade\Visitor\AutotagsVisitor;
use \jade\Everzet\Jade\Filter\CDATAFilter;
use \jade\Everzet\Jade\Filter\PHPFilter;
use \jade\Everzet\Jade\Filter\CSSFilter;
use \jade\Everzet\Jade\Filter\JavaScriptFilter;
use \jade\Everzet\Jade\Lexer\Lexer;
use lithium\core\Libraries;

class Jade extends \lithium\template\view\adapter\File
{
    public function render( $template, $data = array(), array $options = array() )
    {
        $defaults = array('context' => array());
		$options += $defaults;

		$this->_context = $options['context'] + $this->_context;
		$this->_data = (array) $data + $this->_vars;
		$template__ = $template;
		unset($options, $template, $defaults, $data);

		if ($this->_config['extract']) {
			extract($this->_data, EXTR_OVERWRITE);
		} elseif ($this->_view) {
			extract((array) $this->_view->outputFilters, EXTR_OVERWRITE);
		}

        $compiledJade = $this->compile($template__);
		ob_start();
		include $compiledJade;
		return ob_get_clean();
    }

    /**
     * Returns a jade compiled cached template file name
     *
     * @param string $type
     * @param array $params
     * @return string
     */
    public function compile($file, array $options = array() )
    {
        $cachePath = Libraries::get(true, 'resources') . '/tmp/cache/templates';
		$defaults = array('path' => $cachePath, 'fallback' => true);
		$options += $defaults;
        $dumper = new PHPDumper();
        $dumper->registerVisitor('tag', new AutotagsVisitor());
        $dumper->registerFilter('javascript', new JavaScriptFilter());
        $dumper->registerFilter('cdata', new CDATAFilter());
        $dumper->registerFilter('php', new PHPFilter());
        $dumper->registerFilter('style', new CSSFilter());
        $parser = new Parser(new Lexer());
        $renderer = new Renderer($parser, $dumper);

		$stats = stat($file);
		$dir = dirname($file);
		$oname = basename(dirname($dir)) . '_' . basename($dir) . '_' . basename($file, '.php');
		$template = "template_{$oname}_{$stats['ino']}_{$stats['mtime']}_{$stats['size']}.php";
		$template = "{$options['path']}/{$template}";
        $compiledJade = $template.'.compiledJade.php';

		if(file_exists($compiledJade))
			return $compiledJade;

        $compiled = file_exists($template) ? $renderer->render(file_get_contents($template))
                                           : $renderer->render(file_get_contents($file));

        if (is_writable($cachePath) && file_put_contents($compiledJade, $compiled) !== false && copy($file,$template))
        {
			foreach (glob("{$options['path']}/template_{$oname}_*.php") as $expired) {
				if ( ! (bool) preg_match("@$template|$compiledJade@", $expired))
					unlink($expired);
			}
			return $compiledJade;
		}

		throw new TemplateException("Could not write compiled template `{$template}` to cache.");

    }

    /**
	 * Returns a template file name
	 *
	 * @param string $type
	 * @param array $params
	 * @return string
	 */
	public function template($type, array $params) {
		$params += array('library' => true);
		$params['library'] = Libraries::get($params['library'], 'path');
		return $this->_paths($type, $params);
	}

}

?>
