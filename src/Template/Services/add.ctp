<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>
<?php echo $this->Html->css('jquery-ui.css'); ?>
<?php echo $this->Html->script('jquery-ui.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('maskedinput.js', ['block' => 'scriptBottom']); ?>
<?php echo $this->Html->script('custom/upload_image.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('custom/dashboard.js', ['block' => 'scriptBottom']); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-asterisk fa-fw"></i>SERVICES
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="col-lg-12">
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
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php if ($this->request->action == 'add') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> SERVICE 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($service, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NAME <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('service_name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER SERVICE NAME', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">DESCRIPTION <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('service_description', ['label' => false, 'class' => 'form-control required normal-font service-descriptions', 'placeholder' => 'ENTER SERVICE DESCRIPTION ', 'rows' => 4]); ?>
                                    </div>
                                </div>
                            </div>
<!--                            <div class="form-group">
                                <label class="col-sm-3 control-label">DESCRIPTION 1 </label>
                                <div class="col-sm-2">
                                    <div class="custom-radio radio ">
                                        <?php
                                        if (isset($service['icon_1']) && $service['icon_1'] != '') {
                                            echo $this->Html->image(SERVICE_ICON_PATH . $service['icon_1'], ['height' => 50, 'width' => 80]) . "<br/>";
                                            ?>
                                            <br/>
                                            <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'deleteimage', 'icon_1', $service['icon_1']]) ?> data-value="<?php echo $service['icon_1']; ?>" data-target="#delete" href="#"  class="btn btn-danger btn-sm delete ">REMOVE IMAGE</a>
                                            <?php
                                        } else {
                                            echo $this->Form->input('icon_1', ['label' => false, 'type' => 'file', 'class' => 'imgpreview', 'id' => 'icon_1']);
                                            ?>
                                            <br/>
                                            <div class="imageblock">
                                                <div class="form-group hover-element scanimgblock">
                                                    <div class="col-sm-8 td-inputbox">
                                                        <?php echo $this->Html->image('upload_image.png', ['class' => 'banner_upload', 'alt' => 'Your image', 'id' => 'icon_1_upload_preview', 'height' => '45']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?> 
                                    </div>  
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Description 1</label>
                                            <?php echo $this->Form->input('desc_heading_1', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION HEADING 1', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php echo $this->Form->input('desc_text_1', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION TEXT 1', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">DESCRIPTION 2 </label>
                                <div class="col-sm-2">
                                    <div class="custom-radio radio ">
                                        <?php
                                        if (isset($service['icon_2']) && $service['icon_2'] != '') {
                                            echo $this->Html->image(SERVICE_ICON_PATH . $service['icon_2'], ['height' => 50, 'width' => 80]) . "<br/>";
                                            ?>
                                            <br/>
                                            <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'deleteimage', 'icon_2', $service['icon_2']]) ?> data-value="<?php echo $service['icon_2']; ?>" data-target="#delete" href="#"  class="btn btn-danger btn-sm delete ">REMOVE IMAGE</a>
                                            <?php
                                        } else {
                                            echo $this->Form->input('icon_2', ['label' => false, 'type' => 'file', 'class' => 'imgpreview', 'id' => 'icon_2']);
                                            ?>
                                            <br/>
                                            <div class="imageblock">
                                                <div class="form-group hover-element scanimgblock">
                                                    <div class="col-sm-8 td-inputbox">
                                                        <?php echo $this->Html->image('upload_image.png', ['class' => 'banner_upload', 'alt' => 'Your image', 'id' => 'icon_2_upload_preview', 'height' => '45']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?> 
                                    </div>  
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Description 2</label>
                                            <?php echo $this->Form->input('desc_heading_2', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION HEADING 2', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php echo $this->Form->input('desc_text_2', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION TEXT 2', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">DESCRIPTION 3 </label>
                                <div class="col-sm-2">
                                    <div class="custom-radio radio ">
                                        <?php
                                        if (isset($service['icon_3']) && $service['icon_3'] != '') {
                                            echo $this->Html->image(SERVICE_ICON_PATH . $service['icon_3'], ['height' => 50, 'width' => 80]) . "<br/>";
                                            ?>
                                            <br/>
                                            <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'deleteimage', 'icon_3', $service['icon_3']]) ?> data-value="<?php echo $service['icon_3']; ?>" data-target="#delete" href="#"  class="btn btn-danger btn-sm delete ">REMOVE IMAGE</a>
                                            <?php
                                        } else {
                                            echo $this->Form->input('icon_3', ['label' => false, 'type' => 'file', 'class' => 'imgpreview', 'id' => 'icon_3']);
                                            ?>
                                            <br/>
                                            <div class="imageblock">
                                                <div class="form-group hover-element scanimgblock">
                                                    <div class="col-sm-8 td-inputbox">
                                                        <?php echo $this->Html->image('upload_image.png', ['class' => 'banner_upload', 'alt' => 'Your image', 'id' => 'icon_3_upload_preview', 'height' => '45']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?> 
                                    </div>  
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Description 3</label>
                                            <?php echo $this->Form->input('desc_heading_3', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION HEADING 3', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php echo $this->Form->input('desc_text_3', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION TEXT 3', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">DESCRIPTION 4 </label>
                                <div class="col-sm-2">
                                    <div class="custom-radio radio ">
                                        <?php
                                        if (isset($service['icon_4']) && $service['icon_4'] != '') {
                                            echo $this->Html->image(SERVICE_ICON_PATH . $service['icon_4'], ['height' => 50, 'width' => 80]) . "<br/>";
                                            ?>
                                            <br/>
                                            <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'deleteimage', 'icon_4', $service['icon_4']]) ?> data-value="<?php echo $service['icon_4']; ?>" data-target="#delete" href="#"  class="btn btn-danger btn-sm delete ">REMOVE IMAGE</a>
                                            <?php
                                        } else {
                                            echo $this->Form->input('icon_4', ['label' => false, 'type' => 'file', 'class' => 'imgpreview', 'id' => 'icon_4']);
                                            ?>
                                            <br/>
                                            <div class="imageblock">
                                                <div class="form-group hover-element scanimgblock">
                                                    <div class="col-sm-8 td-inputbox">
                                                        <?php echo $this->Html->image('upload_image.png', ['class' => 'banner_upload', 'alt' => 'Your image', 'id' => 'icon_4_upload_preview', 'height' => '45']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?> 
                                    </div>  
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Description 4</label>
                                            <?php echo $this->Form->input('desc_heading_4', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION HEADING 4', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php echo $this->Form->input('desc_text_4', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION TEXT 4', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">DESCRIPTION 5 </label>
                                <div class="col-sm-2">
                                    <div class="custom-radio radio ">
                                        <?php
                                        if (isset($service['icon_5']) && $service['icon_5'] != '') {
                                            echo $this->Html->image(SERVICE_ICON_PATH . $service['icon_5'], ['height' => 50, 'width' => 80]) . "<br/>";
                                            ?>
                                            <br/>
                                            <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'deleteimage', 'icon_5', $service['icon_5']]) ?> data-value="<?php echo $service['icon_5']; ?>" data-target="#delete" href="#"  class="btn btn-danger btn-sm delete ">REMOVE IMAGE</a>
                                            <?php
                                        } else {
                                            echo $this->Form->input('icon_5', ['label' => false, 'type' => 'file', 'class' => 'imgpreview', 'id' => 'icon_5']);
                                            ?>
                                            <br/>
                                            <div class="imageblock">
                                                <div class="form-group hover-element scanimgblock">
                                                    <div class="col-sm-8 td-inputbox">
                                                        <?php echo $this->Html->image('upload_image.png', ['class' => 'banner_upload', 'alt' => 'Your image', 'id' => 'icon_5_upload_preview', 'height' => '45']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?> 
                                    </div>  
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Description 5</label>
                                            <?php echo $this->Form->input('desc_heading_5', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION HEADING 5', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php echo $this->Form->input('desc_text_5', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION TEXT 5', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">DESCRIPTION 6 </label>
                                <div class="col-sm-2">
                                    <div class="custom-radio radio ">
                                        <?php
                                        if (isset($service['icon_6']) && $service['icon_6'] != '') {
                                            echo $this->Html->image(SERVICE_ICON_PATH . $service['icon_6'], ['height' => 50, 'width' => 80]) . "<br/>";
                                            ?>
                                            <br/>
                                            <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'deleteimage', 'icon_6', $service['icon_6']]) ?> data-value="<?php echo $service['icon_6']; ?>" data-target="#delete" href="#"  class="btn btn-danger btn-sm delete ">REMOVE IMAGE</a>
                                            <?php
                                        } else {
                                            echo $this->Form->input('icon_6', ['label' => false, 'type' => 'file', 'class' => 'imgpreview', 'id' => 'icon_6']);
                                            ?>
                                            <br/>
                                            <div class="imageblock">
                                                <div class="form-group hover-element scanimgblock">
                                                    <div class="col-sm-8 td-inputbox">
                                                        <?php echo $this->Html->image('upload_image.png', ['class' => 'banner_upload', 'alt' => 'Your image', 'id' => 'icon_6_upload_preview', 'height' => '45']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?> 
                                    </div>  
                                </div>
                                <div class="col-sm-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Description 6</label>
                                            <?php echo $this->Form->input('desc_heading_6', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION HEADING 6', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php echo $this->Form->input('desc_text_6', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER DESCRIPTION TEXT 6', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">CATEGORY <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input tel">
                                        <?php echo $this->Form->input('category_id', ['label' => false, 'type' => 'select', 'options' => $serviceCategories, 'empty' => 'SELECT CATEGORY', 'id' => 'select-category', 'class' => ' demo-default', 'placeholder' => 'ENTER CATEGORY NAME']); ?>
<!--                                        <input type="number" name="order_id" class="form-control  required number" placeholder="Enter Category order id" id="order_id" aria-required="true">-->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">VISIT CHARGE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('visit_charge', ['label' => false, 'class' => 'form-control normal-font number', 'placeholder' => 'ENTER SERVICE VISIT CHARGE', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">MINIMUM CHARGE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('minimum_charge', ['label' => false, 'class' => 'form-control normal-font number', 'placeholder' => 'ENTER MINIMUM SERVICE CHARGE', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">BANNER IMAGE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if (isset($service['banner_image']) && $service['banner_image'] != '') {
                                        echo $this->Html->image(SERVICE_BANNER_PATH . $service['banner_image'], ['height' => 65, 'width' => 125]) . "<br/>";
                                        ?>
                                        <br/>
                                        <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'deleteimage', 'banner_image', $service['banner_image']]) ?> data-value="<?php echo $service['banner_image']; ?>" data-target="#delete" href="#"  class="btn btn-danger delete ">REMOVE IMAGE</a>
                                        <?php
                                    } else {
                                        echo $this->Form->input('banner', ['label' => false, 'type' => 'file', 'class' => 'required imgpreview', 'id' => 'banner']);
                                        ?>
                                        <br/>
                                        <div class="imageblock">
                                            <div class="form-group hover-element scanimgblock">
                                                <div class="col-sm-8 td-inputbox">
                                                    <?php echo $this->Html->image('banner_image.png', ['class' => 'banner_upload', 'alt' => 'Your image', 'id' => 'banner_upload_preview', 'height' => '65']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SQUARE IMAGE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if (isset($service['square_image']) && $service['square_image'] != '') {
                                        echo $this->Html->image(SERVICE_SQUARE_BANNER_PATH . $service['square_image'], ['height' => 100, 'width' => 100]) . "<br/>";
                                        ?>
                                        <br/>
                                        <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'deleteimage', 'square_image', $service['square_image']]) ?> data-value="<?php echo $service['square_image']; ?>" data-target="#delete" href="#"  class="btn btn-danger delete ">REMOVE IMAGE</a>
                                        <?php
                                    } else {
                                        echo $this->Form->input('square', ['label' => false, 'type' => 'file', 'class' => 'required imgpreview', 'id' => 'squarebanner']);
                                        ?>
                                        <br/>
                                        <div class="imageblock">
                                            <div class="form-group hover-element scanimgblock">
                                                <div class="col-sm-8 td-inputbox">
                                                    <?php echo $this->Html->image('upload_image.png', ['class' => 'square_upload', 'alt' => 'Your image', 'id' => 'squarebanner_upload_preview', 'height' => '75']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">POPULAR <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php echo $this->Form->radio('popular', [['value' => 'YES', 'text' => 'YES'], ['value' => 'NO', 'text' => 'NO']], ['value' => 'NO']); ?>
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">STATUS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php echo $this->Form->radio('status', [['value' => 'ACTIVE', 'text' => 'ACTIVE'], ['value' => 'INACTIVE', 'text' => 'INACTIVE']], ['value' => 'ACTIVE']); ?>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE SERVICE</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Services', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</div>