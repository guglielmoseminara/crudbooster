@extends('crudbooster::admin_template')

@section('content')

    @push('head')
        <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
    @endpush
    @push('bottom')
        <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.wysiwyg').summernote();
            })
        </script>
    @endpush

    @include('crudbooster::api_generator._generator.tabs')

    <div class='box'>

        <div class='box-body'>
            @push('bottom')
                @include('crudbooster::api_generator._generator.script')
            @endpush

            @push('head')
                <style>
                    .selected_text {
                        cursor: pointer;
                    }

                    .selected_text:hover {
                        color: #76a400
                    }

                    tfoot td {
                        background: #eeeeee
                    }

                    .tr-response {
                        cursor: pointer
                    }
                </style>
            @endpush


            <form method='post' action='{{ route("AdminApiGeneratorControllerPostSaveApiCustom")}}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id" value="{{$row->id}}">
                <div class='row'>
                    <div class='col-sm-8'>
                        <div class='form-group'>
                            <label>API Name</label>
                            <input type='text' class='form-control' value='{{$row->nama}}' required name='nama'
                                   id='input-nama'/>
                        </div>
                    </div>

                    <div class='col-sm-4'>
                        <div class='form-group'>
                            <label>Table</label>
                            <select id='combo_tabel' name='tabel' required class='form-control'>
                                <option value=''>** Choose a Table</option>
                                @foreach($tables as $tab)
                                    <option {{($row->tabel == $tab)?"selected":""}} value='{{$tab}}'>{{$tab}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-sm-8'>
                        <div class='form-group'>
                            <label>API Slug</label>
                            <div class='input-group'>
                                <span class="input-group-addon" id="basic-addon1" style="background:#eeeeee">{{url("api")}}
                                    /</span>
                                <input type='text' class='form-control' value='{{$row->permalink}}' required
                                       name='permalink' id='input-permalink'/>
                            </div>
                        </div>
                    </div>
                    <div class='col-sm-2'>
                        <div class='form-group'>
                            <label>Action Type</label>
                            <select id='tipe_action' name='aksi' required class='form-control'>
                                <option value=''>** Select Action</option>
                                <option value='list' {{ ($row->aksi == 'list')?"selected":"" }} >LISTING</option>
                                <option value='detail' {{ ($row->aksi == 'detail')?"selected":"" }}>DETAIL / READ
                                </option>
                                <option value='save_add' {{ ($row->aksi == 'save_add')?"selected":"" }}>CREATE / ADD
                                </option>
                                <option value='save_edit' {{ ($row->aksi == 'save_edit')?"selected":"" }}>UPDATE
                                </option>
                                <option value='delete' {{ ($row->aksi == 'delete')?"selected":"" }}>DELETE</option>
                            </select>
                        </div>
                    </div>
                    <div class='col-sm-2'>
                        <div class='form-group'>
                            <label>Method Type</label>
                            <br/>
                            <label class='radio-inline'>
                                <input type='radio' required class='method_type'
                                       {{ ($row->method_type == 'get')?"checked":"" }} name='method_type' value='get'/>
                                GET
                            </label>
                            <label class='radio-inline'>
                                <input type='radio' class='method_type'
                                       {{ ($row->method_type == 'post')?"checked":"" }} name='method_type'
                                       value='post'/> POST
                            </label>

                        </div>
                    </div>
                </div>


                <div class='form-group'>
                    <div class="clearfix">
                        <label><i class='fa fa-cog'></i> Parameters</label>
                        <a class='pull-right btn btn-xs btn-primary' href='javascript:void(0)'
                           onclick="load_parameters()"><i class='fa fa-refresh'></i> Reset</a>
                    </div>

                    <table id='table-parameters' class='table table-striped table-bordered'>
                        <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Laravel Validation / Description / Value</th>
                            <th width="8%" title='is Mandatory ?'>Mandatory</th>
                            <th width="8%" title='is used ?'>Enable</th>
                            <th width="5%">-</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class='row-no-data'>
                            <td colspan='7'>There is no data</td>
                        </tr>
                        </tbody>
                        <tfoot style="display:none">
                        <tr>
                            <td>#</td>
                            <td width="20%"><input class='form-control' name='params_name[]' type='text'/></td>
                            <td width="20%"><select class='form-control' name='params_type[]'>
                                    <optgroup label='Common Validation'>
                                        <option value='string'>String</option>
                                        <option value='integer'>Integer</option>
                                        <option value='email'>Email</option>
                                        <option value='image'>Image (jpeg, png, bmp, gif, or svg)</option>
                                        <option value='file'>File Upload</option>
                                        <option value='exists'>Exists (table,column)</option>
                                        <option value='unique'>Unique (table,column,except)</option>
                                        <option value='password'>Password</option>
                                        <option value='search'>Search</option>
                                        <option value='custom'>Custom (Not In Table)</option>
                                    </optgroup>
                                    <optgroup label='Other Validation'>
                                        <option value='array'>Array</option>
                                        <option value='alpha'>Alpha</option>
                                        <option value='alpha_num'>Alpha Numeric</option>
                                        <option value='alpha_spaces'>Alpha Spaces</option>
                                        <option value='base64_file'>Base64 File</option>
                                        <option value='boolean'>Boolean</option>
                                        <option value='date'>Date (Y-m-d)</option>
                                        <option value='date_format:Y-m-d H:i:s'>DateTime (Y-m-d H:i:s)</option>
                                        <option value='date_format'>Date Format Custom</option>
                                        <option value='digits'>Digits</option>
                                        <option value='digits_between'>Digits Between (Min,Max)</option>
                                        <option value='in'>In (a,b,c)</option>
                                        <option value='json'>Json Valid</option>
                                        <option value='mimes'>Mimes Type</option>
                                        <option value='min'>Min</option>
                                        <option value='max'>Max</option>
                                        <option value='numeric'>Numeric</option>
                                        <option value='not_in'>Not In (a,b,c)</option>
                                        <option value='url'>URL Valid</option>
                                    </optgroup>
                                    <optgroup label='Other'>
                                        <option value='ref'>Child Table References</option>
                                    </optgroup>
                                </select></td>
                            <td><input class='form-control' type='text' name='params_config[]'></td>
                            <td><select class='form-control params_required' name='params_required[]'>
                                    <option value='1'>YES</option>
                                    <option value='0'>NO</option>
                                </select></td>
                            <td><select class='form-control params_used' name='params_used[]'>
                                    <option value='1'>YES</option>
                                    <option value='0'>NO</option>
                                </select></td>
                            <td class='col-delete'><a class='btn btn-primary' href='javascript:void(0)'
                                                      onclick='addParam()'><i class='fa fa-plus'></i></a></td>
                        </tr>
                        </tfoot>
                    </table>

                    <div class="help-block">
                        To set as comment at description. Add prefix * (asterisk) before description. Unless will be set
                        as default value.
                    </div>
                </div>

                <div class='form-group'>
                    <label>SQL Where Query (Optional)</label>
                    <textarea name='sql_where' rows='3' class='form-control'
                              placeholder="status = '[paramStatus]'">{{$row->sql_where}}</textarea>
                    <div class='help-block'>Use [paramName] to get the parameter value. e.g : [id]</div>
                </div>

                <div class='form-group'>
                    <div class='clearfix'>
                        <label><i class='fa fa-cog'></i> Response</label>
                        <a class='pull-right btn btn-xs btn-primary' href='javascript:void(0)'
                           onclick='load_response()'><i class='fa fa-refresh'></i> Reset</a>
                    </div>
                    <div id='response'>
                        <table id='table-response' class='table table-striped table-bordered'>
                            <thead>
                            <tr>
                                <th width="3%">No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Sub Query</th>
                                <th width="8%">Enable</th>
                                <th width="3%">-</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class='row-no-data'>
                                <td colspan='7'>There is no data</td>
                            </tr>
                            </tbody>
                            <tfoot style="display:none">
                            <tr class='tr-additional'>
                                <td>#</td>
                                <td width="20%"><input placeholder='E.g : grand_total' name='responses_name[]'
                                                       class='form-control' type='text'/>
                                    <small>Enter alias name</small>
                                </td>
                                <td>
                                    <select class='form-control' name='responses_type[]'>
                                        <option value='integer'>Integer</option>
                                        <option value='boolean'>Boolean</option>
                                        <option value='string'>String</option>
                                        <option value='file'>File</option>
                                        <option value='date'>Date</option>
                                        <option value='datetime'>DateTime</option>
                                        <option value='double'>Double</option>
                                        <option value='custom'>Custom (Not in Table)</option>
                                    </select>
                                </td>
                                <td>
                                    <input placeholder="E.g : select sum(total) from order_detail where id_order = order.id"
                                           name='responses_subquery[]' class='form-control' type='text'>
                                    <small>Enter sub query without alias name</small>
                                </td>
                                <td><select class='form-control responses_used' name='responses_used[]'>
                                        <option value='1'>YES</option>
                                        <option value='0'>NO</option>
                                    </select></td>
                                <td class='col-delete'><a class='btn btn-primary' href='javascript:void(0)'
                                                          onclick='addResponse()'><i class='fa fa-plus'></i></a></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class='form-group'>
                    <label>API Description</label>
                    <textarea name='keterangan' rows='3' class='form-control wysiwyg'
                              placeholder='Optional'>{{$row->keterangan}}</textarea>
                </div>

                <div class='form-group'>
                    <input type='submit' class='btn btn-success' value='SAVE & GENERATE API'/>
                </div>

            </form>
        </div><!--END BODY-->
    </div><!--END BOX-->

@endsection
