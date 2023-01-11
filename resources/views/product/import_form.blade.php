<div class="row">
    <div class="col-12 pl-0">
        <div class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
            {!! Html::decode(Form::label('file', 'Import File:<span class="text-danger">*</span>', ['class' => 'control-label '])) !!}
            {!! Form::file('file', null, ['class' => 'form-control rounded-0 customTabindex', 'id' => 'file','autofocus']) !!}
            {!! $errors->has('file') ? $errors->first('file', '<p class="text-danger">:message</p>') : '' !!}
        </div>
    </div>
    <div class="col-12 pt-2">
        <div class="form-group>
            {!! Html::decode(Form::label('file', 'Sample File:', ['class' => 'control-label '])) !!}
            <a href="/sample/product.csv" download class="btn btn-sm btn-primary"> Download</a>
        </div>
    </div>
</div>