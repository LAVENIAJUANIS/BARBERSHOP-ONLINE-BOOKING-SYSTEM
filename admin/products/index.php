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
		<h3 class="card-title">List of Cars</h3>
		<div class="card-tools">
			<a href="./?page=products/manage_product" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Add New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="25%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Brand</th>
						<th>Type</th>
						<th>Title</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
                        $bwhere = "";
                        if($_settings->userdata('type') != 1){
                            $bwhere = " where user_id = '{$_settings->userdata('id')}' ";
                        }
                        $brand_qry = $conn->query("SELECT id,`name` FROM `brand_list` where id in (SELECT brand_id from `car_list` {$bwhere}) ");
                        $brand_res = $brand_qry->fetch_all(MYSQLI_ASSOC);
                        $brand_arr = array_column($brand_res,'name','id');
						$category_qry = $conn->query("SELECT id,`name` FROM `category_list` where id in (SELECT category_id from `car_list` {$bwhere}) ");
                        $category_res = $category_qry->fetch_all(MYSQLI_ASSOC);
                        $category_arr = array_column($category_res,'name','id');
						$qry = $conn->query("SELECT * from `car_list` {$bwhere} order by `product_title` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td class=""><?php echo ucwords($brand_arr[$row['brand_id']] ? $brand_arr[$row['brand_id']] : "") ?></td>
							<td class=""><?php echo ucwords($category_arr[$row['category_id']] ? $category_arr[$row['category_id']] : "") ?></td>
							<td class=""><?php echo ucwords($row['product_title']) ?></td>
							<td class="text-center">
                                <?php
                                    switch($row['status']){
                                        case '0':
                                            echo "<span class='badge badge-success bg-primary badge-pill'>Available</span>";
                                            break;
                                        case '1':
                                            echo "<span class='badge badge-secondary badge-pill'>Sold</span>";
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
				                    <a class="dropdown-item view_data" href="./?page=products/view_product&id=<?= $row['id'] ?>" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item" href="./?page=products/manage_product&id=<?= $row['id'] ?>" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
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
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this product permanently?","delete_product",[$(this).attr('data-id')])
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
        });
	})
	function delete_product($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_product",
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