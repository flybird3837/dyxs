@extends('layouts.main')

@section('title', '党员信息管理')

@section('content-header', '党员信息管理')
@section('content')
    <div class="row">

        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li class="active"><i class="fa fa-cogs icon"></i> 党员信息管理</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>
        <div class="col-lg-12">
            @include('shared.alert')
            <section class="panel panel-default">
                <header class="panel-heading"> 党员信息列表</header>
                <div class="row text-sm wrapper">
                    <div class="col-sm-2 m-b-xs">
                        @if(auth()->user()->hasRole('project_manage'))
                        <a target="_blank" href="/xls/template.xlsx" class="btn btn-default fa fa-download icon">下载表格模板</a>
                        @endif
                    </div>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="pull-right">
                                    <form class="form-inline" role="form" onsubmit="return false">
                                        <div class="form-group">
                                            <input type="file" id="file_upload"/> 
                                        </div>
                                        <div class="form-group">
                                            <!--<input type="button" value="上传" id="upload"/> -->
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-success btn-sm fa fa-angle-double-down icon" id="upload">导入</button>
                                            <a target="_blank" href="{{route('dangyuan.download')}}" class="btn btn-info btn-sm fa fa-angle-double-up icon">导出</a>
                                        </div>
                                    </form>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                     <!--<input type="file" id="file_upload"/> 
                     <input type="button" value="上传" id="upload"/> 
                     <div style="background:#848484;width:100px;height:10px;margin-top:5px"> 
                     <div id="progressNumber" style="background:#428bca;width:0px;height:10px" > 
                     </div> 
                     </div> 
                     <font id="percent">0%</font> 
                    -->
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light text-sm">
                        <thead>
                        <tr>
                            <th>照片</th>
                            <th>姓名</th>
                            <th>性别</th>
                            <th>入党日期</th>
                            <th>视频</th>
                            <th>语音</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dangyuans as $item)
                            <tr>
                                <td>
                                    @if($item->image)
                                    <a href="{{$item->image}}" target="_blank"><img src="{{$item->image}}?imageView2/1/w/50/h/50"/></a>
                                    @endif
                                </td>
                                <td>
                                    <span  id="name_{{$item->id}}">{{$item->name}}</span>
                                    <input id="name_input_{{$item->id}}" type="text" class="form-control col-xs-1 input-sm" placeholder="姓名" value="{{$item->name}}" style="display:none">
                                </td>
                                <td>
                                    <span id="sex_{{$item->id}}">@if($item->sex == 1)男@else女@endif</span>
                                    <input id="sex_input_{{$item->id}}" type="text" class="form-control col-xs-1 input-sm" placeholder="性别" value="@if($item->sex == 1)男@else女@endif" style="display:none">
                                </td>
                                <td>
                                    <span id="in_time_{{$item->id}}">{{$item->in_time}}</span>
                                    <input id="in_time_input_{{$item->id}}" type="text" class="form-control col-xs-1 input-sm" placeholder="性别" value="{{$item->in_time}}" style="display:none">
                                </td>
                                <td>
                                     @if($item->video)
                                     <a href="{{$item->video}}" target="_blank">
                                    <img src="{{$item->video}}?vframe/jpg/offset/1" style="width:50px;height:50px"/>
                                    </a>
                                     @endif
                                </td>
                                <td>
                                    @if($item->audio)
                                        <a href="{{$item->audio}}" target="_blank"><i class="fa fa-play-circle-o icon" style="font-size:50px">
                                            <b class="bg-danger"></b>
                                        </i></a>
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->hasRole('project_manage'))
                                    <span id="manage_{{$item->id}}">
                                        <a href="javascript:void(0)" onclick="showEdit({{$item->id}})">修改</a>
                                    </span>
                                    <span id="edit_btn_{{$item->id}}" style="display:none">
                                        <button type="button" class="btn btn-success btn-sm" onclick="edit({{$item->id}})">提交</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="showEdit({{$item->id}})">取消</button>
                                    </span>
                                    @else
                                    ---
                                    @endif
                                </td>
                            </tr>


                        @endforeach
                        </tbody>
                    </table>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-4 text-center">
                            <small class="text-muted inline m-t-sm m-b-sm">当前显示 20-30 | 总数 100</small>
                        </div>
                        <div class="col-sm-4 text-right text-center-xs">
                            {{$dangyuans->links()}}
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

    var file = null; 
    $(function(){ 
        $("#upload").click(function(){ 
            var fileInput = $('#file_upload').get(0).files[0];
            if(!fileInput){
                alert("请选择导入文件。");
                return;
            }
            upload(); 
        }); 
    }); 

    var input = document.getElementById("file_upload"); 
     
    //文件域选择文件时, 执行readFile函数 
    input.addEventListener('change',readFile,false); 
     
    function readFile(){ 
        file = this.files[0]; 
    } 
    //上传文件 
    function upload(){ 
        var xhr = new XMLHttpRequest(); 
        var fd = new FormData(); 
        fd.append("file", file); 
        //监听事件 
        //xhr.upload.addEventListener("progress", uploadProgress, false); 
        //发送文件和表单自定义参数 
        xhr.open("POST", "{{ route('dangyuan.upload') }}",true); 
        xhr.setRequestHeader("X-CSRF-TOKEN", document.head.querySelector("[name=csrf-token]").content);
        xhr.send(fd); 
        xhr.onreadystatechange = function(){
            //若响应完成且请求成功
            if(xhr.readyState === 4 && xhr.status === 200){
                if (xhr.responseText == '0')
                    alert("导入成功");
                else
                    alert("导入失败");
            }
        }
    } 
     
    function uploadProgress(evt){ 
        if (evt.lengthComputable) {   
            //evt.loaded：文件上传的大小 evt.total：文件总的大小   
            var percentComplete = Math.round((evt.loaded) * 100 / evt.total); 
            //加载进度条，同时显示信息  
            $("#percent").html(percentComplete + '%') 
            $("#progressNumber").css("width",""+percentComplete+"px");  
        } 
    } 

    function showEdit(id){
        $('#name_'+id).toggle();
        $('#name_input_'+id).toggle();
        $('#sex_'+id).toggle();
        $('#sex_input_'+id).toggle();
        $('#in_time_'+id).toggle();
        $('#in_time_input_'+id).toggle();
        $('#manage_'+id).toggle();
        $('#edit_btn_'+id).toggle();
    }

    function edit(id){
        if ($('#name_input_'+id).val() == '')
            alert('请输入姓名。');
        if ($('#sex_input_'+id).val() == '')
            alert('请输入性别。');
        if ($('#in_time_input_'+id).val() == '')
            alert('请输入入党日期。');
        $.ajax({
            url: "/dangyuan/edit",
            method: "POST",
            data: {
                name:$('#name_input_'+id).val(),
                sex:$('#sex_input_'+id).val(),
                in_time:$('#in_time_input_'+id).val(),
                id:id
            },
            dataType: "json",
            success: function success(data) {
                if (data == 0) {
                    window.location.reload();
                }else if (data == 1){
                    alert('入党时间错误，请重新输入');
                }else if (data == 2){
                    alert('性别格式错误，请输入男或女');
                }
            }
        });
    }
</script>
@endsection
