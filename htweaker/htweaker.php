<?php



if (!defined('_PS_VERSION_'))

	exit;



class htweaker extends Module

{



	protected $_errors = array();



	/* Set default configuration values here */

	protected $_config = array(

		'HTWEAKER_BEFORE' => '',

		'HTWEAKER_AFTER' => '',

		);





	public function __construct()

	{

		$this->name = 'htweaker';

		$this->tab = 'front_office_features';

		$this->version = '1.0';

		$this->author = 'dh42';

		$this->need_instance = 0;




		$this->bootstrap = true;




	 	parent::__construct();



		$this->displayName = $this->l('.htaccess Editor');

		$this->description = $this->l('Allows you to add rules before and after the htaccess. From dh42 PrestaShop Certified Partner and PrestaShop support Experts.');

		$this->confirmUninstall = $this->l('Are you sure you want to delete this module?');

	}



	public function install()

	{

		if (!parent::install() OR

			!$this->_installConfig() OR

			!$this->registerHook('actionHtaccessCreate') OR

			!$this->registerHook('displayBackOfficeHeader')

			)

			return false;

		return true;

	}



	public function uninstall()

	{

		if (!parent::uninstall() OR

			!$this->_eraseConfig()

			)

			return false;

		return true;

	}



	private function _installConfig()

	{

		foreach ($this->_config as $keyname => $value) {

			Configuration::updateValue(strtoupper($keyname), $value);

		}

		return true;

	}



	private function _eraseConfig()

	{

		foreach ($this->_config as $keyname => $value) {

			Configuration::deleteByName(strtoupper($keyname));

		}

		return true;

	}









	public function getContent()

	{



		if (version_compare(@_PS_VERSION_,'1.6','<'))

			$this->_html = '<h2>'.$this->displayName.'</h2>';



		$this->_postProcess();



		// Set all variables to be used in the form here

		$vars = array();

		$vars['config'] = $this->getConfig();



		if (version_compare(@_PS_VERSION_,'1.6','>'))

			$this->_displayForm($vars);

		else

			$this->_displayForm15($vars);



		return	$this->_html;

	}



	private function _displayForm($vars)

	{



		$this->context->controller->addJS($this->_path.'/js/ace.js', 'all');

		// set in getContent();

		extract($vars);





		$this->_html .= '



	  <style type="text/css" media="screen">



	    #htaccessbefore, #htaccessafter {

	        margin: 0;

	        position: absolute;

	        top: 0;

	        bottom: 0;

	        left: 0;

	        right: 0;

	    }

	  </style>

<div style="width:100%;height:100px;">

<div style="width:33.3%;text-align:left;float:left;height:100px">

<ins data-revive-zoneid="1" data-revive-id="27f1a68d9b3c239bbbd38cc09b79d453"></ins>

<script async src="//dh42.com/openx/www/delivery/asyncjs.php"></script>

</div>



<div style="width:33.3%;text-align:center;float:left;height:100px">

<ins data-revive-zoneid="2" data-revive-id="27f1a68d9b3c239bbbd38cc09b79d453"></ins>

<script async src="//dh42.com/openx/www/delivery/asyncjs.php"></script>

</div>



<div style="width:33.3%;text-align:center;float:right;height:100px">

<ins data-revive-zoneid="3" data-revive-id="27f1a68d9b3c239bbbd38cc09b79d453"></ins>

<script async src="//dh42.com/openx/www/delivery/asyncjs.php"></script>

</div>

</div>

			<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="defaultForm form-horizontal" enctype="multipart/form-data">



		    <div class="panel" style="margin-top:15px">



				<div class="panel-heading">'.$this->l('Settings').'</div>

		';



		$this->_html .='

				<div class="form-group" >

					<label class="control-label col-lg-3" style="font-weight:bold;text-align:left;margin-left:15px;">'.$this->l('Content to add at the beginning').'</label>

					<div class="col-lg-4" style="position:relative;height: 200px;width:100%">

						<div id="htaccessbefore">'.$config['HTWEAKER_BEFORE'].'</div>

						<textarea name="htaccessbefore"></textarea>

					</div>

				</div>

		';



		$this->_html .='

				<div class="form-group">

					<label class="control-label col-lg-3" style="font-weight:bold;text-align:left;margin-left:15px;">'.$this->l('Content to add at the end').'</label>

					<div class="col-lg-4" style="position:relative;height: 200px;width:100%">

						<div id="htaccessafter">'.$config['HTWEAKER_AFTER'].'</div>

						<textarea name="htaccessafter"></textarea>

					</div>

				</div>



		<script>



		var editor = ace.edit("htaccessbefore");

	    editor.setTheme("ace/theme/chrome");

	    editor.getSession().setMode("ace/mode/apache_conf");

	    var textarea = $(\'textarea[name="htaccessbefore"]\').hide();

	    textarea.text(editor.getSession().getValue());

		editor.getSession().on(\'change\', function(){

		  textarea.text(editor.getSession().getValue());

		});



		var editor2 = ace.edit("htaccessafter");

	    editor2.setTheme("ace/theme/chrome");

	    editor2.getSession().setMode("ace/mode/apache_conf");

	    var textarea2 = $(\'textarea[name="htaccessafter"]\').hide();

	    textarea2.text(editor2.getSession().getValue());

		editor2.getSession().on(\'change\', function(){

		  textarea2.text(editor2.getSession().getValue());

		});



		</script>

		';



		/* Submit button */

		$this->_html .='<p class="center"><input type="submit" name="submitUpdate" value="'.$this->l('Save and regenerate').'" class="btn btn-default"></p>';





		$this->_html .= '

				</div>

