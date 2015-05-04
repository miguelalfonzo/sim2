<div class="panel-body table-documents">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-group col-xs-8 col-sm-4 col-md-2 col-lg-2">
            <div>
                <select id="idProof" class="form-control">
                    @foreach( $proofTypes as $proofType )
                        <option value="{{$proofType->id}}" selected>{{$proofType->descripcion}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        @include('Dmkt.User.date_picker')
        
        <div class="form-group col-xs-4 col-sm-2 col-md-1 col-lg-1">
            <div>
                <a id="search-documents" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in">
                    <i class="glyphicon glyphicon-search"></i>
                </a>
            </div>
        </div>
    </div>
</div>