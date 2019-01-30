@extends('layouts.main')

@section('title', '宣传片管理')

@section('content-header', '宣传片管理')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <!-- .breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li class="active"><i class="fa fa-cogs icon"></i> 宣传片管理</li>
            </ul>
            <!-- / .breadcrumb -->
        </div>
        <div class="col-lg-12">
            @include('shared.alert')
            <section class="panel panel-default">
                <header class="panel-heading"> 宣传片列表</header>
                <div class="row text-sm wrapper">
                    <div class="col-sm-12">
                        <span class="pull-right">
                            <form class="form-inline" role="form" onsubmit="return false"  id="upload_form">
                            <div class="form-group">
                                <label id="up_caption"></label>
                                <div class="progress" style="width:300px;">
                                  <div id="progress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                            <button id="upload" class="btn btn-info btn-sm fa fa-upload icon">上传</button>
                            <button id="select" class="btn btn-info btn-sm fa fa-upload icon">选择</button>
                            </form>
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light text-sm">
                        <thead>
                        <tr>
                            <th>视频</th>
                            <th>主题</th>
                            <th>简介</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($xuanchuans as $xuanchuan)
                            <tr>
                                <td width="15%"><a href="{{$upload_domain}}/{{$xuanchuan->video}}" target="_blank"><img src="{{$upload_domain}}/{{$xuanchuan->video}}?vframe/jpg/offset/1" style="width:100px;height:auto"/></a></td>
                                <td width="35%">{{$xuanchuan->name}}</td>
                                <td width="35%">{{$xuanchuan->intro}}</td>
                                <td width="15%">
                                    @if(auth()->user()->hasRole('project_manage'))
                                    <a href="{{ route('xuanchuans.edit', ['id' => $xuanchuan->id]) }}">编辑</a>
                                    <a href="{{ route('xuanchuans.edit', ['id' => $xuanchuan->id]) }}">删除</a>
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
                            {{$xuanchuans->links()}}
                        </div>
                    </div>
                </footer>
            </section>
        </div>

    </div>
