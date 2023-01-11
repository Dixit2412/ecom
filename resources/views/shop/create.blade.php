@extends($theme)
@section('title', $title)
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{!! $title !!}</h3>
                <div class="card-tools">
                    @if (!empty($module_action))
                        @foreach ($module_action as $key => $action)
                            {!! Html::decode(Html::link($action['url'], $action['title'], $action['attributes'])) !!}
                        @endforeach
                    @endif
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            {!! Form::open(['route' => 'shop.store', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'shop_form', 'method' => 'post', 'files'=>true]) !!}
            <div class="card-body">
                @include('shop.form')
            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="display: block;">
                <button type="submit" name="save" value="Save" class="btn btn-info mr-2 rounded-0"><i class="fas fa-save mr-2"></i>Save</button>
                @if (!Request::has('download'))
                    <button type="submit" name="save_exit" value="Save & Exit" class="btn btn-info rounded-0 mr-2"><i class="fas fa-save mr-2"></i>Save & Exit</button>
                @endif
                {!! Html::decode(link_to(URL::full(), '<i class="fas fa-window-close mr-2"></i> Cancel', ['class' => 'btn btn-warning cancel rounded-0'])) !!}
            </div>
            {{ Form::close() }}
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection