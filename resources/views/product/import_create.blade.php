@extends($theme)
@section('title', $title)
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">{!! $module_title !!}</h3>
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
            {!! Form::open(['route' => 'product.import.store', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true, 'method' => 'post']) !!}
            <div class="card-body">
                @include('product.import_form')
            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="display: block;">
                <button type="submit" name="save" value="Save" class="btn btn-info mr-2">Save</button>
                {!! Html::decode(link_to(URL::full(), 'Cancel', ['class' => 'btn btn-warning cancel'])) !!}
            </div>
            {{ Form::close() }}
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
