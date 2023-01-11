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
                            <th></th>
                            <th>
                                <div class="datatable-form-filter">{!! Form::text('filter_shop', Request::get('filter_shop', null), ['class' => 'form-control rounded-0', 'placeholder' => 'Shop']) !!}</div>
                            </th>

                            <th>
                                <div class="datatable-form-filter">{!! Form::text('filter_name', Request::get('filter_name', null), ['class' => 'form-control rounded-0', 'placeholder' => 'Product Name']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter">{!! Form::text('filter_price', Request::get('filter_price', null), ['class' => 'form-control rounded-0', 'placeholder' => 'Product Price']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter">{!! Form::select('filter_stock', [''=>'-Select-','no'=>'No','yes'=>'Yes'], Request::get('filter_stock', null), ['class' => 'form-control rounded-0']) !!}</div>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Sr.No.</th>
                            <th>Shop</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Stock</th>
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
                        d.shop_id = jQuery(".datatable-form-filter input[name='filter_shop']").val();
                        d.name = jQuery(".datatable-form-filter input[name='filter_name']").val();
                        d.price = jQuery(".datatable-form-filter input[name='filter_price']").val();
                        d.stock = jQuery(".datatable-form-filter select[name='filter_stock']").val();
                    }
                },
                "columns": [
                    {
                        "name": "id",
                        "data": "id",
                        "class": "",
                        "searchable": false,
                        "orderable": false,
                        "render": function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        "name": "shop_id",
                        "data": "shop_id",
                        "class": "",
                        "searchable": true,
                        "orderable": false
                    },
                    {
                        "name": "name",
                        "data": "name",
                        "class": "",
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "name": "price",
                        "data": "price",
                        "class": "",
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "name": "is_stock",
                        "data": "is_stock",
                        "class": "",
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        "name": "action",
                        "data": "action",
                        "class": "text-left",
                        "render": null,
                        "searchable": false,
                        "orderable": false,
                        "width": "150px"
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
        jQuery('.datatable-form-filter select').on('change', function(e) {
            window.LaravelDataTables["dataTableBuilder"].draw();
            e.preventDefault();
        });
    </script>
@endpush
