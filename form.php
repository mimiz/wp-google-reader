<?php 
if(get_option('googlereaderlogin') != '' && get_option('googlereaderpassword') != '' ){
?>

<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __('Title', 'google-reader'); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'cache' ); ?>"><?php _e('Cache', 'google-reader')?>:</label>
	<?php if(get_option('googlereadercachedir') == '' && get_option('googlereadercachelifetime') == ''){
		?><span>Not configured</span>
		<input  type="hidden" id="<?php echo $this->get_field_id( 'cache' ); ?>" name="<?php echo $this->get_field_name( 'cache' ); ?>" value="0" />
		<?php 
	}else{
		 if($instance['cache'] != 0 || $instance['cache'] != "" ) {?>
		<input  class="checkbox" checked="checked" type="checkbox" id="<?php echo $this->get_field_id( 'cache' ); ?>" name="<?php echo $this->get_field_name( 'cache' ); ?>" value="1" />
	<?php }else{ ?>
		<input class="checkbox"  type="checkbox" id="<?php echo $this->get_field_id( 'cache' ); ?>" name="<?php echo $this->get_field_name( 'cache' ); ?>" value="0" />
	<?php }
	}
	?>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'nbelement' ); ?>"><?php echo __('Number of elements to retreive ', 'google-reader');?> :</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'nbelement' ); ?>" name="<?php echo $this->get_field_name( 'nbelement' ); ?>" value="<?php echo $instance['nbelement']; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e('Type', 'google-reader');?>:</label>
	<select onchange="javacript:google_reader.widget.changeType(this);"  id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' );?>">
		<?php foreach($this->_GrSharedOptions as $t) { ?>
			<option value="<?php echo $t;?>" <?php if($t == $instance['type']){?> selected="selected" <?php }?>> <?php echo $t;?></option>
		<?php }?>
	</select> 
</p>
<p style="<?php if(isset($instance['tag']) && !empty($instance['tag'])){ echo "display:block";}else{echo "display:none";} ?>" id="googleReaderPlugintagP">
	<label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php _e('Tag Name', 'google-reader'); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>" value="<?php if(isset($instance['tag'])){ echo $instance['tag'];} ?>" />
</p>
<?php }else{
	echo '<p>'.__('You must define your google account, go to Settings / Google Reader ...', 'google-reader').'</p>';
	
}?>