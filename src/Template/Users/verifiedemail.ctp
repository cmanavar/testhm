<?php echo $this->element('js/datatable-delete'); ?>
<?php echo $this->Html->script('patient-dropdown.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>
<div class="login-window-area">
    <div id="container">
        <div id="content">
            <div id="login-page-wraper" class="login-page" style="padding-top:20px;">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="col-md-12 col-xs-12 text-center" style="padding: 60px 0px; background: none;">
                            <?= $this->Html->image('logo/hlogo200px.png', ['height' => 54, 'width' => 200]) . "<br/>" ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Welcome Message -->
                    <div class="col-md-4 col-md-offset-4">
                        <div class="login-box">
                            <div class="text-center">
                                <?= $this->Html->image('logo/hlogo200px.png', ['height' => 54, 'width' => 200]); ?>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <?php if ($status != 'success') { ?>
                                        <?php echo ($this->Flash->render()); ?>
                                    <?php } ?>
                                    <?php
                                    // show error messages
                                    if (!empty($errors)) {
                                        echo '<div class="cake-error alert alert-danger"> <button data-dismiss="alert" class="close close-sm" type="button">
                                    <i class="fa fa-times"></i>
                                    </button><ul>';
                                        foreach ($errors as $e) {
                                            echo '<li>' . reset($e) . '</li>';
                                        }
                                        echo '</ul></div>';
                                    }
                                    ?>
                                </div>
                                <div class="row" style="padding: 2px 0px;">
                                    <div class="col-md-12">
                                        <h4 class="text-center capitalize" style="color: #000;"><?= strtoupper($msg); ?></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="<?= WEBSITE_PATH; ?>"><input type="submit" class="btn btn-success  btn-lg btn-block fontFamily" value="VISIT WEBSITE" name=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>