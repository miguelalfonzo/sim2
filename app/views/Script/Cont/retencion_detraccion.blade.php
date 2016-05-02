<script>
	$( '#regimen' ).on( 'change' , function()
	{
	    if ( $( this ).val() == 0 )
	   	{
	        $( '#monto-regimen' ).closest( '.form-group' ).css("visibility" , "hidden" );
	    }
	    else
	   	{
	        $("#monto-regimen").closest( '.form-group' ).css("visibility" , "show" );        
	    }
	});
</script>