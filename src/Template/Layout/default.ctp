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
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<?php
$action = $this->request->params['action'];

$controller = $this->request->params['controller'];

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $controller; ?> - Admin Panel </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <link rel="icon" href="<?php echo $this->request->webroot; ?>webroot/favicon.png" type="image/x-icon" />
        <?php if ($_SERVER['HTTP_HOST'] != "localhost") : ?>
            <?php if (!isset($_REQUEST['hidden_live'])) : ?>
                <style>
                    /* body .hidden_live{display:none}*/
                </style>
            <?php endif; ?><?php endif; ?>
        <?php echo $this->Html->css('bootstrap.min.css'); ?>
        <?php //echo $this->Html->css('customresponsive.css'); ?>
        <?php //echo $this->Html->css('bootstrap-select.css'); ?>
        <?php //echo $this->Html->css('plugins/metisMenu/metisMenu.min.css');?>

        <?php echo $this->Html->css('sb-admin-2.css'); ?>
        <?php echo $this->Html->css('font-awesome-4.1.0/css/font-awesome.min.css'); ?>
        <?php echo $this->Html->css('custom/custom.css'); ?>
        <?php echo $this->Html->css('custom/menu/bootstrap-theme.min.css'); ?>
        <?php echo $this->Html->css('custom/menu/yamm.css'); ?>
        <?php echo $this->Html->css('custom/menu/yamm.css'); ?>
        <?php echo $this->Html->css('custom/green.css'); ?>



    </head>
    <body>
        <div id="container">

            <div id="content">
                <!-- START PAGE SIDEBAR --> 
                <?php echo $this->element('page_sidebar'); ?>  
                <?php echo $this->fetch('content'); ?>

            </div>
        </div>
        <?php //echo $this->element('sql_dump');  ?>
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


        <script>
            $(function () {
                window.prettyPrint && prettyPrint()
                $(document).on('click', '.yamm .dropdown-menu', function (e) {
                    e.stopPropagation()
                });

            })
        </script>

    </body>
</html>
