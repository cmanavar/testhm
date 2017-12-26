<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->
<?php echo $this->Html->script('custom/upload_image.js', array('block' => 'scriptBottom')); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-tags fa-fw"></i>SERVICE CATEGORIES
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
                    <?php if ($this->request->action == 'add') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> SERVICE CATEGORY
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($category, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NAME <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER SERVICE CATEGORY NAME', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ORDER# <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input tel">
                                        <?php echo $this->Form->input('order_id', ['type' => 'number', 'label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER SERVICE CATEGORY ORDER ID', 'maxlength' => 255]); ?>
<!--                                        <input type="number" name="order_id" class="form-control  required number" placeholder="Enter Category order id" id="order_id" aria-required="true">-->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label padng_rgtrmv">ICON IMAGE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if (isset($category['icon_image']) && $category['icon_image'] != '') {
                                        echo $this->Html->image(SERVICE_CATEGORY_ICON_PATH . $category['icon_image'], ['height' => 50, 'width' => 50]) . "<br/>";
                                        ?>
                                        <br/>
                                        <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'ServiceCategory', 'action' => 'deleteimage', 'icon_image', $category['icon_image']]) ?> data-value="<?php echo $category['icon_image']; ?>" data-target="#delete" href="#"  class="btn btn-danger delete ">REMOVE IMAGE</a>
                                        <?php
                                    } else {
                                        echo $this->Form->input('icon', ['label' => false, 'type' => 'file', 'class' => 'required imgpreview', 'id' => 'iconlogo']);
                                        ?>
                                        <br/>
                                        <div class="imageblock">
                                            <div class="form-group hover-element scanimgblock">
                                                <div class="col-sm-8 td-inputbox">
                                                    <?php echo $this->Html->image('upload_image.png', ['class' => 'icon_upload', 'alt' => 'Your image', 'id' => 'iconlogo_upload_preview', 'height' => '50']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label padng_rgtrmv">BANNER IMAGE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if (isset($category['banner_image']) && $category['banner_image'] != '') {
                                        echo $this->Html->image(SERVICE_CATEGORY_BANNER_PATH . $category['banner_image'], ['height' => 75, 'width' => 125]) . "<br/>";
                                        ?>
                                        <br/>
                                        <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'ServiceCategory', 'action' => 'deleteimage', 'banner_image', $category['banner_image']]) ?> data-value="<?php echo $category['banner_image']; ?>" data-target="#delete" href="#"  class="btn btn-danger delete ">REMOVE IMAGE</a>
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
                                <label class="col-sm-3 control-label padng_rgtrmv">SQUARE IMAGE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if (isset($category['square_image']) && $category['square_image'] != '') {
                                        echo $this->Html->image(SERVICE_CATEGORY_SQUARE_BANNER_PATH . $category['square_image'], ['height' => 100, 'width' => 100]) . "<br/>";
                                        ?>
                                        <br/>
                                        <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'ServiceCategory', 'action' => 'deleteimage', 'square_image', $category['square_image']]) ?> data-value="<?php echo $category['square_image']; ?>" data-target="#delete" href="#"  class="btn btn-danger delete ">REMOVE IMAGE</a>
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
                                <label class="col-sm-3 control-label">STATUS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php echo $this->Form->radio('display_app', [['value' => 'YES', 'text' => 'YES'], ['value' => 'NO', 'text' => 'NO']], ['default' => 'NO']); ?>
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">STATUS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php echo $this->Form->radio('status', [['value' => 'ACTIVE', 'text' => 'ACTIVE'], ['value' => 'INACTIVE', 'text' => 'INACTIVE']], ['default' => 'ACTIVE']); ?>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-3  control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">SAVE CATEGORY</button>                                    
                                <?php echo $this->Html->link('CANCEL', array('controller' => 'ServiceCategory', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
                            </div>
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