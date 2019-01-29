@extends('layouts.main')

@section('title', '项目管理')

@section('content-header', '项目管理')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><i class="fa fa-list-ul active"></i> 权限管理</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>
        <div class="col-lg-12">
            {{-- 提醒 --}}
            @include('shared.alert')

            <section class="panel panel-default">
                <header class="panel-heading"> 项目列表</header>
                <div class="row text-sm wrapper">
                    <form method="GET" action="{{ route('projects.index') }}">
                        <div class="col-sm-5 m-b-xs">
                            <a href="javascript:void(0)" class="btn btn-default btn-sm" id="add_btn" onclick="showAdd()">添加党组织</a>
                        </div>
                        <div class="col-sm-4 m-b-xs"></div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input name="name" type="text" class="input-sm form-control" value="{{$name}}"
                                       placeholder="输入党组织进行查询">
                                <span class="input-group-btn">
                                <button class="btn btn-sm btn-default" type="submit">查询</button>
                            </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light text-sm">
                        <thead>
                        <tr>
                            <th>党组织名称</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $item)
                            <tr>
                                <td width="70%" style="padding-top:10px">
                                    <span id="name_{{$item->id}}">{{$item->name}}</span>
                                    <input id="name_input_{{$item->id}}" type="text" class="form-control col-xs-1 input-sm" placeholder="党组织名称" value="{{$item->name}}" style="display:none">
                                </td>
                                <td style="padding-top:10px">
                                    <span id="manage_{{$item->id}}">
                                        <a href="javascript:void(0)" onclick="showEdit({{$item->id}})">编辑</a>
                                        @if($item->project_administrator)
                                            <a href="{{ route('projects.user.edit', ['project' => $item->id, 'user' => $item->project_administrator->id]) }}">修改维护员</a>
                                        @else
                                            <a href="{{ route('projects.user.create_user', ['project' => $item->id, 'role' => 'administrator']) }}">添加维护员</a>
                                        @endif

                                        @if($item->project_maintainer)
                                            <a href="{{ route('projects.user.edit', ['project' => $item->id, 'user' => $item->project_maintainer->id]) }}">修改管理员</a>
                                        @else
                                            <a href="{{ route('projects.user.create_user', ['project' => $item->id, 'role' => 'maintainer']) }}">添加管理员</a>
                                        @endif

                                        {{--<a href="{{ route('devices.destroy', ['id' => 1]) }}">删除</a>--}}
                                        <a href="{{route('projects.show', ['id' => $item->id])}}">查看</a>
                                    </span>
                                    <span id="edit_btn_{{$item->id}}" style="display:none">
                                        <button type="button" class="btn btn-success btn-sm" onclick="edit({{$item->id}})">提交</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="showEdit({{$item->id}})">取消</button>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                            <tr id="add_form" style="display:none;">
                                <td width="70%">
                                    <input id="name" name="name" type="text" class="form-control col-xs-2 input-sm" placeholder="党组织名称"
                                       value="">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" onclick="add()">提交</button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hideAdd()">取消</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-4 hidden-xs">
                        </div>
                        <div class="col-sm-4 text-center">
                            <small class="text-muted inline m-t-sm m-b-sm">
                                当前显示 {{($list->currentPage() - 1) * $list->perPage()}}
                                -{{($list->currentPage()) * $list->perPage()}} | 总数 {{$list->total()}} </small>
                        </div>
                        <div class="col-sm-4 text-right text-center-xs">
                            {{$list->links()}}
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#add_btn').on('click', function () {
        });

        function showAdd(){
            $('#add_form').show();
        }

        function hideAdd(){
            $('#add_form').hide();
        }

        function showEdit(id){
            $('#name_'+id).toggle();
            $('#name_input_'+id).toggle();
            $('#manage_'+id).toggle();
            $('#edit_btn_'+id).toggle();
        }

        function add(){
            if ($('#name').val() == '')
                alert('请输入党支部名称。');
            $.ajax({
                url: "/projects/add",
                method: "POST",
                data: {name:$('#name').val()},
                dataType: "json",
                success: function success(data) {
                    if (data.error != 0) {
                        window.location.reload();
                    }
                }
            });
        }

        function edit(id){
            if ($('#name_input_'+id).val() == '')
                alert('请输入党支部名称。');
            $.ajax({
                url: "/projects/edit",
                method: "POST",
                data: {name:$('#name_input_'+id).val(), id:id},
                dataType: "json",
                success: function success(data) {
                    if (data.error != 0) {
                        window.location.reload();
                    }
                }
            });
        }
    </script>
@endsection