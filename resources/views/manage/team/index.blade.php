@extends('layouts.main')

@section('title', '集体照管理')

@section('content-header', '集体照管理')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><i class="fa fa-list-ul active"></i> 集体照管理</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>
        <div class="col-lg-12">
            {{-- 提醒 --}}
            @include('shared.alert')

            <section class="panel panel-default">
                <header class="panel-heading"> 党支部列表</header>
                <div class="row text-sm wrapper">
                    <form method="GET" action="{{ route('projects.index') }}">
                        <div class="col-sm-5 m-b-xs">
                            <a href="javascript:void(0)" class="btn btn-default btn-sm" id="add_btn" onclick="showAdd()">添加党支部</a>
                        </div>
                        <div class="col-sm-4 m-b-xs"></div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input name="name" type="text" class="input-sm form-control" value="{{$name}}"
                                       placeholder="输入党支部进行查询">
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
                            <th>集体照</th>
                            <th>集体视频</th>
                            <th>党支部名称</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $item)
                            <tr>
                                <td width="20%" style="padding-top:10px">
                                    @if(isset($item->image))
                                    <a href="{{$item->image}}" target="_blank"><img src="{{$item->image}}?imageView2/1/w/50/h/50"/></a>
                                    @endif
                                </td>
                                <td width="15%">
                                    @if(isset($item->video))
                                    <a href="{{$item->video}}" target="_blank">
                                        <img src="{{$item->video}}?vframe/jpg/offset/1" style="width:50px;height:50px"/>
                                    </a>
                                    @endif
                                </td>
                                <td width="60%" style="padding-top:10px">
                                    <span id="name_{{$item->id}}">{{$item->name}}</span>
                                    <input id="name_input_{{$item->id}}" type="text" class="form-control col-xs-1 input-sm" placeholder="党支部名称" value="{{$item->name}}" style="display:none">
                                </td>
                                <td style="padding-top:10px">
                                    <span id="manage_{{$item->id}}">
                                        <a href="javascript:void(0)" onclick="showEdit({{$item->id}})">编辑</a>
                                        <a href="javascript:void(0)" onclick="del({{$item->id}})">删除</a>
                                    </span>
                                    <span id="edit_btn_{{$item->id}}" style="display:none">
                                        <button type="button" class="btn btn-success btn-sm" onclick="edit({{$item->id}})">提交</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="showEdit({{$item->id}})">取消</button>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                            <tr id="add_form" style="display:none;">
                                <td width="20%">
                                </td>
                                <td width="60%">
                                    <input id="name" name="name" type="text" class="form-control col-xs-2 input-sm" placeholder="党支部名称"
                                       value="">
                                </td>
                                <td width="20%">
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
            if ($('#name').val() == ''){
                alert('请输入党支部名称。');
                return;
            }
            $.ajax({
                url: "/team/add",
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
            if ($('#name_input_'+id).val() == ''){
                alert('请输入党支部名称。');
                return;
            }
            $.ajax({
                url: "/team/edit",
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

      function del(id){
          if (confirm('确定要删除吗？')==false){
              return ;
          }
          $.ajax({
              url: "/team/del",
              method: "POST",
              data: {id:id},
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