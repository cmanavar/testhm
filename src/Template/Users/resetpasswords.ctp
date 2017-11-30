<?php echo $this->element('js/datatable-delete'); ?>
<?php echo $this->Html->script('patient-dropdown.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>
<div class="login-window-area">
    <div id="container">
        <div id="content">
            <div id="login-page-wraper" class="login-page" style="padding-top:20px;">

                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center" style="padding: 5px 0px; background: none;">
                        <a href="#" class="login_image">
                            <h1><b>H-Men</b></h1>
                        </a>
                    </div>    
                </div>
                <div class="row">
                    <!-- Welcome Message -->
                    <div class="col-md-4 col-md-offset-4">
                        <div class=" login-box">
                            <h1 class="text-center fontFamily">RESET PASSWORD</h1>
                            <div class="panel-body">
                                <?php echo $this->Form->create('', ['class' => 'form-horizontal validate']); ?>
                                <div class="row">
                                    <?php echo ($this->Flash->render()); ?>
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
                                <div class="form-group">
                                    <label>NEW PASSWORD</label>
                                    <span class="icon_for_text"><i class="fa fa-lock"></i></span>
                                    <?php echo $this->Form->input('password', ['label' => false, 'type' => 'password', 'id' => 'pwd', 'class' => 'form-control required', 'placeholder' => 'ENTER NEW PASSWORD']); ?> 
                                </div>
                                <div class="form-group lg-input">
                                    <label>CONFIRM PASSWORD</label>
                                    <span class="icon_for_text"><i class="fa fa-lock"></i></span>
                                    <?php echo $this->Form->input('confirm_password', ['label' => false, 'type' => 'password', 'equalTo' => '#pwd', 'class' => 'form-control required', 'placeholder' => 'ENTER CONFIRM PASSWORD']); ?> 

                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success  btn-lg btn-block fontFamily" value="SET PASSWORD" name="">
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