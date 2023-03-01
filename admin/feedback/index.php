<style>
    .img-avatar{
        width:45px;
        height:45px;
        object-fit:cover;
        object-position:center center;
        border-radius:100%;
    }
</style>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Appointments</h3>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="30%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>No</th>
						<th>Date Created</th>
						<th>Full Name</th>
						<th>Schedule</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `appointment_list` order by `status` asc,unix_timestamp(`date_created`)");
						while($row = $qry->fetch_assoc()):
						
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td><?php echo ucwords($row['fullname']) ?></td>
							<td><?php echo date("M d, Y",strtotime($row['schedule'])) ?></td>
							<td class="text-center">
                                <?php
                                    switch($row['status']){
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
                                ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-light"></span> View Booking</a>
				                    <div class="dropdown-divider"></div>
									<a class="dropdown-item view_product" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-light"></span> View Product Buy</a>
									<div class="dropdown-divider"></div>
									<?php if($row['status'] == 0): ?>
				                    <a class="dropdown-item verified" href="javascript:void(0)" data-id="<?= $row['id'] ?>"  data-name="<?= $row['fullname'] ?>"><span class="fa fa-check text-primary"></span> Verify</a>
				                    <div class="dropdown-divider"></div>
									<?php endif; ?>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.verified').click(function(){
			_conf("Are you sure to verify this Appointment Request?","verified",[$(this).attr('data-id')])
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Appointment permanently?","delete_appointment",[$(this).attr('data-id')])
		})
		$('.view_data').click(function(){
			uni_modal("Appointment Details","appointments/view_appointment.php?id="+$(this).attr('data-id'),"large")
		})
		$('.view_product').click(function(){
			uni_modal("Appointment Details","appointments/view_productbuy.php?id="+$(this).attr('data-id'),"large")
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
	})
	function delete_appointment($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_appointment",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
	function verified($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=verify_appointment",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>