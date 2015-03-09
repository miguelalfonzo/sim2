<html>
@if(isset($msg))
<ol>
	<li>Error: {{$msg->getMessage()}}</li>
	<li>File: {{$msg->getFile()}}</li>
	<li>Line: {{$msg->getLine()}}</li>
	<li>Description: {{$msg->getTraceAsString()}}</li>
</ol>
@else
<h1> 
	{{$subject}}
</h1>
@endif
</html>