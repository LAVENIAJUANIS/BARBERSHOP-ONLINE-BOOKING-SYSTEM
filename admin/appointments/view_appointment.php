<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `appointment_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <dl>
                <dt class="text-muted">Full Name</dt>
                <dd class='pl-4 fs-4 fw-bold'><?= isset($fullname) ? $fullname : '' ?></dd>
                <dt class="text-muted">Email</dt>
                <dd class='pl-4 fs-4 fw-bold'><?= isset($email) ? $email : '' ?></dd>
                <dt class="text-muted">Contact</dt>
                <dd class='pl-4 fs-4 fw-bold'><?= isset($contact) ? $contact : '' ?></dd>
				<dt class="text-muted">Your Barber</dt>
                <dd class='pl-4 fs-4 fw-bold'><?= isset($barber) ? $barber : '' ?></dd>
            </dl>
        </div>
        <div class="col-md-6">
            <dl>
                <dt class="text-muted">Schedule</dt>
                <dd class='pl-4 text-dark'>
                    <p class=""><small><?= isset($schedule) ? date("M d, Y",strtotime($schedule)) : '' ?></small></p>
                </dd>
				<dt class="text-muted">Time</dt>
				<dd class='pl-4 text-dark'>
				<p class=""><small><?= isset($time) ? date("h:i A", strtotime($time)) : '' ?></small></p>
				</dd>

                <dt class="text-muted">Status</dt>
                <dd class='pl-4 text-dark'>
                    <?php
                    if(isset($status)):
                        switch($status){
                            case '1':
                                echo "<span class='badge badge-primary badge-pill'>Verified</span>";
                                break;
                            case '2':
                                echo "<span class='badge badge-success bg-primary badge-pill'>Done</span>";
                                break;
                            case '0':
                                echo "<span class='badge badge-light badge-pill text-dark'>Pending</span>";
                                break;
                        }
                    endif;
                    ?>
                </dd>
            </dl>
        </div>
    </div>
    <div class="row">
        <table class="table table-striped table-hover table-bordered">
            <colgroup>
                <col width="60%">
                <col width="40%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center px-2 py-1">Service</th>
                    <th class="text-center px-2 py-1">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $appointment = $conn->query("SELECT a.*,s.name FROM `appointment_service` a inner join service_list s on a.service_id = s.id where a.appointment_id = '{$id}' ");
                while($row = $appointment->fetch_assoc()):
                ?>
                    <tr>
                        <td class="px-2 py-1"><?= $row['name'] ?></td>
                        <td class="px-2 py-1 text-right"><?= number_format($row['cost'],2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="px-2 py-1 text-right">Total</th>
                    <td class="px-2 py-1 text-right"><?= number_format($total,2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-12 text-right">
        <button class="btn btn-flat btn-sm btn-dark" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>