@endsection
@section('scripts')
<script src="https://unpkg.com/qiniu-js@2.5.3/dist/qiniu.min.js"></script>
<script src="{{ asset('js/plupload/plupload.full.min.js') }}"></script>
<script>
    var token = '{{$upload_token}}';
    var domain = '{{$upload_domain}}';
    var config = {
      useCdnDomain: true,
      disableStatisticsReport: false,
      retryCount: 6,
      region: qiniu.region.z2
    };
    var putExtra = {
      fname: "",
      params: {},
      mimeType: null
    };

  qiniu.getUploadUrl(config, token).then(function(res){
    var uploadUrl = res;
    var board = {};
    var indexCount = 0;
    var resume = false;
    var chunk_size;
    var blockSize;
    var uploader = new plupload.Uploader({
      runtimes: "html5,flash,silverlight,html4",
      url: uploadUrl,
      browse_button: "select", // 触发文件选择对话框的按钮，为那个元素id
      flash_swf_url: "./js/plupload/Moxie.swf", // swf文件，当需要使用swf方式进行上传时需要配置该参数
      silverlight_xap_url: "./js/plupload/Moxie.xap",
      chunk_size: 4 * 1024 * 1024,
      max_retries: 3,
      multi_selection: false,
      multipart_params: {
        // token从服务端获取，没有token无法上传
        token: token
      },
      filters: { 
        //max_file_size: '10mb', //最大上传文件大小（格式100b, 10kb, 10mb, 1gb） 
        mime_types: [//允许文件上传类型 
            {title: "files", extensions: "mpg,m4v,mp4,flv,3gp,mov,avi,rmvb,mkv,wmv"} 
        ] 
      },
      init: {
        PostInit: function() {
          console.log("upload init");
        },
        FilesAdded: function(up, files) {
          resume = false;
          //$("#box button").css("backgroundColor", "#aaaaaa");
          chunk_size = uploader.getOption("chunk_size");
          var id = files[0].id;
          $('#up_caption').html(files[0].name);
          $("#upload").attr("disabled", false);
          $('#upload').on("click", function() {
                uploader.start();
          });
        },
        FileUploaded: function(up, file, info) {
          console.log(info);
        },
        UploadComplete: function(up, files) {
          // Called when all files are either uploaded or failed
          console.log("[完成]");
        },
        Error: function(up, err) {
          console.log(err.response);
        }
      }
    });
    uploader.init();
    uploader.bind('Error',function(){
      console.log(1234)
    })
    uploader.bind("BeforeUpload", function(uploader, file) {
      $("#select").attr("disabled", "disabled");
      $("#upload").attr("disabled", "disabled");
      $('#up_caption').html(file.name+'上传中...');
      dir = '{{$upload_dir}}';
      key = dir + file.name;
      putExtra.params["x:name"] = key.split(".")[0];
      var id = file.id;
      chunk_size = uploader.getOption("chunk_size");
      var directUpload = function() {
        var multipart_params_obj = {};
        multipart_params_obj.token = token;
        // filterParams 返回符合自定义变量格式的数组，每个值为也为一个数组，包含变量名及变量值
        var customVarList = qiniu.filterParams(putExtra.params);
        for (var i = 0; i < customVarList.length; i++) {
          var k = customVarList[i];
          multipart_params_obj[k[0]] = k[1];
        }
        multipart_params_obj.key = key;
        uploader.setOption({
          url: uploadUrl,
          multipart: true,
          multipart_params: multipart_params_obj
        });
      };

      var resumeUpload = function() {
        blockSize = chunk_size;
        initFileInfo(file);
        if(blockSize === 0){
          mkFileRequest(file)
          uploader.stop()
          return
        }
        resume = true;
        var multipart_params_obj = {};
        // 计算已上传的chunk数量
        var index = Math.floor(file.loaded / chunk_size);

        var headers = qiniu.getHeadersForChunkUpload(token)
        uploader.setOption({
          url: uploadUrl + "/mkblk/" + blockSize,
          multipart: false,
          required_features: "chunks",
          headers: {
            Authorization: "UpToken " + token
          },
          multipart_params: multipart_params_obj
        });
      };

      // 判断是否采取分片上传
      if (
        (uploader.runtime === "html5" || uploader.runtime === "flash") &&
        chunk_size
      ) {
        if (file.size < chunk_size) {
          directUpload();
        } else {
          resumeUpload();
        }
      } else {
        console.log(
          "directUpload because file.size < chunk_size || is_android_weixin_or_qq()"
        );
        directUpload();
      }
    });

    uploader.bind("ChunkUploaded", function(up, file, info) {
      var res = JSON.parse(info.response);
      var leftSize = info.total - info.offset;
      var chunk_size = uploader.getOption && uploader.getOption("chunk_size");
      if (leftSize < chunk_size) {
        up.setOption({
          url: uploadUrl + "/mkblk/" + leftSize
        });
      }
      up.setOption({
        headers: {
          Authorization: "UpToken " + token
        }
      });
      // 更新本地存储状态
      var localFileInfo = JSON.parse(localStorage.getItem(file.name))|| [];
      localFileInfo[indexCount] = {
        ctx: res.ctx,
        time: new Date().getTime(),
        offset: info.offset,
        percent: file.percent
      };
      indexCount++;
      localStorage.setItem(file.name, JSON.stringify(localFileInfo));
    });

    // 每个事件监听函数都会传入一些很有用的参数，
    // 我们可以利用这些参数提供的信息来做比如更新UI，提示上传进度等操作
    uploader.bind("UploadProgress", function(uploader, file) {
      var id = file.id;
      // 更新进度条进度信息;
      var fileUploaded = file.loaded || 0;
      var percent = file.percent + "%";
      $('#progress').css('width', percent);
      $('#progress').html(percent);
      console.log(percent);
    });

    uploader.bind("FileUploaded", function(uploader, file, info) {
      var id = file.id;
      if (resume) {
        mkFileRequest(file)
      } else {
        uploadFinish(JSON.parse(info.response), file.name,board[id]);
      }
    });

    function updateChunkProgress(file, board, chunk_size, count) {

      var index = Math.ceil(file.loaded / chunk_size);
      var leftSize = file.loaded - chunk_size * (index - 1);
      if (index == count) {
        chunk_size = file.size - chunk_size * (index - 1);
      }

      var dom = $(board)
        .find(".fragment-group li")
        .eq(index - 1)
        .find("#childBarColor");
      dom.css(
        "width",
        leftSize / chunk_size *100 + "%"
      );
    }

    function uploadFinish(res, name, board) {
        $("#select").attr("disabled", false);
        $("#upload").attr("disabled", false);
        $('#up_caption').html(name+'上传完成');
        localStorage.removeItem(name)
        if (res.key && res.key.match(/\.(jpg|jpeg|png|gif)$/)) {
        //imageDeal(board, res.key, domain);
        }
    }

    function initFileInfo(file) {
      var localFileInfo = JSON.parse(localStorage.getItem(file.name))|| [];
      indexCount = 0;
      var length = localFileInfo.length
      if (length) {
        var clearStatus = false
        for (var i = 0; i < localFileInfo.length; i++) {
            indexCount++
          if (isExpired(localFileInfo[i].time)) {
            clearStatus = true
            localStorage.removeItem(file.name);
            break;
          }
        }
        if(clearStatus){
          indexCount = 0;
          return
        }
        file.loaded = localFileInfo[length - 1].offset;
        var leftSize = file.size - file.loaded;
        if(leftSize < chunk_size){
          blockSize = leftSize
        }
        file.percent = localFileInfo[length - 1].percent;
        return
      }else{
        indexCount = 0
      }
    }

    function mkFileRequest(file){
      // 调用sdk的url构建函数
      var requestUrl = qiniu.createMkFileUrl(
        uploadUrl,
        file.size,
        key,
        putExtra
      );
      var ctx = []
      var id = file.id
      var local = JSON.parse(localStorage.getItem(file.name))
      for(var i =0;i<local.length;i++){
        ctx.push(local[i].ctx)
      }
      // 设置上传的header信息
      var headers = qiniu.getHeadersForMkFile(token)
      $.ajax({url: requestUrl, type: "POST",  headers: headers, data: ctx.join(","), success: function(res){
        uploadFinish(res, file.name,board[id]);
      }})
    }

    function isExpired(time){
      let expireAt = time + 3600 * 24* 1000;
      return new Date().getTime() > expireAt;
    }
  });

</script>
@endsection

