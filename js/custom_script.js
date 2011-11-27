var google_reader = {
	widget:{
		changeType:function(element){
			var value = jQuery(element).val();
			if(value == 'tag'){
				if(jQuery("#widgets-right #googleReaderPlugintagP"))
				{
					jQuery("#widgets-right #googleReaderPlugintagP").show();
				}
			}else{
				if(jQuery("#widgets-right #googleReaderPlugintagP"))
				{
					jQuery("#widgets-right #googleReaderPlugintagP input").val('');
					jQuery("#widgets-right #googleReaderPlugintagP").hide();
				}
			}
		}
	}		
};