			</form>';

	}



	private function _displayForm15($vars)

	{



		$this->context->controller->addJS($this->_path.'/js/ace.js', 'all');

		// set in getContent();

		extract($vars);





		$this->_html .= '



	  <style type="text/css" media="screen">



	    #htaccessbefore, #htaccessafter {

	        margin: 0;

	        position: absolute;

	        top: 0;

	        bottom: 0;

	        left: 0;

	        right: 0;

	    }

	  </style>

			<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="defaultForm form-horizontal" enctype="multipart/form-data">



		    	<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>

		';



		$this->_html .='



					<label>'.$this->l('Content to add at the beginning').'</label>

					<div class="margin-form" style="position:relative;height: 300px; width: 700px;padding-left:0;margin-left: 260px">

						<div id="htaccessbefore">'.$config['HTWEAKER_BEFORE'].'</div>

						<textarea name="htaccessbefore"></textarea>

					</div>



		';



		$this->_html .='

				<p>&nbsp;</p>

					<label>'.$this->l('Content to add at the end').'</label>

					<div class="margin-form" style="position:relative;height: 300px; width: 700px;padding-left:0;margin-left: 260px">

						<div id="htaccessafter">'.$config['HTWEAKER_AFTER'].'</div>

						<textarea name="htaccessafter"></textarea>

					</div>





		<script>



		var editor = ace.edit("htaccessbefore");

	    editor.setTheme("ace/theme/chrome");

	    editor.getSession().setMode("ace/mode/apache_conf");

	    var textarea = $(\'textarea[name="htaccessbefore"]\').hide();

		editor.getSession().on(\'change\', function(){

		  textarea.text(editor.getSession().getValue());

		});



		var editor2 = ace.edit("htaccessafter");

	    editor2.setTheme("ace/theme/chrome");

	    editor2.getSession().setMode("ace/mode/apache_conf");

	    var textarea2 = $(\'textarea[name="htaccessafter"]\').hide();

		editor2.getSession().on(\'change\', function(){

		  textarea2.text(editor2.getSession().getValue());

		});



		</script>

		';



		/* Submit button */

		$this->_html .='<p class="center"><input type="submit" name="submitUpdate" value="'.$this->l('Save and regenerate').'" class="button"></p>';





		$this->_html .= '

				</fieldset>

			</form>';



	}



	private function _postProcess()

	{





		if (Tools::isSubmit('submitUpdate')) // handles the basic config update

		{



			if((bool)Configuration::get('PS_USE_HTMLPURIFIER'))

				$this->_errors[] = $this->l('Error: You have to disable the HTML purifier');



			if(!$this->_errors)

			{



				Configuration::updateValue('HTWEAKER_BEFORE', Tools::getValue('htaccessbefore'));

				Configuration::updateValue('HTWEAKER_AFTER', Tools::getValue('htaccessafter'));



				// presta headers

				$presta_headers = '# ~~start~~ Do not remove this comment';

				$presta_closing = '# ~~end~~ Do not remove this comment, Prestashop will keep automatically the code outside this comment when .htaccess will be generated again';

				// now add to the htaccess

				$htweaker_top_starts = '# ~htweaker_top~';

				$htweaker_bottom_starts = '# ~htweaker_bottom~';



				$top_content = Tools::getValue('htaccessbefore');

				$bottom_content = Tools::getValue('htaccessafter');



				$htweaker_top_ends = '# ~htweaker_top_end~';

				$htweaker_bottom_ends = '# ~htweaker_bottom_end~';





				if(!$htaccess = file_get_contents(_PS_ROOT_DIR_.'/.htaccess'))

				{

					// generate one

					Tools::generateHtaccess();

					$htaccess = file_get_contents(_PS_ROOT_DIR_.'/.htaccess');

				}



				// first, check if there is any htweaker rule already, and if so, erase it

				// top part

				$content_to_add_top = $htweaker_top_starts . "\n" . $top_content . "\n" . $htweaker_top_ends;



				if (preg_match('/\# ~htweaker_top~(.*?)\# ~htweaker_top_end~/s', $htaccess, $m))

				{

					$content_to_remove = $m[0];

					$htaccess = str_replace($content_to_remove, $content_to_add_top, $htaccess);

				}

				else // nothing found at the top, add it

					$htaccess = str_replace($presta_headers, $content_to_add_top . "\n\n" . $presta_headers, $htaccess);





				// bottom part

				$content_to_add_bottom = $htweaker_bottom_starts . "\n" . $bottom_content . "\n" . $htweaker_bottom_ends;

				if (preg_match('/\# ~htweaker_bottom~(.*?)\# ~htweaker_bottom_end~/s', $htaccess, $m))

				{

					$content_to_remove = $m[0];

					$htaccess = str_replace($content_to_remove, $content_to_add_bottom, $htaccess);

				}

				else // nothing found at the top, add it

					$htaccess = str_replace($presta_closing, $presta_closing . "\n\n" . $content_to_add_bottom, $htaccess);



				file_put_contents(_PS_ROOT_DIR_.'/.htaccess', $htaccess);



			}











			// Error handling

			if ($this->_errors) {

				$this->_html .= $this->displayError(implode($this->_errors, '<br />'));

			} else $this->_html .= $this->displayConfirmation($this->l('Settings Updated!'));

		}

	}



	public function getConfig()

	{

		$config_keys = array_keys($this->_config);

		return Configuration::getMultiple($config_keys);

	}








		public function hookDisplayBackOfficeHeader($params)



	{



		if( isset($this->context->controller->dh_support) )



			return;



		$this->context->controller->dh_support = 1;

		if (version_compare(@_PS_VERSION_,'1.6','<'))

			$this->context->controller->addJS($this->_path . '/dh42_15.js', 'all');

		else

			$this->context->controller->addJS($this->_path . '/dh42.js', 'all');



		return;







	}





}

