<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                QUESTIONS
                <a href="<?php echo $this->Url->build(["controller" => "Questions", "action" => "index",$service_id]); ?>"><button class="btn btn-warning btn-sm pull-right" >BACK</button></a>
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-12">

            </div>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VIEW QUESTIONS DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php
                    //echo "<pre>";
                    //print_r($question); //exit;  
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <?php // foreach ($category as $key => $val) {    ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Category Name</label></td>
                                    <td><?= $question['category_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Name</label></td>
                                    <td><?= $question['service_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Question Title</label></td>
                                    <td><?= $question['question_title']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Questions Type</label></td>
                                    <td><?= ucfirst($question['questions_type']); ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Answer Type</label></td>
                                    <td><?= $question['answer_type']; ?></td>
                                </tr>
                                <?php if (isset($question['parent_questions']) && $question['parent_questions'] != '') { ?>
                                    <tr>
                                        <td width="30%"><label class="control-label">Parent Questions </label></td>
                                        <td><?= $question['parent_questions']; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($question['parent_answers']) && $question['parent_answers'] != '') { ?>
                                    <tr>
                                        <td width="30%"><label class="control-label">Parent Answer </label></td>
                                        <td><?= $question['parent_answers']; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Answers</label></td>
                                    <td>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Label</td>
                                                    <td>Quantity</td>
                                                    <td>Price (<i class="fa fa-inr"></i>)</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($question['answers'] as $key => $val) { ?>
                                                    <tr>
                                                        <td><?= $val['label']; ?></td>
                                                        <td><?= str_replace("_", " ", $val['quantity']); ?></td>
                                                        <td><?= $val['price']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                
                                <?php // }    ?>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.row -->
    </div>
</div>