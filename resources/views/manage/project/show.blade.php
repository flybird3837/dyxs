@extends('layouts.main')
@section('title', '党组织详情')
@section('content-header', '党组织详情')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> 控制台</a></li>
                <li><a href="{{route('projects.index')}}"><i class="fa fa-list-ul"></i> 党组织管理</a></li>
                <li class="active">党组织详情</li>
            </ul>
            @include('shared.alert')
        </div>

        <div class="col-lg-12">
            <form class="form-horizontal" data-validate="parsley" style="margin-bottom: 60px;">
                <section class="panel panel-default">
                    <header class="panel-heading"><strong>党组织详情</strong></header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">党组织名称</label>
                            <div class="col-sm-9">
                                <input readonly id="name" name="name" type="text" class="form-control"
                                       value="{{$project->name}}">
                            </div>
                        </div>
                        @if($project->project_administrator)
                            <div class="line line-dashed line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">维护员账号</label>
                                <div class="col-sm-9">
                                    <input readonly name="project_manage_phone" type="text" class="form-control"
                                           placeholder="小程序secret" value="{{$project->project_administrator->phone}}">
                                </div>
                            </div>
                        @endif
                        @if($project->project_maintainer)
                            <div class="line line-dashed line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">管理员账号</label>
                                <div class="col-sm-9">
                                    <input readonly name="project_manage_phone" type="text" class="form-control"
                                           placeholder="小程序secret" value="{{$project->project_maintainer->phone}}">
                                </div>
                            </div>
                        @endif
                    </div>
                    <footer class="panel-footer text-right bg-light lter">
                        <button type="button" onclick="javascript:window.history.back();" class="btn btn-default btn-s-xs">返回</button>
                    </footer>
                </section>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.input-group-addon').on('click', function () {
            var icon = $(this).children('i'), $this = $(this);
            if (icon.hasClass('fa-eye')) {
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
                $this.siblings('input').attr('type', 'text');
            } else {
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
                $this.siblings('input').attr('type', 'password');
            }
        })
    </script>
@endsection