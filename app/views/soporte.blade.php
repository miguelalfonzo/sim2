<html>
	@if( isset( $msg ) )
		<ol>
			<li>User: {{Auth::user()->id.' '.Auth::user()->username}}</li>
			<li>Error: {{$msg->getMessage()}}</li>
			<li>File: {{$msg->getFile()}}</li>
			<li>Line: {{$msg->getLine()}}</li>
			<li>Description: {{$msg->getTraceAsString()}}</li>
		</ol>
	@elseif( isset( $description) )
		<ol>
			<li>User: {{Auth::user()->id.' '.Auth::user()->username}}</li>
			<li>Function: {{$function}}</li>
			<li>Line: {{$line}}</li>
			<li>File: {{$file}}</li>
			<li>Description: {{$description}}</li>
		</ol>
	@elseif( isset( $subject ) )
		<h1>
			{{$subject}}
		</h1>
	@endif
</html>