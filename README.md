# A lithium jade template compiler and renderer #
## Usage: ##  
Add to your li3 project in the <pre>libraries/jade/</pre> folder
and adjust the bootstrap/libraries.php file  
<pre>
 //Jade
 Libraries::add('jade');
 Libraries::add('li3_jade');
</pre>
A jade library configured to work with li3_jade can be found [here](https://github.com/ketema/jade.php)
### Create a template ###
<pre>
 :php
   | echo "This is inline php."

 h2 The Jade template engine Rocks
 p(style='white-space:pre;')
   | Jade makes writing HTML EASY and Beautiful
   | If you don't know about it
   a(href="http://jade-lang.com/") Get on it!
</pre>
### Render the template from a controller ###
<pre>
 &LT?php
  	namespace app\controllers;
  	
  	Class JadeController extends \lithium\action\Controller
  	{
  		public function index()
  		{
  			$this->render(array(
  				'template' => 'myjadetemplate',
  				'type' => 'jade',
  			));
  		}
  	}
 ?&gt
</pre>