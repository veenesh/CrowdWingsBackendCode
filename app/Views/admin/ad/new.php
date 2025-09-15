<?= $this->extend('theme/vendor') ?>


<?= $this->section('content')?>

<form method="POST" enctype='multipart/form-data'>
    <div class="row">
       <div class='col-md-4'>
        <?php
		    $cover= "https://via.placeholder.com/500";
		    echo view('include/single-upload-preview', ['name'=>'ad_picture', 'path'=>$cover]);
		?>
       </div> 
       <div class='col-md-8'>
       <div class="form-group">
               <lable>Ad Title</label>
               <input type='text' name='title' class='form-control'>
            </div>
            <div class="form-group">
               <lable>Select Gender</label>
               <select name='gender' class='form-control'>
                    <option value=''>Select Gender</option>
                    <option value='all'>All</option>
                    <option value='male'>Male</option>
                    <option value='female'>Female</option>
                </select>
            </div>


            <div class="form-group">
                    <label for="exampleInputPassword1" class="input__label">State</label>
                    <select name='state' class='form-control state'>
                        <option value=''>Select State</option>
                        <?php foreach($states as $state){?>
                            <option value='<?=$state['id']?>'  <?=set_select('state', $state['id'], False); ?>><?=$state['name']?></option>
                        <?php }?>    
                    </select>

                    <?php
						 if(isset($validation)){
							 if($validation->hasError('state')){
								 echo $validation->showError('state');
							 }
						 }
					 ?>
            </div>

            <div class="form-group">
                            <label for="exampleInputPassword1" class="input__label">City</label>
                            <select name='city' class='form-control city'>
                                <option value=''>Select City</option>
                                <?php foreach($cities as $city){?>
                                    <option value='<?=$city['id']?>'  <?=set_select('city', $city['id'], False); ?>><?=$city['name']?></option>
                                <?php }?>
                            </select>

                                <?php
									 if(isset($validation)){
										 if($validation->hasError('city')){
											 echo $validation->showError('city');
										 }
									 }
								 ?>
                        </div>
            <div class="d-flex align-items-center flex-wrap justify-content-between">
                <input type="submit" name="create_ad" class="btn btn-primary btn-style mt-4" value="Create Ad">
       
            </div>             
       </div> 
    </div>
</form>

<script>
    $( ".state" ).on( "change", function() {
		  var id=$(".state").val();
			$.ajax({
					  url: "<?=base_url()?>/load/city/"+id,
					  type: 'get',
					  success: function(response){
					  		$(".city").html(response);
					  }
					   
		});
	});
</script>
<?= $this->endSection()?>