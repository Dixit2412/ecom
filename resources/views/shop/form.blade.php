<div class="row">
    <div class="col-6">
        <div class="form-group row {{ $errors->has('name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('name', 'Name:<span class="text-danger">*</span>', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                {!! Form::text('name', null, ['class' => 'form-control rounded-0','placeholder'=>'Name', 'id' => 'name','autofocus']) !!}
                {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group row {{ $errors->has('email') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('email', 'E-mail:<span class="text-danger">*</span>', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                {!! Form::text('email', null, ['class' => 'form-control rounded-0','placeholder'=>'E-mail', 'id' => 'email']) !!}
                {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row pt-2">
    <div class="col-6">
        <div class="form-group row {{ $errors->has('address') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('address', 'Address:', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                {!! Form::textarea('address', null, ['class' => 'form-control rounded-0','placeholder'=>'Address', 'id' => 'address','rows'=>'2']) !!}
                {!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group row {{ $errors->has('password') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('password', 'Password:<span class="text-danger">*</span>', ['class' => 'control-label col-2'])) !!}
            <div class="col-7">
                <input class="form-control rounded-0" placeholder="Password" name="password" type="password" value="" id="password">
                {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
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
@endpush