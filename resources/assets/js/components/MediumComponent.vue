<template>

    <div class="row w-100 h-100 m-0 p-0">

        <div class="col-2 border-right pt-4">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a v-if="showTypes.image" class="nav-link" v-bind:class="{'active': activeNav === 'image'}" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-image" role="tab"
                   aria-controls="v-pills-home" aria-selected="true">图片</a>
                <a v-if="showTypes.audio" class="nav-link" v-bind:class="{'active': activeNav === 'audio'}" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-music" role="tab"
                   aria-controls="v-pills-profile" aria-selected="false">音频</a>
                <a v-if="showTypes.video" class="nav-link" v-bind:class="{'active': activeNav === 'video'}" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-video" role="tab"
                   aria-controls="v-pills-messages" aria-selected="false">视频</a>
            </div>
        </div>

        <div class="col-10 p-0">

            <div class="tab-content top-tab-content" id="v-pills-tabContent">
                <!--图片页-->
                <div class="tab-pane fade top-tab-pane" v-bind:class="{'active show': activeNav === 'image'}" id="v-pills-image" role="tabpanel"
                     aria-labelledby="v-pills-home-tab">

                    <ul class="nav nav-tabs" id="imageTab" role="tablist">
                        <li class="nav-item" style="width: 20px;"></li>
                        <li class="nav-item">
                            <a class="nav-link active" id="image-list-tab" data-toggle="tab" href="#image-list"
                               role="tab"
                               aria-controls="image-list" aria-selected="true">图片库</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="image-upload-tab" data-toggle="tab" href="#image-upload" role="tab"
                               aria-controls="image-upload" aria-selected="false">上传图片</a>
                        </li>
                    </ul>

                    <div class="tab-content second-table-content" id="myTabContent">
                        <!--图片库页-->
                        <div class="tab-pane fade show active" id="image-list" role="tabpanel"
                             aria-labelledby="image-list-tab">
                            <div class="row m-0 h-100">
                                <div class="col-9 media-list">
                                    <div v-if="images && images.data.length" class="d-flex">
                                        <div @click="toggleFile(image.id, 'image')" v-for="image in images.data"
                                             class="image-item m-2"
                                             v-bind:class="{'selected': selectedImages.indexOf(image.id) !== -1}">
                                            <img class="border" v-bind:src="image.url">
                                            <div class="delete text-center">
                                                <div class="back"></div>
                                                <span class="text-danger">取消选择</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="empty-list">
                                        <span class="text-secondary">暂无内容，请先上传</span>
                                    </div>
                                </div>
                                <div class="col-3 border-left">
                                    <div v-if="focusedImage" class="image-info mt-2">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="">
                                                    <img style="width: 100%" :src="focusedImage.url">
                                                </div>

                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <span class="d-block">{{focusedImage.client_origin_name}}</span>
                                                    <span class="d-block">{{focusedImage.created_at}}</span>
                                                    <span class="d-block">{{(focusedImage.size / 1000 / 1000).toFixed(2)}} M</span>
                                                    <span class="d-block"><button
                                                            @click="deleteFile(focusedImage.id, 'image')" type="button"
                                                            class="btn btn-danger btn-sm">永久删除</button></span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div>
                                            <div class="form-group">
                                                <label>图片链接</label>
                                                <input readonly class="form-control" :value="focusedImage.url">

                                            </div>
                                            <div class="form-group">
                                                <label>磁盘</label>
                                                <input readonly class="form-control" :value="focusedImage.disk">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--上传图片页-->
                        <div class="tab-pane fade" id="image-upload" role="tabpanel" aria-labelledby="image-upload-tab">
                            <upload-file v-on:uploadSuccess="afterFileUploaded" type="image"
                                         :url="apiUrl"></upload-file>
                        </div>
                    </div>

                </div>

                <div class="tab-pane fade top-tab-pane" v-bind:class="{'active show': activeNav === 'audio'}" id="v-pills-music" role="tabpanel"
                     aria-labelledby="v-pills-profile-tab">
                    <ul class="nav nav-tabs" id="musicTab" role="tablist">
                        <li class="nav-item" style="width: 20px;"></li>
                        <li class="nav-item">
                            <a class="nav-link active" id="music-list-tab" data-toggle="tab" href="#music-list"
                               role="tab"
                               aria-controls="music-list" aria-selected="true">音频库</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="music-upload-tab" data-toggle="tab" href="#music-upload" role="tab"
                               aria-controls="music-upload" aria-selected="false">上传音频</a>
                        </li>
                    </ul>

                    <div class="tab-content second-table-content">
                        <!--音频库页-->
                        <div class="tab-pane fade show active" id="music-list" role="tabpanel"
                             aria-labelledby="music-list-tab">
                            <div class="row m-0 h-100">
                                <div class="col-9 media-list">
                                    <div v-if="audios && audios.data.length" class="d-flex">
                                        <div v-for="audio in audios.data" class="image-item m-2 mt-4"
                                             v-bind:class="{'selected': selectedAudios.indexOf(audio.id) !== -1}"
                                             @click="toggleFile(audio.id, 'audio')">
                                            <img class="border" src="/images/audio.jpg">
                                            <div class="delete text-center">
                                                <div class="back"></div>
                                                <span class="text-danger">取消选择</span>
                                            </div>
                                            <div class="mt-1 text-center w-100">{{audio.client_origin_name}}</div>
                                        </div>
                                    </div>
                                    <div v-else class="empty-list">
                                        <span class="text-secondary">暂无内容，请先上传</span>
                                    </div>
                                </div>
                                <div class="col-3 border-left">
                                    <div v-if="focusedAudio" class="audio-info mt-2">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="">
                                                    <img style="width: 100%" src="/images/audio.jpg">
                                                </div>

                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <span class="d-block">{{focusedAudio.client_origin_name}}</span>
                                                    <span class="d-block">{{focusedAudio.created_at}}</span>
                                                    <span class="d-block">{{(focusedAudio.size / 1000 / 1000).toFixed(2)}} M</span>
                                                    <span class="d-block"><button
                                                            @click="deleteFile(focusedAudio.id, 'audio')" type="button"
                                                            class="btn btn-danger btn-sm">永久删除</button></span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div>
                                            <div class="form-group">
                                                <label>音频链接</label>
                                                <input class="form-control" :value="focusedAudio.url">

                                            </div>
                                            <div class="form-group">
                                                <label>磁盘</label>
                                                <input class="form-control" :value="focusedAudio.disk">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--上传音频页-->
                        <div class="tab-pane fade" id="music-upload" role="tabpanel" aria-labelledby="music-upload-tab">
                            <upload-file v-on:uploadSuccess="afterFileUploaded" type="audio"
                                         :url="apiUrl"></upload-file>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade top-tab-pane" v-bind:class="{'active show': activeNav === 'video'}" id="v-pills-video" role="tabpanel"
                     aria-labelledby="v-pills-messages-tab">
                    <ul class="nav nav-tabs" id="videoTab" role="tablist">
                        <li class="nav-item" style="width: 20px;"></li>
                        <li class="nav-item">
                            <a class="nav-link active" id="video-list-tab" data-toggle="tab" href="#video-list"
                               role="tab"
                               aria-controls="video-list" aria-selected="true">视频库</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="video-upload-tab" data-toggle="tab" href="#video-upload" role="tab"
                               aria-controls="video-upload" aria-selected="false">上传视频</a>
                        </li>
                    </ul>

                    <div class="tab-content second-table-content">
                        <!--视频库页-->
                        <div class="tab-pane fade show active" id="video-list" role="tabpanel"
                             aria-labelledby="video-list-tab">
                            <div class="row m-0 h-100">
                                <div class="col-9 media-list">
                                    <div v-if="videos && videos.data.length" class="d-flex">
                                        <div v-for="video in videos.data" class="image-item m-2"
                                             @click="toggleFile(video.id, 'video')"
                                             v-bind:class="{'selected': selectedVideos.indexOf(video.id) !== -1}">
                                            <img class="border" src="/images/video.jpg">
                                            <div class="delete text-center">
                                                <div class="back"></div>
                                                <span class="text-danger">取消选择</span>
                                            </div>
                                            <div class="mt-1 text-center w-100">{{video.client_origin_name}}</div>
                                        </div>
                                    </div>
                                    <div v-else class="empty-list">
                                        <span class="text-secondary">暂无内容，请先上传</span>
                                    </div>
                                </div>
                                <div class="col-3 border-left">
                                    <div v-if="focusedVideo" class="video-info mt-2">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="">
                                                    <img class="w-100" src="/images/video.jpg">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <span class="d-block">{{focusedVideo.client_origin_name}}</span>
                                                    <span class="d-block">{{focusedVideo.created_at}}</span>
                                                    <span class="d-block">{{(focusedVideo.size / 1000 / 1000).toFixed(2)}} M</span>
                                                    <span class="d-block"><button
                                                            @click="deleteFile(focusedVideo.id, 'video')" type="button"
                                                            class="btn btn-danger btn-sm">永久删除</button></span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div>
                                            <div class="form-group">
                                                <label>视频连接</label>
                                                <input class="form-control" :value="focusedVideo.url">

                                            </div>
                                            <div class="form-group">
                                                <label>磁盘</label>
                                                <input class="form-control" :value="focusedVideo.disk">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--上传视频页-->
                        <div class="tab-pane fade" id="video-upload" role="tabpanel" aria-labelledby="video-upload-tab">
                            <upload-file v-on:uploadSuccess="afterFileUploaded" type="video"
                                         :url="apiUrl"></upload-file>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-bottom border-top pl-2 pr-2">

                <div class="left-info mr-4">
                    <span class="d-block">已选 {{selectedFilesList.length}} 个</span>
                    <span class="d-block"><a href="#" @click="clearSelectedFile">清空</a></span>
                </div>

                <div  class="selected-medium mr-4">
                    <div v-for="file in selectedFilesList" class="selected-media mr-1">
                        <template v-if="file.file_type === 'image'">
                            <img class="border" :src="file.url">
                        </template>
                        <template v-else-if="file.file_type === 'audio'">
                            <img class="border" src="/images/audio.jpg">
                        </template>
                        <template v-else-if="file.file_type === 'video'">
                            <img class="border" src="/images/video.jpg">
                        </template>
                    </div>
                </div>

                <div class="right-button float-right">
                    <button type="button" class="btn btn-info" @click="confirm">确定</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        data() {
            return {
                images: null,
                audios: null,
                videos: null,
                selectedFiles: [],
                selectedFilesList: [],
                selectedImages: [],
                selectedAudios: [],
                selectedVideos: [],
                focusedImage: null,
                focusedAudio: null,
                focusedVideo: null,
                apiUrl: '/files',
                showTypes: {
                    image: true,
                    audio: true,
                    video: true,
                },
                activeNav: 'image',
            }
        },

        props: ['type', 'field'],

        created() {
            // console.log('this.field', this.field);
            // console.log('this.type', this.type);

            if (this.type) {
                switch (this.type) {
                    case 'image':
                        this.showTypes.audio = false;
                        this.showTypes.video = false;
                        this.showTypes.image = true;
                        this.activeNav = this.type;
                        break;
                    case 'audio':
                        this.showTypes.image = false;
                        this.showTypes.video = false;
                        this.showTypes.audio = true;
                        this.activeNav = this.type;
                        break;
                    case 'video':
                        this.showTypes.image = false;
                        this.showTypes.audio = false;
                        this.showTypes.video = true;
                        this.activeNav = this.type;
                        break;
                    default:
                        this.showTypes.image = true;
                        this.showTypes.video = true;
                        this.showTypes.audio = true;
                        break;
                }
            }

            this.initData();
        },

        methods: {
            initData() {
                this.getFiles('image');
                this.getFiles('audio');
                this.getFiles('video');
            },

            getFiles(type) {
                let api = this.apiUrl + '?type=' + type;
                $.get(api).done(res => {
                    // console.log(this)
                    if (type === 'image') {
                        this.images = res;
                    }

                    if (type === 'audio') {
                        // console.log('audio', res)
                        this.audios = res;
                    }

                    if (type === 'video') {
                        this.videos = res;
                    }
                    // console.log(res)
                    this.updateSelectedFilesList();
                }).fail(err => {
                    console.error('request fail', err);
                });
            },

            toggleFile(file, type) {
                //console.log('file', file);
                //console.log('type', type);
                switch (type) {
                    case 'image':
                        this.focusedImage = this.images.data.find(item => item.id === file);
                        let selectedImageIndex = $.inArray(file, this.selectedImages);
                        if (selectedImageIndex === -1) {
                            this.selectedImages.push(file);
                            //this.selectedFiles.push(this.focusedImage);
                        } else {
                            this.selectedImages.splice(selectedImageIndex, 1);
                        }
                        break;
                    case 'audio':
                        this.focusedAudio = this.audios.data.find(item => item.id === file);
                        let selectedAudioIndex = $.inArray(file, this.selectedAudios);
                        if (selectedAudioIndex === -1) {
                            this.selectedAudios.push(file);
                            //this.selectedFiles.push(this.focusedAudio);
                        } else {
                            this.selectedAudios.splice(selectedAudioIndex, 1);
                        }
                        break;
                    case 'video':
                        this.focusedVideo = this.videos.data.find(item => item.id === file);
                        let selectedVideoIndex = $.inArray(file, this.selectedVideos);
                        if (selectedVideoIndex === -1) {
                            this.selectedVideos.push(file);
                            //this.selectedFiles.push(this.focusedVideo);
                        } else {
                            this.selectedVideos.splice(selectedVideoIndex, 1);
                        }
                        break;
                    default:
                        break;
                }

                let fileIndex = $.inArray(file, this.selectedFiles);

                if (fileIndex === -1) {
                    this.selectedFiles.push(file);
                } else {
                    this.selectedFiles.splice(fileIndex, 1);
                }

                this.updateSelectedFilesList();
                console.log(this.selectedFilesList);

            },

            updateSelectedFilesList() {
                this.selectedFilesList = [];

                this.selectedFiles.forEach(id => {
                   // console.log(this.images);
                    let image = this.images.data.find(image => image.id === id);
                    if (image) {
                        this.selectedFilesList.push(image);
                    }

                    let audio = this.audios.data.find(audio => audio.id === id);
                    if (audio) {
                        this.selectedFilesList.push(audio);
                    }

                    let video = this.videos.data.find(video => video.id === id);
                    if (video) {
                        this.selectedFilesList.push(video);
                    }
                })

            },

            deleteFile(id, type) {
                layer.confirm('文件将永久删除，确定执行？', {
                        btn: ['确定', '取消']
                    },
                    () => {
                        $.ajax({
                            url: this.apiUrl + '/' + id,
                            method: 'DELETE',
                        }).done(res => {
                            // console.log('deleted file');
                            layer.alert('文件删除成功！');
                            this.afterFileDelete(type);

                        }).fail(err => {
                            // console.log('file delete fail')
                            layer.alert('删除失败！');
                        });
                    },
                    () => {
                        // layer.alert('点击取消')
                    }
                );

            },

            afterFileUploaded(res, type) {
                // console.log(type, res);
                this.getFiles(type);
            },

            afterFileDelete(type) {
                this.getFiles(type);
                // this.updateSelectedFilesList();
            },

            confirm() {
                // console.log('click confirm')
                let windowName = window.name;
                if (windowName) {
                    let index = parent.layer.getFrameIndex(window.name);
                    // console.log('index', index)
                    if (typeof parent.choosedFiles === 'function') {
                        parent.choosedFiles(this.selectedFilesList, this.type, this.field);
                    }
                    parent.layer.close(index);
                } else {
                    console.log('window name is null');
                }
            },

            clearSelectedFile() {
                this.selectedImages = [];
                this.selectedAudios = [];
                this.selectedVideos = [];
                this.selectedFiles = [];
                this.selectedFilesList = [];
            }
        },
    }
</script>