jQuery(document).ready(function($) {
    $('#keyword_form_check').validate({
        submitHandler: function(form) {
            $( "#checking" ).show( "slow" );
            $( "#butsend" ).hide( "slow" );
            $( "#pos" ).hide( "slow" );
            var kpcData = {
                kw: $( "#kw" ).val(),
                website: $( "#website" ).val(),
                kpc_range: $( "#kpc-range" ).val(),
                kpc_place: $( "#kpc-place" ).val(),
                action: "kpc"    
            };
            $.ajax({
                type:"POST",
                url: kpcAjax.ajaxurl,
                data: kpcData,
                success:function(data){
                    jQuery("#pos").html(data);
                    $( "#checking" ).hide( "slow" );
                    $( "#butsend" ).show( "slow" );
                    $( "#pos" ).show( "slow" );
                }
            });
            return false
        },
        
		rules: {
			kw: {
				required: true
			},
            website: {
				required: true,
                url: true
			}
		}
	});
} );