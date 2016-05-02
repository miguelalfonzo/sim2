<li class="list-group-item">
	<div class="row" style="margin:0">
		<b>{{ $label }}</b>
	    <span class="pull-right">
		    <span class="badge">{{ $type }}</span>
		  	<button type="button" class="btn btn-default btn-xs btn-delete-client">
		    	<span class="glyphicon glyphicon-remove"></span>
		  	</button>
		</span>
	</div>
	<input type="hidden" name="clientes[]" value="{{ $value }}" >
	<input type="hidden" name="tipos_cliente[]" value="{{ $id_tipo_cliente }}">
</li>

