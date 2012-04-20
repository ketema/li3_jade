<?php

use \lithium\net\http\Media;

Media::type( 'jade', 'text/html', array(
    'view' => 'lithium\template\View',
    'loader' => 'Jade',
    'renderer' => 'Jade',
    'paths' => array(
        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
        'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
        'element'  => '{:library}/views/elements/{:template}.{:type}.php'
    )
));

?>
