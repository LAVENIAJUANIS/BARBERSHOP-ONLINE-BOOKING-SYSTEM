<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT c.*,b.name as brand, cc.name as car_type,b.image_path as brand_img FROM `car_list` c inner join brand_list b on b.id = c.brand_id inner join category_list cc on cc.id = c.category_id where c.id = '{$_GET['id']}'");
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
    #car-cover-view{
        object-fit:scale-down;
        object-position:center center;
        height:35vh;
        width:50vw;
    }
    #brand-img{
        height:50px;
        width:50px;
        object-fit: scale-down;
        object-position:center center;
        border-radius:50% 50%
    }
</style>
<div class="py-3">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h5 class="card-title">Car Details</h5>
            <div class="card-tools">
                <?php 
                if(isset($status)){
                    switch($status){
                        case 0:
                            echo '<span class="bg-primary badge badge-pill badge-primary">Available</span>';
                            break;
                        case 1:
                            echo '<span class="badge badge-pill badge-danger">Sold</span>';
                            break;
                    }
                }
                ?>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-4 text-center">
                        <img src="<?= validate_image(isset($id) ? "uploads/banners/car-{$id}.png?v={$date_updated}" :'') ?>" alt="" id="car-cover-view" class="img-thumbnail bg-dark">
                    </div>
                    <div class="col-md-8">
                        <h2 class='text-dark'><b><?= isset($product_title) ? $product_title : "" ?></b></h2>
                        <hr>
                        <div class="row justify-content-between align-items-top">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <span>
                                        <img src="<?= validate_image(isset($brand_img) ? $brand_img : "") ?>" alt="Author Image" id="brand-img" class="img-thumbail border">
                                    </span>
                                    <span class="mx-2 text-muted"><?= isset($brand) ? $brand : "N/A" ?></span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <span class="text-muted">
                                    <i class="fa fa-th-list"></i> <?= $car_type ?>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <span class="text-muted">
                                    <i class="fa fa-tags"></i> <?= number_format($price,2) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row py-3">
                    <div class="col-md-6">
                        <dl>
                            <dt class="text-muted">Model</dt>
                            <dd class="pl-4"><?= isset($model) ? $model : "" ?></dd>
                            <dt class="text-muted">Year</dt>
                            <dd class="pl-4"><?= isset($year) ? $year : "" ?></dd>
                            <dt class="text-muted">Engine</dt>
                            <dd class="pl-4"><?= isset($engine) ? $engine : "" ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl>
                            <dt class="text-muted">Fuel</dt>
                            <dd class="pl-4"><?= isset($fuel) ? $fuel : "" ?></dd>
                            <dt class="text-muted">Color</dt>
                            <dd class="pl-4"><?= isset($color) ? $color : "" ?></dd>
                            <dt class="text-muted">Mileage</dt>
                            <dd class="pl-4"><?= isset($mileage) ? number_format($mileage) : "" ?></dd>
                        </dl>
                    </div>
                </div>
                <hr>
                <div class="row py-3">
                    <div class="col-md-12">
                        <div class="text-muted">Description</div>
                        <div><?= isset($description) ? html_entity_decode($description) : "" ?></div>
                    </div>
                </div>
                <hr>
                <div class="row py-3">
                    <div class="col-md-12">
                        <div class="text-muted">Condition</div>
                        <div><?= isset($condition) ? html_entity_decode($condition) : "" ?></div>
                    </div>
                </div>
                <hr>
                <div class="row py-3">
                    <div class="col-md-12">
                        <div class="text-muted">Features</div>
                        <div><?= isset($features) ? html_entity_decode($features) : "" ?></div>
                    </div>
                </div>
                <hr>
                <div class="row py-3">
                    <div class="col-md-12">
                        <div class="text-muted">Other Images</div>
                    </div>
                </div>
                <?php 
                $imgs = scandir(base_app."uploads/cars/{$id}");
                ?>
                <div class="row row-cols-sm-1 row-cols-md-2 row-cols-md-4 justify-content-center">
                    <?php 
                        foreach($imgs as $img):
                            if(in_array($img,array(".","..")))
                            continue;
                    ?>
                    <img src="<?= validate_image("uploads/cars/{$id}/{$img}") ?>" alt="Car Image" class="img-thumbnail car-images mx-2 my-1">
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="./?page=products/manage_product&id=<?= isset($id) ? $id : "" ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
            <a href="./?page=products" class="btn btn-secondary"><i class="fa fa-angle-left"></i> Back to List</a>
        </div>
    </div>
</div>