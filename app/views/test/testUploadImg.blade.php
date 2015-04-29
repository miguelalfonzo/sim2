@extends('template.main')
@section('content')

<div class="container">
    <div class="span8">
        <!-- Post Title -->
        <div class="row">
            <div class="span8">
                <h4>Ajax Image Upload and Preview With Laravel</h4>
                
            </div>
        </div>
        <!-- Post Footer -->
        <div class="row">
            <div class="span3">
                <div id="validation-errors"></div>
                <form class="form-horizontal" id="solicitude-upload-image-event" enctype="multipart/form-data" method="post" action="{{ url('testUploadImgSave') }}" autocomplete="off">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <span class="btn btn-info btn-file">
                        Subir Imagenes <input type="file" name="image[]" id="upload-image-event" multiple="true" /> 
                    </span>
                </form>
 
            </div>
            <div class="span5" id="output" style="display:none;">
                
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
	var options = {
                beforeSubmit:   showRequest,
				success:        showResponse,
				dataType:       'json' 
        }; 
 	$('body').delegate('#upload-image-event','change', function(){
 		$('#solicitude-upload-image-event').ajaxForm(options).submit();  		
 	}); 
});		
function showRequest(formData, jqForm, options) { 
	$("#validation-errors").hide().empty();
	$("#output").css('display','none');
    return true; 
} 
function showResponse(response, statusText, xhr, $form)  { 
	if(response.success == false)
	{
		var arr = response.errors;
		$.each(arr, function(index, value)
		{
			if (value.length != 0)
			{
				$("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
			}
		});
		$("#validation-errors").show();
	} else {
        for (var i = response.fileList.length - 1; i >= 0; i--) {
            response.fileList[i]
            $("#output").append('<div class="col-xs-6 col-md-3 solicitude_img thumbnail"> <img data-img-id='+ response.fileList[i]['id'] +' src="'+ response.fileList[i]['name'] +'" /></div>');
            $("#output").css('display','block');
        };
	}
}
</script>
@stop