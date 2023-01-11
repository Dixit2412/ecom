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
            {!! Form::open(['route' => 'product.store', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'product_form', 'method' => 'post', 'files'=>true]) !!}
            <div class="card-body">
                @include('product.form')
            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="display: block;">
                <button type="submit" name="save" value="Save" class="btn btn-info mr-2 rounded-0">Save</button>
                @if (!Request::has('download'))
                    <button type="submit" name="save_exit" value="Save & Exit" class="btn btn-info rounded-0 mr-2">Save & Exit</button>
                @endif
                {!! Html::decode(link_to(URL::full(), 'Cancel', ['class' => 'btn btn-warning cancel rounded-0'])) !!}
            </div>
            {{ Form::close() }}
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection