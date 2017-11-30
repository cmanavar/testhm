<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>

<html lang="en">
    <head> 
        <?php echo $this->Html->charset(); ?>       
        <!-- META SECTION -->
        <title>Admin Panel - Login</title> 
        <?php
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>           
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="<?php echo $this->request->webroot; ?>webroot/favicon.png" type="image/x-icon" />
        <!-- END META SECTION -->

        <!-- CSS INCLUDE -->     
        <?php //echo $this->Html->css('theme-default.css');?>    
        <?php
        //echo $this->Html->css('bower_components/font-awesome/css/font-awesome.min.css');
        //echo $this->Html->css('bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet');
        echo $this->Html->css('bower_components/bootstrap/dist/css/bootstrap.min.css');
        echo $this->Html->css('dist/css/sb-admin-2.css');
        echo $this->Html->css('custom/custom.css');
        ?>      
        <!-- EOF CSS INCLUDE -->                                      
    </head>
    <body>

        <?php echo $this->fetch('content'); ?>
        <?php
        echo $this->Html->script(array('hmen/jquery.js',
            'bootstrap.min.js',
            'validate/jquery.validate.js',
            // 'custom/custom-validation-form.js',
            'bootstrap-typeahead.js',
            'custom/custom.js', 'custom/pagesidebar.js'
                //'custom/custom-validation-datatable.js',
        ));
        ?>

        <?php
        // echo $this->Html->script(array('plugins.js','actions.js'));
        echo $this->fetch('scriptBottom');
        ?>

    </body>
</html>