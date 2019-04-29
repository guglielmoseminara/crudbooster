@extends("crudbooster::admin_template")
@section("content")

    @push('head')
        <link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>'/>
        <style>
            .select2-container--default .select2-selection--single {
                border-radius: 0px !important
            }

            .select2-container .select2-selection--single {
                height: 35px
            }
        </style>
    @endpush

    @push('bottom')
        <script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
        <script>
            $(function () {
                $('.select2').select2();

            })
            $(function () {
                $('select[name=table]').change(function () {
                    var v = $(this).val().replace(".", "_");
                    $.get("{{CRUDBooster::mainpath('check-slug')}}/" + v, function (resp) {
                        if (resp.total == 0) {
                            $('input[name=path]').val(v);
                        } else {
                            v = v + resp.lastid;
                            $('input[name=path]').val(v);
                        }
                    })

                })
            })

            function deleteParam(t) {
                $(t).parent().parent().remove();
                var no_params = 0;
                $('#table-parameters tbody tr').each(function () {
                    no_params += 1;
                    $(this).find('td:nth-child(1)').text(no_params);
                });
            }

            function addParam() {

                var row = $('<tr><td>#</td><td width="20%"><input class="form-control" name="params_name[]" type="text"/></td><td width="20%"><select class="form-control" name="params_type[]"><option value="string">String</option><option value="date">Date</option><option value="longText">Long Text</option><option value="integer">Integer</option><option value="biginteger">Big Integer</option><option value="boolean">Boolean</option><option value="float">Float</option></select></td><td class="col-delete"><a class="btn btn-danger" href="javascript:void(0)" onclick="deleteParam(this)"><i class="fa fa-ban"></i></a></td></tr>');

                $('#table-parameters tbody').append(row);

                /*var htm = $('#table-parameters tbody tr').clone();

                var val = $('#table-parameters tbody tr td:nth-child(2) input').val();
                var validation = $('#table-parameters tbody tr td:nth-child(3) select').val();
                var config = $('#table-parameters tbody tr td:nth-child(4) select').val();
                var m = $('#table-parameters tbody tr td:nth-child(5) select').val();
                var u = $('#table-parameters tbody tr td:nth-child(6) select').val();

                htm.find('td:nth-child(3)').find('select').val(validation);
                htm.find('td:nth-child(4)').find('select').val(config);
                htm.find('td:nth-child(5)').find('select').val(m);
                htm.find('td:nth-child(6)').find('select').val(u);

                $('#table-parameters tbody').append(htm);

                $('#table-parameters tbody tr').find('input[type=text]').val('');
                $('#table-parameters tbody tr').find('option').removeAttr('selected');*/

                var no_params = 0;
                $('#table-parameters tbody tr').each(function () {
                    no_params += 1;
                    $(this).find('td:nth-child(1)').text(no_params);
                    $(this).find('.col-delete').html("<a class='btn btn-danger' href='javascript:void(0)' onclick='deleteParam(this)'><i class='fa fa-ban'></i></a>");
                });
            }
            addParam()
        </script>
    @endpush

    <ul class="nav nav-tabs">
        @if($id)
            <li role="presentation" class="active"><a href="{{Route('ModulsControllerGetStep1',['id'=>$id])}}"><i class='fa fa-info'></i> Step 1 - Module
                    Information</a></li>
            <li role="presentation"><a href="{{Route('ModulsControllerGetStep2',['id'=>$id])}}"><i class='fa fa-table'></i> Step 2 - Table Display</a></li>
            <li role="presentation"><a href="{{Route('ModulsControllerGetStep3',['id'=>$id])}}"><i class='fa fa-plus-square-o'></i> Step 3 - Form Display</a>
            </li>
            <li role="presentation"><a href="{{Route('ModulsControllerGetStep4',['id'=>$id])}}"><i class='fa fa-wrench'></i> Step 4 - Configuration</a></li>
        @else
            <li role="presentation" class="active"><a href="#"><i class='fa fa-info'></i> Step 1 - Create table</a></li>
            <li role="presentation"><a href="#"><i class='fa fa-table'></i> Step 2 - Module Information</a></li>
            <li role="presentation"><a href="#"><i class='fa fa-table'></i> Step 3 - Table Display</a></li>
            <li role="presentation"><a href="#"><i class='fa fa-plus-square-o'></i> Step 4 - Form Display</a></li>
            <li role="presentation"><a href="#"><i class='fa fa-wrench'></i> Step 5 - Configuration</a></li>
        @endif
    </ul>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Create table</h3>
        </div>
        <div class="box-body">
            <form>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <table id='table-parameters' class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th width="5%">-</th>
                        </tr>
                        </thead>
                        <tbody>
                    </table>
                    <a class='btn btn-primary' href='javascript:void(0)' onclick='addParam()'><i class='fa fa-plus'></i>{{trans('crudbooster.add_field')}}</a>
            </form>




            <!--<form method="post" action="{{Route('ModulsControllerPostStep2')}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="id" value="{{$row->id}}">
                <div class="form-group">
                    <label for="">Table</label>
                    <select name="table" id="table" required class="select2 form-control" value="{{$row->table_name}}">
                        <option value="">{{trans('crudbooster.text_prefix_option')}} Table</option>
                        @foreach($tables_list as $table)

                            <option {{($table == $row->table_name)?"selected":""}} value="{{$table}}">{{$table}}</option>

                        @endforeach
                    </select>
                    <div class="help-block">
                        Do not use cms_* as prefix on your tables name
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Module Name</label>
                    <input type="text" class="form-control" required name="name" value="{{$row->name}}">
                </div>

                <div class="form-group">
                    <label for="">Icon</label>
                    <select name="icon" id="icon" required class="select2 form-control">
                        @foreach($fontawesome as $f)
                            <option {{($row->icon == 'fa fa-'.$f)?"selected":""}} value="fa fa-{{$f}}">{{$f}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Module Slug</label>
                    <input type="text" class="form-control" required name="path" value="{{$row->path}}">
                    <div class="help-block">Please alpha numeric only, without space instead _ and or special character</div>
                </div>
        </div>
        <div class="box-footer">

            <input checked type='checkbox' name='create_menu' value='1'/> Also create menu for this module <a href='#'
                                                                                                              title='If you check this, we will create the menu for this module'>(?)</a>

            <div class='pull-right'>
                <a class='btn btn-default' href='{{Route("ModulsControllerGetIndex")}}'> {{trans('crudbooster.button_back')}}</a>
                <input type="submit" class="btn btn-primary" value="Step 2 &raquo;">
            </div>
        </div>
        </form>!-->
    </div>


@endsection
