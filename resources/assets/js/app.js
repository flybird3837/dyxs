require('./layer/layer');

$(document).ready(function() {

    /**
     * 删除资源
     */
    $('.deleteConfirm').on('click', function() {
        var $this = $(this);
        layer.confirm('确定删除？', {
            btn: ['确定','取消']
        }, function(){
            // layer.msg('的确很重要', {icon: 1});
            var target = $this.data('target');
            $('#' + target).submit();
        }, function(){
        });
    })
});