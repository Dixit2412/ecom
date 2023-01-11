@extends($theme)
@section('title', $title)
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card card-info">
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
            {{ Form::model($product, ['method' => 'PATCH', 'route' => ['product.update', $product], 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'add_modal_form','files'=> true]) }}
            <div class="card-body">
                @include('product.form')
            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="display: block;">
                <button type="submit" name="update" value="Update" class="btn btn-info mr-2 rounded-0">Save</button>
                @if (!Request::has('download'))
                    <button type="submit" name="update_exit" value="Update & Exit" class="btn btn-info mr-2 rounded-0">Update & Exit</button>
                @endif
                {!! Html::decode(link_to(URL::full(), 'Cancel', ['class' => 'btn btn-warning cancel rounded-0'])) !!}
            </div>
            {{ Form::close() }}
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
@push('scripts')
@endpush
