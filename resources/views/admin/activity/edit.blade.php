@extends('admin.layout')
@section('head')
    <style type="text/css">
        .material-switch > input[type="checkbox"] {
            display: none;
        }

        .material-switch > label {
            cursor: pointer;
            height: 0px;
            position: relative;
            width: 40px;
        }

        .material-switch > label::before {
            background: rgb(0, 0, 0);
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            content: '';
            height: 16px;
            margin-top: -8px;
            position:absolute;
            opacity: 0.3;
            transition: all 0.4s ease-in-out;
            width: 40px;
        }
        .material-switch > label::after {
            background: rgb(255, 255, 255);
            border-radius: 16px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            content: '';
            height: 24px;
            left: -4px;
            margin-top: -8px;
            position: absolute;
            top: -4px;
            transition: all 0.3s ease-in-out;
            width: 24px;
        }
        .material-switch > input[type="checkbox"]:checked + label::before {
            background: inherit;
            opacity: 0.5;
        }
        .material-switch > input[type="checkbox"]:checked + label::after {
            background: inherit;
            left: 20px;
        }
    </style>
@stop
@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title">活動修改</h4>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">活動名稱</label>
                                        <div class="col-md-8">
                                            <input type="text" name="name" class="form-control" value="{{ $activity->name }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">簡述</label>
                                        <div class="col-md-8">
                                            <input type="text" name="description" class="form-control" value="{{ $activity->description }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">低消</label>
                                        <div class="col-md-8">
                                            <input type="number" name="money" class="form-control"  min="0" value="{{ $activity->money }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">狀態</label>
                                        <div class="col-md-8">
                                            <div class="form-control-static material-switch">
                                                <input id="update_status" name="status" type="checkbox" @if( $activity->status) checked @endif />
                                                <label for="update_status" class="label-success"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">開始時間</label>
                                        <div class="col-md-4">
                                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d',strtotime($activity->start_time)) }}"required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="start_time" class="form-control" value="{{ date('H:i:s',strtotime($activity->start_time)) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">結束時間</label>
                                        <div class="col-md-4">
                                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d',strtotime($activity->end_time)) }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="end_time" class="form-control" value="{{ date('H:i:s',strtotime($activity->end_time)) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="m-b-30">
                                        <input type="hidden" name="id" value="{{ $activity->id }}" required>
                                        <button id="update_button" class="btn btn-primary">修改 <i class="fa fa-pencil"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>獎項名稱</th>
                                    <th>簡述</th>
                                    <th>機率</th>
                                    <th>狀態</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <?php $i = 1?>
                            @foreach ($prizes as $prize)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td><input type="text" name="name" class="form-control" value="{{ $prize->name }}" required></td>
                                    <td><input type="text" name="description" class="form-control" value="{{ $prize->description }}"></td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" name="probability" class="form-control" value="{{ $prize->probability }}" required>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-control-static material-switch">
                                            <input id="add_status_{{ $i }}" name="status" type="checkbox" @if( $prize->status) checked @endif />
                                            <label for="add_status_{{ $i }}" class="label-success"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <button data-id="{{ $prize->id }}" type="button" class="btn btn-primary waves-effect waves-light prize_update">修改</button>
                                        <form action="/admin/prize/delete" method="post" class="b-0 btn p-0">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $prize->id }}">
                                            <input type="submit" class="btn btn-danger" value="刪除">
                                        </form>
                                    </td>
                                </tr>
                                <?php $i++?>
                            @endforeach
                        </table>
                    </div>
                </div>
                <!-- end: page -->
            </div>
            <div class="panel">
                <div class="panel-heading">新增獎項</div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">獎項名稱</label>
                                        <div class="col-md-8">
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">簡述</label>
                                        <div class="col-md-8">
                                            <input type="text" name="description" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">機率</label>
                                        <div class="col-md-8">

                                        <div class="input-group">
                                            <input type="number" name="probability" class="form-control"  min="0" required>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">狀態</label>
                                        <div class="col-md-8">
                                            <div class="form-control-static material-switch">
                                                <input id="add_status" name="status" type="checkbox"/>
                                                <label for="add_status" class="label-success"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="m-b-30">
                                        <input type="hidden" name="activity_id" value="{{ $activity->id }}" required>
                                        <button id="add_button" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end container -->
    </div>
    <!-- end content -->
    <!-- FOOTER -->
    <footer class="footer text-right">
        2017 © stevia-network.
    </footer>
    <!-- End FOOTER -->
</div>

@stop
@section('script')
<script type="text/javascript">
$(function(){
    $("#update_button").on('click', function(event){
        event.preventDefault();
        var form = $('#update_button').parents('form').eq(0);
        var name = form.find('[name="name"]').val();
        var start_date = form.find('[name="start_date"]').val();
        var start_time = form.find('[name="start_time"]').val();
        var end_date = form.find('[name="end_date"]').val();
        var end_time = form.find('[name="end_time"]').val();
        var money = form.find('[name="money"]').val();
        if(name != "" && start_date != "" && start_time != "" && end_date != "" && end_time != "" && money != "" ){
            $.ajax({
                url: '/admin/activity/update',
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: function(res){
                    swal(
                        '修改成功',
                        '',
                        'success'
                    );
                },
                error: function(e){
                    swal(
                        '修改失敗',
                        '請洽系統管理商！',
                        'error'
                    );
                }
            });
        }
        else{
            swal(
                '不得為空！',
                '活動名稱 開始時間 結束時間 低消',
                'error'
            );
        }
    });
    $("#add_button").on('click', function(event){
        event.preventDefault();
        var form = $('#add_button').parents('form').eq(0);
        var name = form.find('[name="name"]').val();
        var probability = form.find('[name="probability"]').val();
        if(name != "" && probability != "" ){
            $.ajax({
                url: '/admin/prize/add',
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: function(res){
                    // location.reload();
                    console.log(res);
                    if(res.error){
                        swal(
                            '錯誤！',
                            res.message,
                            'error'
                        );
                    }else{
                        location.reload();
                    }

                },
                error: function(e){
                    swal(
                        '新增失敗',
                        '請洽系統管理商！',
                        'error'
                    );
                }
            });
        }
        else{
            swal(
                '不得為空！',
                '獎項名稱 機率',
                'error'
            );
        }
    });
    $(".prize_update").on('click', function(event){
        event.preventDefault();
        var id = $(this).data('id');
        var item = $(this).parents('tr').eq(0);
        var name = item.find('[name="name"]').val();
        var probability = item.find('[name="probability"]').val();
        // console.log(id);
        if(name != "" && probability != "" ){
            $.ajax({
                url: '/admin/prize/update',
                type: 'post',
                dataType: 'json',
                data: {
                    'id':id,
                    'name':name,
                    'description':item.find('[name="description"]').val(),
                    'probability':probability,
                    'status':((item.find('[name="status"]').is(":checked"))?'on':''),
                },
                success: function(res){
                    if(res.error){
                        swal(
                            '錯誤！',
                            res.message,
                            'error'
                        );
                    }else{
                        swal(
                            '修改成功',
                            '',
                            'success'
                        );
                    }
                },
                error: function(e){
                    swal(
                        '修改失敗',
                        '請洽系統管理商！',
                        'error'
                    );
                }
            });
        }
        else{
            swal(
                '不得為空！',
                '活動名稱 開始時間 結束時間 低消',
                'error'
            );
        }
    });
});
</script>
@stop