<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `service_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>

<div class="container-fluid">
    <form action="" id="service-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="" class="control-label">Service Name</label>
            <input type="text" name="name" id="name" class="form-control form-control-border" placeholder="Service Name" value ="<?php echo isset($name) ? $name : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Description</label>
            <textarea rows="3" name="description" id="description" class="form-control form-control-border" placeholder="Write the service descrtion here." required><?php echo isset($description) ? $description : '' ?></textarea>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Service Cost</label>
            <input type="number" name="cost" id="cost" class="form-control form-control-border text-right" placeholder="Service Cost" value ="<?php echo isset($cost) ? $cost : 0 ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Status</label>
            <select name="status" id="status" class="form-control form-control-border" required>
                <option value="1" <?= isset($status) && $status == 1 ? "selected" :"" ?>>Active</option>
                <option value="0" <?= isset($status) && $status == 0 ? "selected" :"" ?>>Inactive</option>
            </select>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal #service-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_service",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    end_loader();
                }
            })
        })
    })
</script>