<div class="row">
    <div class="col-6">
        <div class="form-group row {{ $errors->has('shop_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('shop_id', 'Shop:<span class="text-danger">*</span>', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                {!! Form::select('shop_id', [''=>'-Select-']+$shop, null, ['class' => 'form-control rounded-0 shop_select', 'id' => 'shop_id','autofocus']) !!}
                {!! ($errors->has('shop_id') ? $errors->first('shop_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group row {{ $errors->has('name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('name', 'Name:<span class="text-danger">*</span>', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                {!! Form::text('name', null, ['class' => 'form-control rounded-0','placeholder'=>'Name', 'id' => 'name','autofocus']) !!}
                {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row pt-2">
    <div class="col-6">
        <div class="form-group row {{ $errors->has('price') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('price', 'Price:<span class="text-danger">*</span>', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                {!! Form::text('price', null, ['class' => 'form-control rounded-0 allownumeric','placeholder'=>'1020', 'id' => 'price']) !!}
                {!! ($errors->has('price') ? $errors->first('price', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group row {{ $errors->has('is_stock') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('is_stock', 'Stock:', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                {!! Form::select('is_stock', ['yes'=>'Yes','no'=>'No'], null, ['class' => 'form-control rounded-0', 'id' => 'is_stock']) !!}
                {!! ($errors->has('is_stock') ? $errors->first('is_stock', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>

<div class="row pt-2">
    <div class="col-6">
        <div class="form-group row">
            {!! Html::decode(Form::label('image', 'Image:', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                {{ Form::file('image',array("onchange"=>"readURL(this)",'accept'=>'image/*'), ['class'=>'form-control']) }}
                {!! ($errors->has('image') ? $errors->first('image', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        jQuery(document).ready(function() {
            jQuery('.shop_select').select2();
        });
    </script>
@endpush