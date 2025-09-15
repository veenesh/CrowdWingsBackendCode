<style>
.preview {
    background: #e4e4e4;
    width: 100%;
    height: 300px;
    float: left;
    margin: 0 0 6px 0px;
    background-size: cover;
    background-position: center;
	border: 1px solid #ced4da;
}
</style>
	<?php
		$inputName=$name;
		if(set_value($inputName)!=''){
			$text = set_value($inputName);
		}
		$img='';
		if(isset($path)){
		$img=$path;
		}
		$post_url='uploadimage';
		if(isset($url)){
			$post_url=$url;
		}
		$uploadLimit=1;
		if(isset($fileCount)){
			$uploadLimit=$fileCount;
		}
	?>
	 <div id="queue" class='preview filename <?=$inputName?>' style='background-image:url(<?=$img?>)'></div>
	 <input type='hidden' name='<?=$inputName?>' id='<?=$inputName?>' value='<?=set_value($inputName)?>'>



<script>
<?php 
	$id='';
	$timestamp = time();
?>
$(function() {
	$('#<?=$inputName?>').uploadifive({
                'auto'             : true,
				'multi'     : false,
				'buttonText'        : '<i class="fa fa-paperclip" aria-hidden="true"></i> Browse',
                'fileType'         : '.jpg,.jpeg,.gif,.png',
                'formData'         : {
                                       'timestamp' : '<?php echo $timestamp;?>',
                                       'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
                                     },
                'queueClass'          : '<?=$inputName?>',
                'uploadScript'     : '<?=base_url()?>/<?=$post_url?>',
                'onUploadComplete' : function(file, data) {
					
					var imgPath = "<?=base_url()?>/public/uploads/"+data;
                    $('.<?=$inputName?>').css("background-image","url("+imgPath+")");
                    $('#<?=$inputName?>').val(data);;
	
                }
            });
});
</script>