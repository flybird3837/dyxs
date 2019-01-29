<template>
    <div class="upload-file w-100 h-100">
        <div class="upload-tip">
            <span>将文件拖入此处来上传</span>
            <span>或</span>
            <button type="button" class="btn btn-secondary" @click="chooseFile">选择文件</button>

            <input type="file" v-bind:accept="accepts" multiple style="display:none" @change="inputChange">
        </div>
    </div>
</template>

<script>
    // window.layer.alert('hello');
    const legalTypes = ['image', 'audio', 'video'];

    const accepts = {
        image: ['image/png', 'image/jpeg', 'image/gif'],
        audio: ['audio/mpeg', 'audio/ogg', 'audio/x-m4a', 'audio/mp3'],
        video: ['video/x-flv', 'video/x-m4v', 'video/mp4', 'video/x-ms-wmv']
    };

    export default {

        data() {
            return {
                dropbox: null,
                uploadType: null,
                accepts: null,
            }
        },

        props: ['type', 'url', 'selectedFiles'],

        mounted() {
            // console.log(this.url)
            if (typeof this.type === 'string' && ($.inArray(this.type, legalTypes) !== -1)) {
                // console.log('当前上传' + this.type);
                this.uploadType = this.type;
            }


            if (!this.uploadType) {
                console.error('上传错误，上传文件类型未知');
            }

            this.accepts = accepts[this.type].join(',');
            // console.log(this.accepts)

            let dropbox = this.$el;
            this.dropbox = dropbox;
            dropbox.addEventListener("dragenter", this.hello, false);
            dropbox.addEventListener("dragover", this.dragover, false);
            dropbox.addEventListener("drop", this.drop, false);
        },

        created() {
            // layer.msg('上传完成', {icon: 2});
            console.log('created' + this.type)
        },

        methods: {
            chooseFile(e) {
                let fileElem = e.target.nextElementSibling;
                if (fileElem) {
                    fileElem.click();
                }
            },

            inputChange(e) {
              // console.log('inputChange');
              if (e.target.files) {
                  this.handleFiles(e.target.files);
              }
            },

            dragenter(e) {
                console.log('dragenter');
                e.stopPropagation();
                e.preventDefault();
            },

            dragover(e) {
                e.stopPropagation();
                e.preventDefault();
            },

            drop(e) {
                e.stopPropagation();
                e.preventDefault();
                let dt = e.dataTransfer;
                let files = dt.files;
                // console.log(files);
                this.handleFiles(files);
            },
            handleFiles(files) {
                if (!this.verifyFiles(files)) {
                    return false;
                }

                let loading = layer.msg('文件上传中...', {
                    time: 0,
                    icon: 16,
                    shade: 0.1
                });

                let formData = new FormData();
                for (let i=0; i<files.length; i++) {
                    formData.append('files[]', files[i]);
                }

                formData.append('type', this.type);

                $.ajax({
                    url: this.url,
                    type: 'POST',
                    data: formData,
                    async: true,
                    processData: false,
                    contentType: false,
                }).done(res => {
                    layer.close(loading);
                    layer.msg('上传完成', {icon: 1});
                    this.$emit('uploadSuccess', res, this.type);

                }).fail(err => {
                    layer.close(loading);
                    layer.msg('上传失败', {icon: 2});
                    this.$emit('uploadFail', err);
                });
            },

            verifyFiles(files) {
                let mineTypes = accepts[this.type];
                let file;
                for (let i=0; i<files.length; i++) {
                    file = files[i];
                    // console.log('file.type', file.type);
                    // return ;
                    if ($.inArray(file.type, mineTypes) === -1 ) {
                        layer.msg('【' + file.name + '】类型错误，请上传正确的文件', {icon: 2});
                        return false;
                    }
                }
                return true;
            }
        },
    }
</script>
