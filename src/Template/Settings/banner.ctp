<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<?php echo $this->element('js/datatable-delete'); ?>
<?php echo $this->Html->script('custom/upload_image.js', array('block' => 'scriptBottom')); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-file-image-o fa-fw"></i>BANNER
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo ($this->Flash->render()); ?>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php if ($this->request->action == 'banner') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> BANNER 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($banner, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label padng_rgtrmv">BANNER <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if (isset($banner['banner_images']) && $banner['banner_images'] != '') {
                                        echo $this->Html->image(BANNER_IMAGE_PATH . $banner['banner_images'], ['height' => 75, 'width' => 125]) . "<br/>";
                                        ?>
                                        <br/>
                                        <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Settings', 'action' => 'bannerdeleteimage', 'banner_images', $banner['banner_images']]) ?> data-value="<?php echo $banner['banner_images']; ?>" data-target="#delete" href="#"  class="btn btn-danger delete ">REMOVE IMAGE</a>
                                        <?php
                                    } else {
                                        echo $this->Form->input('banner_images', ['label' => false, 'type' => 'file', 'class' => 'required imgpreview', 'id' => 'squarebanner']);
                                        ?>
                                        <br/>
                                        <div class="imageblock">
                                            <div class="form-group hover-element scanimgblock">
                                                <div class="col-sm-8 td-inputbox">
                                                    <?php echo $this->Html->image('banner_image.png', ['class' => 'square_upload', 'alt' => 'Your image', 'id' => 'squarebanner_upload_preview', 'height' => '75']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ORDER # <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('order_id', ['type' => 'number', 'label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER BANNER ORDER ID', 'maxlength' => 255]); ?>
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
                            <button type="submit" class="btn btn-primary">SAVE BANNER</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Settings', 'action' => 'banner'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    LIST BANNER
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="scroll">
                        <div class="table-responsive">
                            <div id="dataTables-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="" id="dataTables-responsive_length">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables-responsive" >
                                                <thead>
                                                    <tr>
                                                        <th width="2%">Sr.no</th>
                                                        <th>Banner</th>
                                                        <th>Order#</th> 
                                                        <th>Status</th>
                                                        <th width="12%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($banners)) {
                                                        foreach ($banners as $key => $val) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo $this->Html->image(BANNER_IMAGE_PATH . $val['banner_images'], ['height' => 65, 'width' => 125]) . "<br/>"; ?></td>
                                                                <td><?php echo stripslashes($val['order_id']) ?></td>    
                                                                <td><?php echo stripslashes($val['status']) ?></td>
                                                                <td>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Settings', 'action' => 'banneredit', $val['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
                                                                    <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'Settings', 'action' => 'bannerdelete']) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o delete"></a>
                                                                </td>     
                                                            </tr>

                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="6" style="text-align:center;"><b>No Records found </b></td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.table-responsive -->
                        <!--paginations-->

                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</div>