<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<?php echo $this->element('js/datatable-delete'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-question-circle fa-fw"></i> FAQ
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
                    <?php if ($this->request->action == 'faq') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> FAQ 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($faq, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">QUESTION <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('question', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER QUESTION', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ANSWER <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php echo $this->Form->input('answer', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER ANSWER', 'rows' => 3]); ?>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE QUESTION</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Settings', 'action' => 'faq'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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
                    LIST FAQ
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
                                                        <th>Question</th>
                                                        <th>Answer</th>
                                                        <th width="12%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($faqs)) {
                                                        foreach ($faqs as $key => $val) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo stripslashes($val['question']) ?></td>    
                                                                <td><?php echo stripslashes($val['answer']) ?></td>
                                                                <td>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Settings', 'action' => 'faqedit', $val['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
                                                                    <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'Settings', 'action' => 'faqdelete']) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o delete"></a>
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