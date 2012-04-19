<?php

use \lithium\net\http\Media

Media::type( 'jade', 'text/x-jade', array(
    'view' => 'lithium\template\View',
    'loader' => 'Jade',
    'renderer' => 'Jade',
    'paths' => array(
        'template' => '{:library}/views/{:controller}/{:template}.{:type}',
        'layout'   => '{:library}/views/layouts/{:layout}.{:type}',
        'element'  => '{:library}/views/elements/{:template}.{:type}'
    )
));

?>
