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
                        <h4 class="page-title">活動管理</h4>
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
                                        <label class="col-md-4 control-label">低消</label>
                                        <div class="col-md-8">
                                            <input type="number" name="money" class="form-control"  min="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">  狀態</label>
                                        <div class="col-md-8">
                                            <div class="form-control-static material-switch">
                                                <input id="add_status" name="status" type="checkbox"/>
                                                <label for="add_status" class="label-success"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">開始時間</label>
                                        <div class="col-md-4">
                                            <input type="date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="start_time" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">結束時間</label>
                                        <div class="col-md-4">
                                            <input type="date" name="end_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="end_time" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="m-b-30">
                                        <button id="add_button" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end: page -->
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <form action="/admin/activity" method="get" class="form-horizontal with-pagination">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">開始時間範圍</label>
                                        <div class="col-md-4">
                                            <input type="date" name="start_date" class="form-control" value="{{ $request->start_date }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="start_time" class="form-control" value="{{ $request->start_time }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-center">至</label>
                                        <div class="col-md-4">
                                            <input type="date" name="start_to_date" class="form-control" value="{{ $request->start_to_date }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="start_to_time" class="form-control" value="{{ $request->start_to_time }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">結束時間範圍</label>
                                        <div class="col-md-4">
                                            <input type="date" name="end_date" class="form-control" value="{{ $request->end_date }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="end_time" class="form-control" value="{{ $request->end_time }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-center">至</label>
                                        <div class="col-md-4">
                                            <input type="date" name="end_to_date" class="form-control" value="{{ $request->end_to_date }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" name="end_to_time" class="form-control" value="{{ $request->end_to_time }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">活動名稱</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="name" placeholder="活動名稱" value="{{ $request->name }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="m-b-30">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end: page -->
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>活動名稱</th>
                                    <th>簡述</th>
                                    <th>低消</th>
                                    <th>開始時間</th>
                                    <th>結束時間</th>
                                    <th>狀態</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1?>
                            @foreach ($activities as $activity)
                                <tr>
                                    <td>{{ $i + ($activities->currentPage() - 1) * 10 }}</td>
                                    <td>{{ $activity->name }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->money }}</td>
                                    <td>{{ $activity->start_time }}</td>
                                    <td>{{ $activity->end_time }}</td>
                                    <td>{{ $activity->status?'開放':'未開放' }}</td>
                                    <td>
                                        <form action="/admin/activity/delete" method="post" class="b-0 btn p-0">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $activity->id }}">
                                            <input type="submit" class="btn btn-danger" value="刪除">
                                        </form>
                                        <a href="/admin/activity/edit/{{ $activity->id }}" class="btn btn-primary waves-effect waves-light">修改</a>
                                    </td>
                                </tr>
                                <?php $i++?>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $activities->links() !!}
                    </div>
                </div>
                <!-- end: page -->
            </div>
            <!-- end Panel -->
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
    $("#add_button").on('click', function(event){
        event.preventDefault();
        var form = $('#add_button').parents('form').eq(0);
        var name = form.find('[name="name"]').val();
        var start_date = form.find('[name="start_date"]').val();
        var start_time = form.find('[name="start_time"]').val();
        var end_date = form.find('[name="end_date"]').val();
        var end_time = form.find('[name="end_time"]').val();
        var money = form.find('[name="money"]').val();
        if(name != "" && start_date != "" && start_time != "" && end_date != "" && end_time != "" && money != "" ){
            $.ajax({
                url: '/admin/activity/add',
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: function(res){
                    location.reload();
                    // console.log(res)
                },
                error: function(e){
                    swal(
                        '活動',
                        '新增失敗，請洽系統管理商！',
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