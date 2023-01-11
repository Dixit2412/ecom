@extends($theme)
@section('title', $title)
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{!! $module_title !!}</h3>
                <div class="card-tools">
                    @if (!empty($action_data))
                        @foreach ($action_data as $key => $action)
                            {!! Html::decode(Html::link($action['url'], $action['title'], $action['attributes'])) !!}
                        @endforeach
                    @endif
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table datatable-basic dataTable table-hover no-footer table-bordered table-striped"
                    id="dataTableBuilder">
                    <thead>
                        <tr>
                            <th>
                                <div class="datatable-form-filter">{!! Form::text('filter_name', Request::get('filter_name', null), ['class' => 'form-control rounded-0', 'placeholder' => 'Name']) !!}</div>
                            </th>

                            <th>
                                <div class="datatable-form-filter">{!! Form::text('filter_email', Request::get('filter_email', null), ['class' => 'form-control rounded-0', 'placeholder' => 'E-mail']) !!}</div>
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <th>E-mail</th>
                            <th>Address</th>
                            <th class="action_width">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        (function(window, $) {
            window.LaravelDataTables = window.LaravelDataTables || {};
            window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    data: function(d) {
                        d.name = jQuery(".datatable-form-filter input[name='filter_name']").val();
                        d.email = jQuery(".datatable-form-filter input[name='filter_email']").val();
                    }
                },
                "columns": [{
                        "name": "name",
                        "data": "name",
                        "class": "",
                        "searchable": true,
                        "orderable": true
                    },
                    {
                        "name": "email",
                        "data": "email",
                        "class": "",
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "name": "address",
                        "data": "address",
                        "class": "",
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "name": "action",
                        "data": "action",
                        "class": "text-center",
                        "render": null,
                        "searchable": false,
                        "orderable": false,
                        "width": "80px"
                    },
                ],
                "searching": false,
                dom: "<'row'l<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-6'i><'col-sm-6 dt-footer-right'p>>",
                "buttons": [],
                "order": []
            });
        })(window, jQuery);
        jQuery('.datatable-form-filter input').on('keyup', function(e) {
            window.LaravelDataTables["dataTableBuilder"].draw();
            e.preventDefault();
        });
    </script>
@endpush
