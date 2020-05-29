@extends('voyager::master')

@section('content')
<div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" name="file" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <button type="submit" class="btn btn-primary">Cargar datos</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection