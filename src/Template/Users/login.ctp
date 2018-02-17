<?php echo $this->Html->css('login.css') ?>
<?php echo $this->Html->css('custom/custom.css'); ?>
<?php echo $this->Html->css('font-awesome-4.1.0/css/font-awesome.min.css'); ?>
<?php echo $this->element('js/datatable-delete'); ?>
<div class="login-window-area">
    <div id="container">
        <div id="content">
            <div id="login-page-wraper" class="login-page" style="padding-top:20px;">

                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center" style="padding: 60px 0px; background: none;">
                        <a href="#" class="login_image">
<!--                            <h1><b>H-Men</b></h1>-->
                        </a>
                    </div>    
                </div>
                <div class="row">
                    <!-- Welcome Message -->
                    <div class="col-md-4 col-md-offset-4">
                        <div class=" login-box">
                            <div class="text-center" style="padding:20px;">
                                <?= $this->Html->image('logo/hlogo.svg', ['height' => 54, 'width' => 200]); ?>
                            </div>
                            <div class="panel-body">
                                <?php echo $this->Form->create('User', ['class' => 'required validate']);
                                ?>
                                <div class="text-center">
                                    <?php echo ($this->Flash->render()); ?>
                                    <?php
                                    // show error messages
                                    if (!empty($errors)) {
                                        echo '<div class="cake-error alert alert-danger "> <button data-dismiss="alert" class="close close-sm" type="button">
                                    <i class="fa fa-times"></i>
                                    </button><ul>';
                                        foreach ($errors as $e) {
                                            echo '<li class="text-left">' . reset($e) . '</li>';
                                        }
                                        echo '</ul></div>';
                                    }
                                    ?></div>
                                <div class="form-group">
                                    <label style="color:#000;">EMAIL</label>
                                    <span class="icon_for_text"><i class="fa fa-user"></i></span>
                                    <?php echo $this->Form->input('email', ['label' => false, 'class' => 'form-control email custom-email required login-inputs', 'type' => 'text']); ?>
                                </div>
                                <div class="form-group lg-input">
                                    <label style="color:#000;">PASSWORD</label>
                                    <span class="icon_for_text">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    <?php echo $this->Form->input('password', ['label' => false, 'class' => 'form-control  required login-inputs', 'type' => 'password']); ?>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success  btn-lg btn-block fontFamily" value="SUBMIT" name="">
                                </div>
                                <?php echo $this->Form->end(); ?> 
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

