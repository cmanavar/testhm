<!--/* $Author: Lanover_Bhakti Thakkar
Template : Doctor
 Function : Delete the Doctor show alert message if doctor have any patient. */  -->
<div class="alert alert-danger fade in">
    <button type="button" class="close close-sm" data-dismiss="alert">
        <i class="fa fa-times"></i>
    </button>
    <?php echo h($message); ?> PATIENT(s) REFERENCE FOUND FOR THIS DOCTOR. PLEASE REMOVE THIS REFERENCE TO DELETE THIS RECORD. 
    <?php echo $this->Html->link('VIEW PATIENTS', ['controller' => 'Referraldoctors', 'action' => 'view', $this->request->session()->read('doctor_id')], ['class' => 'btn btn-danger fa  hidden_live', 'escape' => false, 'title' => 'VIEW PATIENTS']); ?>
</div>


