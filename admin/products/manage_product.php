<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `car_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
    if(is_null($date_updated))
    $date_updated = strtotime($date_created);
    else
    $date_updated = strtotime($date_updated);
}


?>
<style>
    #product-cover{
        object-fit:scale-down;
        object-position: center center;
        height:30vh;
        width:20vw;
    }
</style>
<div class="card card-outline card-primary rounded-0 shadow-sm my-3">
    <div class="card-header rounded-0">
        <h5 class="card-title"><?= isset($id) ? "Add New Product" : "Update Product Details" ?></h5>
    </div>
    <div class="card-body rounded-0">
        <div class="container-fluid">
            <form action="" id="product-form">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_title" class="control-label text-dark">Product Title</label>
                                <input type="text" name="product_title" id="product_title" class="form-control form-control-border" placeholder="Product Title" value ="<?php echo isset($product_title) ? $product_title : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="category_id" class="control-label text-dark">Car Type</label>
                                <select name="category_id" id="category_id" class="form-control form-control-border select2" data-placeholder="Select Type Here" required>
                                    <option value="" disabled <?= !isset($category_id) ? 'selected' : '' ?>>Active</option>
                                    <?php 
                                    $qry = $conn->query("SELECT * FROM `category_list` where `status` = 1 ".(isset($category_id) ? " or id = '{$category_id}'" : "" )." order by `name` asc");
                                    while($row = $qry->fetch_assoc()):
                                    ?>
                                    <option value="<?= $row['id'] ?>" <?= $row['status'] == 0 ? 'disabled' : '' ?> <?= isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?>><?= ucwords($row['name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="brand_id" class="control-label text-dark">Brand Type</label>
                                <select name="brand_id" id="brand_id" class="form-control form-control-border select2" data-placeholder="Select Brand Here" required>
                                    <option value="" disabled <?= !isset($brand_id) ? 'selected' : '' ?>>Active</option>
                                    <?php 
                                    $qry = $conn->query("SELECT * FROM `brand_list` where `status` = 1 ".(isset($brand_id) ? " or id = '{$brand_id}'" : "" )." order by `name` asc");
                                    while($row = $qry->fetch_assoc()):
                                    ?>
                                    <option value="<?= $row['id'] ?>" <?= $row['status'] == 0 ? 'disabled' : '' ?> <?= isset($brand_id) && $brand_id == $row['id'] ? 'selected' : '' ?>><?= ucwords($row['name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price" class="control-label text-dark">Price</label>
                                <input type="number" name="price" id="price" class="form-control form-control-border text-right" placeholder="Price" value ="<?php echo isset($price) ? $price : 0 ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="model" class="control-label text-dark">Model</label>
                                <input type="text" name="model" id="model" class="form-control form-control-border" placeholder="Model" value ="<?php echo isset($model) ? $model : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="year" class="control-label text-dark">Year</label>
                                <input type="year" name="year" id="year" class="form-control form-control-border" placeholder="Year" value ="<?php echo isset($year) ? $year : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="mileage" class="control-label text-dark">Mileage</label>
                                <input type="text" name="mileage" id="mileage" class="form-control form-control-border" placeholder="Mileage" value ="<?php echo isset($mileage) ? $mileage : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="fuel" class="control-label text-dark">Fuel</label>
                                <input type="text" name="fuel" id="fuel" class="form-control form-control-border" placeholder="Fuel" value ="<?php echo isset($fuel) ? $fuel : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="engine" class="control-label text-dark">Engine</label>
                                <input type="text" name="engine" id="engine" class="form-control form-control-border" placeholder="engine" value ="<?php echo isset($engine) ? $engine : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="color" class="control-label text-dark">Color</label>
                                <input type="text" name="color" id="color" class="form-control form-control-border" placeholder="color" value ="<?php echo isset($color) ? $color : "" ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="banner" class="control-label text-dark">Product Cover</label>
                                <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="banner" onchange="displayMag(this,$(this))" accept="image/png,image/jpeg" <?= !isset($id) ? "required" : "" ?>>
                                <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <img src="<?php echo validate_image(isset($id) ? "uploads/banners/car-{$id}.png?v={$date_updated}" :'') ?>" alt="" id="product-cover" class="img-fluid img-thumbnail bg-dark">
                            </div>
                            <div class="form-group">
                                <label for="images[]" class="control-label text-dark">Add Other Images</label>
                                <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="images[]" accept="image/png,image/jpeg" <?= !isset($id) ? "required" : "" ?> multiple>
                                <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="condition" class="control-label text-dark">Condition</label>
                                <textarea rows="3" name="condition" id="condition" class="form-control form-control-border summernote" data-height = "40vh" data-placeholder="Write the car condition here." required><?php echo isset($condition) ? $condition : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="features" class="control-label text-dark">Features</label>
                                <textarea rows="3" name="features" id="features" class="form-control form-control-border summernote" data-height = "40vh" data-placeholder="Write the car features here." required><?php echo isset($features) ? $features : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="description" class="control-label text-dark">Description/Content</label>
                                <textarea rows="3" name="description" id="description" class="form-control form-control-border summernote" data-height = "40vh" data-placeholder="Write the product description here." required><?php echo isset($description) ? $description : '' ?></textarea>
                            </div>
                            <?php if($_settings->userdata('type') == 1): ?>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="status" id="status" <?= isset($status) && $status == 1 ? "checked" : '' ?>>
                                    <label for="status" class="custom-control-label">Sold</label>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer rounded-0">
        <button class="btn btn-primary" type="submit" form="product-form">Save Product</button>
        <a class="btn btn-secondary" href="./?page=?products">Cancel</a>
    </div>
</div>
<script>
    function displayMag(input,_this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#product-cover').attr('src', e.target.result);
                _this.siblings('label').text(input.files[0].name)
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            _this.siblings('label').text('Choose file')
            $('#product-cover').attr('src', "<?php echo validate_image(isset($banner_path) ? $banner_path :'') ?>");
        }
    }
    function showName(_this){
        if (_this[0].files && _this[0].files[0]) {
                _this.siblings('label').text(_this[0].files[0].name)
        }else{
            _this.siblings('label').text('Choose file')
        }
    }
    $(function(){
       
        $('.select2').select2();
        $('.summernote').summernote({
		        height: '40vh',
		        placeholder: $(this).attr('data-placeholder') || "Write here",
		        toolbar: [
		            [ 'font', [ 'bold', 'italic', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph' ] ],
		            [ 'view', [ 'undo', 'redo' ] ]
		        ]
		    })
        $('#product-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_product",
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
                        location.replace("./?page=products/view_product&id="+resp.id);
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
                    $('html, body').animate({scrollTop:0},'fast')
                }
            })
        })
    })
</script>