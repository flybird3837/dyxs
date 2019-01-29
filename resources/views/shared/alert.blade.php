@if(session('danger'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-ban-circle"></i><strong>出错啦!</strong>
        {{ session('danger') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-ok-sign"></i><strong>成功!</strong> {{ session('success') }}
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-info-sign"></i><strong>提醒!</strong> {{ session('info') }}
    </div>
@endif