/*

附表一：全局完成引用的方法
+----+---------------------+------------------------------------------------------------------------------------+
| id | title               | content                                                                            |
+----+---------------------+------------------------------------------------------------------------------------+
|  1 | form_ajax_submit()  | ajax提交表单
|  2 | page_turning()      | 实现按钮点击后无刷新获取内容
|  3 | js_ajax_get()       | 实现按钮点击后执行业务逻辑并更新内容
|  4 | layui_form_effect() | layui表单元素需引入，无刷新获取内容时调用以更新渲染
|  5 | article_add_onload()| 文章编辑相关，全局引用条件：存在百度编辑器id
|  6 | ueditor_onload()    | 百度编辑器相关，全局引用条件：存在百度编辑器id
|  7 | whole_param()       | ajax 更新全局变量
+----+---------------------+------------------------------------------------------------------------------------+

附表二：改良消息提醒
+----+---------------------+------------------------------------------------------------------------------------+
| id | title               | content                                                                            |
+----+---------------------+------------------------------------------------------------------------------------+
|  1 | ok_alert()          | 成功
|  2 | fail_alert()        | 失败
|  3 | msg_alert()         | 普通消息
+----+---------------------+------------------------------------------------------------------------------------+

附表三：其他方法
+----+---------------------+------------------------------------------------------------------------------------+
| id | title               | content                                                                            |
+----+---------------------+------------------------------------------------------------------------------------+
|  1 | check_validate()    | 表单提交数据验证，在form_ajax_submit()方法中引入
|  2 | checkAll_rules()    | 原生复选框的全选反选，根据实际情况引入
|  3 | datadel_list()      | 删除多个记录
|  4 | datadel_an()        | 删除单个
|  5 | login_effect()      | 登录页效果
|  6 | close_form()        | 登录页关闭登录选项卡
|  7 | menu_effect()       | 左侧菜单
|  8 | update_paging()     | 动态更新内容，在全局引用的三个方法中有引用此方法
|  9 | layer_show()        | 弹出层
| 10 | selecttime()        | 时间插件
| 11 | close_tab()         | 关闭选项卡
| 11 | submit_draft()      | 提交草稿，文章编辑模块根据实际情况调用
+----+---------------------+------------------------------------------------------------------------------------+

附表四：表单验证规则
+----+---------------------+------------------------------------------------------------------------------------+
| id | title               | content                                                                            |
+----+---------------------+------------------------------------------------------------------------------------+
|  1 | .js-chs             | 中文
|  2 | .js-alphadash       | 字母数字下划线中划线
|  3 | .js-mobile          | 手机
|  4 | .js-email           | 邮箱
|  5 | .js-number          | 数字 [max, min]
|  6 | .js-confirm         | 两次输入是否一致
+----+---------------------+------------------------------------------------------------------------------------+

附表五：
+----+---------------------+------------------------------------------------------------------------------------+
| id | title               | content                                                                            |
+----+---------------------+------------------------------------------------------------------------------------+
|  1 | .select-form        | 列表页搜索表单的类名，引用page_turning()，已全局引用
|  2 | .select-form-submit | 列表页搜索表单的提交按钮，引用page_turning()，已全局引用
|  3 | .page-container     | 刷新内容的盒子，类名源自hui框架
|  4 | .js-ajax-get        | 无刷新执行逻辑（如用户启用禁用）的按钮类名，引用js_ajax_get()，已全局引用
|  5 | .js-ajax-submit     | 添加在ajax表单的提交按钮
|  6 | .js-ajax-form       | 用户ajax提交的表单
|  7 | paging_url          | 变量。内容请求链接，下方统一命名，前提：后端请求方法为列表页方法_paging()
|  8 | delete_url          | 变量。删除时执行的链接，下方统一命名，前提：后端删除方法del
|  9 | submit_lock         | 变量。表单提交的锁
| 10 | query_str           | 已执行搜索排序的表单数据及分页数据，get请求query字符串形式   删除、启用禁用等用到
+----+---------------------+------------------------------------------------------------------------------------+

备注：
a.以下js方法基于引入jquery-1.9.1, layui-2.2.5, layer-3.1.1框架，相关逻辑请查阅对应框架的使用文档或JavaScript代码;

 */

// 变量paging_url和delete_url统一命名
var paging_url = 'paging.html';
var delete_url = 'delete.html';

var submit_lock = true;

$(function(){
    $('body').show();

    // ajax提交表单
    form_ajax_submit();

    // 实现按钮点击后无刷新获取内容
    page_turning();

    // 实现按钮点击后执行逻辑并更新内容
    js_ajax_get();

    // layui表单元素需引入
    layui_form_effect();

    // 文章编辑板块相关（条件：有百度编辑器id）
    if ($('#ueditor').length > 0) {

        whole_param('article');
        article_add_onload();
        ueditor_onload();
    }

    // esc键按下清理弹窗
    $(document).keydown(function(event) {
        // 退出=27 回车=13
        if (event.keyCode == 27)
            layer.closeAll();
    });
    // 鼠标悬停状态
    var hover_btn = false; // 鼠标悬停状态
    $('.js-ajax-submit').mouseover(function() { hover_btn = true; }).mouseout(function() { hover_btn = false; })
    $('button').mouseover(function() { hover_btn = true; }).mouseout(function() { hover_btn = false; })

    // 单选框样式-hui
    /*$('.skin-minimal input').iCheck({
        checkboxClass: 'icheckbox-blue',
        radioClass: 'iradio-blue',
        increaseArea: '20%'
    });*/

    /**
     * +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * ++++++++++++++++++++++++++++++++++++++++ 表单数据合法性验证 +++++++++++++++++++++++++++++++++++++++++++++++++++++
     * +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     */

    // 输入限制 -- 只能中文
    $('.js-chs').on('blur', function(){
        if (!hover_btn && this.value != '' && this.value.match(/[^\u4E00-\u9FA5]/g)) {
            layer.tips('只能输入中文', this,{
                tips: [3, '#e74c3c'], // 1上 2右 3下 4左
                time: 4000,
            });
        }
    })
    // 输入限制 -- 字母数字下划线中划线
    $('.js-alphadash').on('blur', function(){
        if (!hover_btn && this.value != '' && !this.value.match(/^[a-zA-Z0-9_-]{1,}$/g)) {
            layer.tips('只能输入字母数字下划线中划线', this,{
                tips: [3, '#e74c3c'],
                time: 1500,
            });
        }
    })
    // 输入限制 -- 手机
    $('.js-mobile').on('blur', function(){
        if (!hover_btn && this.value != '' && !this.value.match(/(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/g)) {
            layer.tips('手机号码格式有误', this,{
                tips: [3, '#e74c3c'],
                time: 1500,
            });
        }
    })
    // 输入限制 -- 邮箱
    $('.js-email').on('blur', function(){
        if (!hover_btn && this.value != '' && !this.value.match(/^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/g)) {
            layer.tips('邮箱格式有误', this,{
                tips: [3, '#e74c3c'],
                time: 1500,
            });
        }
    })
    // 输入限制 -- 数字
    $('.js-number').on('blur', function(){
        if (!hover_btn && this.value != '') {
            if (parseFloat(this.value).toString() == 'NaN') {
                layer.tips('只能输入数字', this,{
                    tips: [3, '#e74c3c'],
                    time: 1500,
                });
                return false;
            }
            var max = $(this).attr("max");
            if (typeof(max) !="undefined" && parseFloat(max).toString() != 'NaN' && parseInt(this.value) > parseInt(max)) {
                layer.tips('输入数字最大值为' + max, this,{
                    tips: [3, '#e74c3c'],
                    time: 1500,
                });
                return false;
            }
            var min = $(this).attr("min");
            if (typeof(min) !="undefined" && parseFloat(min).toString() != 'NaN' && parseInt(this.value) < parseInt(min)) {
                layer.tips('输入数字最小值为' + min, this,{
                    tips: [3, '#e74c3c'],
                    time: 1500,
                });
                return false;
            }
        }
    })
    // 输入限制 -- 两次输入是否一致
    $('.js-confirm').on('blur',function(){
        var attr = $(this).attr('confirm');
        if (!hover_btn && typeof(attr) != 'undefined' && $('[name='+attr+']').val() != this.value) {
            layer.tips('两次输入内容不一致', this,{
                tips: [3, '#e74c3c'],
                time: 1500,
            });
            return false;
        }
    })
});

/**
 * ajax提交表单
 * @type {Boolean}
 */
function form_ajax_submit(){

    $(document).on('click', '.js-ajax-submit', function(){
        // 验证数据
        if (!check_validate())
            return false;

        // 上锁
        if (!submit_lock && !$(this).hasClass('js-test'))
            return false;
        else
            submit_lock = false;

        var page_box = $(parent.document).find('.page-container'); // 分页内容盒子
        var renew = $(parent.document).find('.renew'); // 点击可以触发数据更新
        var inLayer = page_box.length > 0 && top.location != self.location; // 是否弹出层
        var add_type = $(this).hasClass('js-add'); // 是否添加操作
        var form = $('.js-ajax-form');
        var action = form.attr('action');
        $.ajax({
            type: 'POST',
            url:  action,
            data: form.serialize(),
            dataType:'json',
            success:function(data){
                if (data.code == 0) {
                    submit_lock = true;
                    $('img.captcha').click().prev('input').val('');
                    fail_alert(data.msg); // 失败

                } else {
                    ok_alert(data.msg); // 成功
                    if (inLayer) {
                        if (renew.length > 0) {
                            renew.click(); // 点击触发 update_paging
                        } else {
                            parent.update_paging(parent.paging_url, page_box);
                        }
                        setTimeout(function(){
                            /* 跳转到底部的处理 */
                            var phref = parent.location.href;
                            if (add_type &&  phref.substring(phref.length-7) != '#footer')
                            {
                                parent.location.href = phref + '#footer';
                            }
                            else if (add_type) {
                                parent.location.href = phref.substr(0, phref.length-7) + '#top';
                                parent.location.href = phref;
                            }
                            /* 跳转到底部的处理 end */
                            parent.layer.closeAll();
                        }, 1200)
                    }
                    else if (data.url) {
                        setTimeout(function(){
                            if (add_type){
                                window.location.href= data.url + '#footer'
                            }
                            else {
                                window.location.href = data.url;
                            }
                        }, 1200);// 延迟1秒跳转到返回链接
                    }
                }
            }
        })
        return false;
    })

    // 编辑文章时添加文章标签
    $(document).on('click', '.js-ajax-addtag', function(){
        // 验证数据
        if (!check_validate())
            return false;

        // 上锁
        if (!submit_lock && !$(this).hasClass('js-test'))
            return false;
        else
            submit_lock = false;

        var form = $('.js-ajax-form');
        var action = form.attr('action');

        $.ajax({
            type: 'POST',
            url:  action,
            data: form.serialize(),
            dataType:'json',
            success:function(data){
                if (data.code == 0) {
                    submit_lock = true;
                    fail_alert(data.msg); // 失败
                } else {
                    $(window.parent.document).find('.tagthree').append('<input type="checkbox" name="article_tag[]" title="'+data.data.tag_name+'" value="'+data.data.id+'">');
                    window.parent.layui_form_effect();
                    ok_alert(data.msg); // 成功
                    setTimeout(function(){
                        parent.layer.closeAll();
                    }, 1200)
                }
            }
        })
        return false;
    })
}
/**
 * ajax提交草稿
 * @type {Boolean}
 */
function submit_draft() {

    // 验证数据
    $('[name="draft"]').val(1);
    $('.js-require').addClass('js-require-bak').removeClass('js-require');
    if (!check_validate())
        return false;

    var form = $('.js-ajax-form');
    var action = form.attr('action');
    $.ajax({
        type: 'POST',
        url:  action,
        data: form.serialize(),
        dataType:'json',
        success:function(data){
            if (data.code == 0) {
                msg_alert(data.msg, 'shenhe-weitongguo'); // 失败
            } else {
                if ($('[name="auto_hold"]').val() != 1) {
                    msg_alert(data.msg, 'shenhe-tongguo'); // 成功
                }
                !$('[name=id]').val() && $('[name=id]').val(data.data.id) && form.attr('action', form.attr('action').replace('add', 'edit'));
            }
            $('[name="draft"]').val('');
            $('[name="auto_hold"]').val('');
            $('[name="permission"]').val('2');
            $('.js-require-bak').addClass('js-require').removeClass('js-require-bak');
            submit_lock = true;
        }
    })
}
// 提交（作废）
function onformsubmit(){
    if (!check_validate())
        return false;

    var form = $('.js-ajax-form');
    var action = form.attr('action');
    $.ajax({
        type: 'POST',
        url:  action,
        data: form.serialize(),
        dataType:'json',
        success:function(data){
            if (data.code == 0) {
                $('img.captcha').click();
                fail_alert(data.msg);
            } else {
                ok_alert(data.msg);
            }
        }
    })
    return false;
}

// *********************************************** 失败弹窗 ***********************************************
function fail_alert(msg){
    layer.closeAll();
    layer.alert(msg, {
        skin: 'layui-layer-lan'
        ,closeBtn: 0
        // 动画类型：4旋转弹出 6抖动 1上而下 2下而上 3左而右
        ,anim: 6
        ,time:2000
    }
    // 不能加回调，否则确定点击不了
    )
}
// *********************************************** 成功弹窗 ***********************************************
function ok_alert(msg){
    layer.closeAll();
    layer.alert(msg, {
        skin: 'layui-layer-molv'
        ,closeBtn: 0
        ,anim: 4
        ,time:1500
    })
}
// *********************************************** 消息提醒 ***********************************************
function msg_alert(msg, icon = ''){
    var docu_height = document.documentElement.clientHeight * 0.9 - 25; //可见高度*0.9-消息弹框高/2
    if (icon != '')
        msg = '<i class="Hui-iconfont Hui-iconfont-' + icon + '"></i> ' + msg;
    layer.closeAll();
    layer.msg(msg,{
        anim:1,
        type: 1,
        offset: [docu_height]
    });
}

/**
 * 提交表单的验证
 * @return {bool} 表单是否可以提交
 * @ 释义： layer.tips参数 1上 2右 3下 4左
 * @ 释义： return flag = false 结束当前循环，同时return值为false以终止表单提交
 */
function check_validate() {
    var flag = true;
    $('.js-ajax-form').find('select, input, textarea').each(function(index, element){
        // 验证必填
        if ($(element).hasClass('js-require') && !$(element).val()) {
            layer.tips('此项必填！', element,{
                tips: [3, '#e74c3c'],
                time: 1000
            });
            $(element).focus();
            return flag = false;;
        }
        // 验证中文
        else if ($(element).hasClass('js-chs') && this.value.match(/[^\u4E00-\u9FA5]/g)) {
            layer.tips('只能输入中文', element,{
                tips: [3, '#e74c3c'],
                time: 1000
            });
            return flag = false;
        }
        // 验证字母数字下划线中划线
        else if ($(element).hasClass('js-alphadash') && this.value.match(/[^a-zA-Z0-9_-]/g)) {
            layer.tips('只能输入字母数字下划线中划线', element,{
                tips: [3, '#e74c3c'],
                time: 1000
            });
            return flag = false;
        }
        // 验证手机
        else if ($(element).hasClass('js-mobile') && !this.value.match(/(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/g)) {
            layer.tips('手机号码格式有误', element,{
                tips: [3, '#e74c3c'],
                time: 1000
            });
            return flag = false;
        }
        // 验证邮箱
        else if ($(element).hasClass('js-email') && !this.value.match(/^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/g)) {
            layer.tips('邮箱格式有误', element,{
                tips: [3, '#e74c3c'],
                time: 1000
            });
            return flag = false;
        }
        // 验证数字
        else if ($(element).hasClass('js-number') && (element.value != '')) {
            if (parseFloat(element.value).toString() == 'NaN') {
                layer.tips('只能输入数字', element,{
                    tips: [3, '#e74c3c'],
                    time: 1500,
                });
                return flag = false;
            }
            var max = $(element).attr("max");
            if (typeof(max) !="undefined" && parseFloat(max).toString() != 'NaN' && parseInt(element.value) > parseInt(max)) {
                layer.tips('输入数字最大值为' + max, element,{
                    tips: [3, '#e74c3c'],
                    time: 1500,
                });
                return flag = false;
            }
            var min = $(element).attr("min");
            if (typeof(min) !="undefined" && parseFloat(min).toString() != 'NaN' && parseInt(element.value) < parseInt(min)) {
                layer.tips('输入数字最小值为' + min, element,{
                    tips: [3, '#e74c3c'],
                    time: 1500,
                });
                return flag = false;
            }
        }
        // 验证两次输入是否一致
        else if ($(element).hasClass('js-confirm')) {
            var confirm = $(element).attr('confirm');
            if (typeof(confirm) != 'undefined' && $('[name='+confirm+']').val() != element.value) {
                layer.tips('两次输入内容不一致', element,{
                    tips: [3, '#e74c3c'],
                    time: 1500,
                });
                return flag = false; // 结束循环
            }
        }
    })

    return flag;
}
// 全选 反选
function checkAll_rules() {
    $(document).on('click', '[type=checkbox]:eq(0)', function(){
        if ($(this).is(':checked')) {
            $('[type=checkbox]').prop('checked', true);
            $('[disabled]').prop('checked', false);
        } else {
            $('[type=checkbox]').prop('checked', false);
        }
    })
}

/**
 * 删除数据（列表）
 * @paran {obj} obj 全部删除按钮dom元素，用于定位删除确认框位置
 * @return {null}
 */
function datadel_list(obj){
    var offsetY = obj.offsetTop - $(window).scrollTop() + 38; // 元素距文档顶端-网页被卷起来的高度+元素高
    var page_box = $('.page-container');
    var ids = '';
    $('[type=checkbox]:checked').each(function(index, el){
        if (el.value != '') {
            ids += el.value + ',';
        }
    })

    if (ids == ''){
        layer.tips('请选中要删除项', $('[lay-filter=checkAll]').parent(),{
            tips: [3, '#e74c3c'], // 1上 2右 3下 4左
            time: 1500
        });
        return false;
    }
    ids = ids.substr(0, ids.length-1);
    console.log('待删除项：'+ids);
    console.log('刷新链接：'+paging_url);
    layer.confirm('确认要批量删除吗？', {
        title: false,
        shift: -1,
        offset: [offsetY, '21px']
    }, function(index){
        $.ajax({
            type: 'POST',
            url: delete_url,
            data: {'id':ids},
            dataType: 'json',
            success: function(data){
                if (data.code == 0) {
                    fail_alert(data.msg); // 失败
                } else {
                    ok_alert(data.msg); // 成功
                    if (paging_url != '') {
                        update_paging(paging_url, page_box);
                    }
                }
            },
        });
    });
}
/**
 * 删除数据（单个）
 * @paran {int} id 删除记录的主键id
 * @paran {obj} obj 删除按钮dom元素，用于定位删除确认框位置
 * @return {null}
 */
function datadel_an(id, obj){

    var offsetY = $(obj).offset().top - $(window).scrollTop() + 20;
    var offsetX = $(obj).offset().left - 225;
    offsetY = offsetY > $(window).height() - 120 ? offsetY - 130 : offsetY;

    var page_box = $('.page-container');
    var new_url = typeof(query_str)!='undefined'?paging_url+query_str:paging_url;
    console.log('待删除项：'+id);
    console.log('刷新链接：'+new_url);
    layer.confirm('确认要删除吗？', {
        title: false,
        shift: -1,
        offset: [offsetY, offsetX],
    }, function(index){
        $.ajax({
            type: 'POST',
            url: delete_url,
            data: {'id':id},
            dataType: 'json',
            success: function(data){
                if (data.code == 0) {
                    fail_alert(data.msg); // 失败
                } else {
                    ok_alert(data.msg); // 成功
                    if (paging_url != '') {
                        update_paging(new_url, page_box);
                    }
                }
            },
        });
    });
}

/**
 * 实现按钮点击后无刷新获取内容
 * @type {bool} 拒绝表单提交和分页a标签的跳转
 * @example 点击分页按钮，搜索按钮
 */
function page_turning()
{
    var obj = $('.page-container');
    // 搜索选项卡的提交按钮
    if ($('.select-form').length > 0 && $('.select-form-submit').length > 0) {
        $('.select-form-submit').click(function(){
            paging_url = paging_url.split('?')[0] + '?' + $('.select-form').serialize() + '&&';
            update_paging(paging_url, obj);
            return false;
        })
    }

    // 分页按钮（有搜索排序选项卡）
    $(document).on('click', '.pagination_box a', function(){
        var mark = paging_url.indexOf('&&') != -1 ? '&&' : '?'; // 连接符
        paging_url = paging_url.split(mark)[0] + mark + this.href.split('?')[1];
        update_paging(paging_url, obj);
        return false;
    })

}

/**
 * 实现按钮点击后执行逻辑并更新内容
 * @return {bool} 拒绝a标签跳转
 * @example 用户管理之启用、禁用
 */
function js_ajax_get(){
    $(document).on('click', '.js-ajax-get', function(){
        var obj = $('.page-container');
        var new_url = typeof(query_str)!='undefined'?paging_url+query_str:paging_url;
        update_paging(this.href) && update_paging(new_url, obj);
        return false;
    })
}

/**
 * 登录页
 */
function login_effect(){
    var htmls = '';
    $(document).ready(function(c) {
        htmls = $("#login-body").html();
        $("#login-body").on("dblclick", function(){
            if ($("#login-body").find(".login-form").length == 0) {
                $("#login-body").html(htmls);
                $(".login-form").hide().fadeIn(1000);
            }
        })
        $(document).on('focus blur', '[name=username], [name=password], [name=captcha]', function(){
            if ($(this).is(":focus")) {
                this.placeholder = '';
            } else {
                this.placeholder = $(this).attr("bakholder");
            }
        })
    });
}
/**
 * 隐藏登录选项卡效果
 */
function close_form(){
    $('.login-form').fadeOut(1000, function(){
        $('.login-form').remove();
    });
}

/**
 * 首页菜单
 */
function menu_effect(){
    $(".second-menu-a").on("click", function(){
        $('.second-menu').removeClass('selected');
        if ($(this).next().is(":hidden")) {
            $(this).parent().addClass('selected');
        }
        else {
            $(this).parent().removeClass('selected');
        }
        $(this).next().slideToggle(200);
        return false;
    })
    $(".contents-menu").on("click", function(){
        $('.cur-focus').removeClass('cur-focus');
        $('.cur-focus-li').removeClass('cur-focus-li');
        $(this).addClass('cur-focus-li');
        var level = $(this).attr('level');
        if (level == 3) {
            $(this).parent().prev().addClass('cur-focus');
        }
        $(this).parents('dd').prev().addClass('cur-focus');
    })
}

/**
 * 动态更新内容
 * @param  {str}  paging_url 内容链接
 * @param  {obj|null}  obj 内容容器，参数一链接获取的内容将展示在此容器，若无则表示无页面内容刷新
 * @return {bool} 说明：参数二为空（逻辑操作）且成功时返回true
 * @example 用户禁用、启用，分页按钮，搜索选项卡等
 */
function update_paging(paging_url, obj='')
{
    $.ajaxSetup({
        async : false
    });
    var ajax_ret = false;
    $.get(paging_url, {}, function(ret){
        if (obj != '') {
            obj.empty().append(ret);
            layui_form_effect(); // 更新渲染
        } else if (ret.code == 1) {
            // 逻辑成功(如启用、禁用)
            ajax_ret = true;
        }
    }, 'json')
    return ajax_ret;
}


/*弹出层*/
/*
    参数解释：
    title   标题
    url     请求的url
    w       弹出层宽度（缺省调默认值）
    h       弹出层高度（缺省调默认值）
*/
function layer_show(title,url,w,h){
    if (title == null || title == '') {
        title=false;
    };
    if (url == null || url == '') {
        url="404.html";
    };
    if (w == null || w == '') {
        w=800;
    };
    if (h == null || h == '') {
        h=($(window).height() - 50);
    };
    layer.open({
        type: 2,
        area: [w+'px', h +'px'],
        fix: true, //不固定
        maxmin: false,
        shade:0.4,
        title: false,
        content: url
    });
}

/**
 * 日期插件
 * @param  {int} flag 1开始日期 2结束日期
 * @return {null}
 */
function selecttime(flag, plustime=0){
    dateFormt = 'yyyy-MM-dd';
    if (plustime == 1) {
        dateFormt = dateFormt + ' HH:mm';
    }

    if(flag==1){
        var endTime = $("#countTimeend").val();
        if(endTime != ""){
            WdatePicker({dateFmt:dateFormt,maxDate:endTime})

        }else{
            WdatePicker({dateFmt:dateFormt})
        }
    }else{
        var startTime = $("#countTimestart").val();
        if(startTime != ""){
            WdatePicker({dateFmt:dateFormt,minDate:startTime})
        }else{
            WdatePicker({dateFmt:dateFormt})
        }
    }
}

/**
 * 关闭选项卡
 * @param  {obj} obj dom元素
 * @param  {str} msg 选项卡隐藏关闭之后弹出的消息
 * @return {null}
 */
function close_tab(obj, msg, icon){
    $(obj.parentNode).slideUp(1000,function(){
        msg_alert(msg, icon);
        $(obj.parentNode).remove();
    });
}

/**
 * layui表单元素需引入
 * @return {null}
 */
function layui_form_effect()
{
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer
        ,form = layui.form;
        form.render(); // 更新渲染
        form.on('checkbox(checkAll)', function(data){
            $('[type=checkbox]:not([disabled])').prop('checked',data.elem.checked)
            form.render('checkbox');
        });
    });

}

/**
 * ajax获取全局参数相关
 * @return {null}
 */
function whole_param(type = ''){
    if (type == 'article') {
        $.ajax({
            type: 'GET',
            url:  '/index.php/api/index/article_param?sign=dev_sign',
            data: {},
            dataType:'json',
            async: false,
            success:function(data){
                // 定义成全局变量（做法不推荐）
                intervals = data.datas.intervals;
                auto_txt = data.datas.auto_txt;
                auto_hold_auth = data.datas.auto_hold_auth;
            }
        })
    }
}

/**
 * 文章编辑相关
 * @ 释义: 存在百度编辑器id-全局引用
 * @return {null}
 */
function article_add_onload(){

    // 点击保存草稿
    $(document).on('click', '.js-ajax-submit-draft', function(){

        // 是否开始输入文字
        if ($('[name=permission]').val() == '' && $('[name=id]').val() == '') {
            msg_alert('没有任何文字输入', 'shenhe-weitongguo');
            return false;
        }

        // 上锁
        if (!submit_lock && !$(this).hasClass('js-test'))
            return false;
        else
            submit_lock = false;

        submit_draft();
        return false;
    })

    // layui: 评论switch
    layui.use('form', function(){
        var form = layui.form;
        form.on('switch(check-comment)', function(data){
            var comment_input = $(this).parents('.row').next().find('input');
            if ($(this).is(':checked')) {
                comment_input.removeAttr('disabled').removeAttr('placeholder');
            } else {
                comment_input.val('').attr({disabled : 'disabled', placeholder : '无法输入'});
            }
            form.render('checkbox');
        });
    });

    // layui: 图片上传控件
    layui.use('upload', function(){
        var $ = layui.jquery
        ,upload = layui.upload;

        //普通图片上传
        var uploadInst = upload.render({
            elem: '#upload_element'
            ,url: '/index.php/api/Upload/image/sign/dev_sign'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#show_img').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.errcode > 0){
                    return layer.msg('重试');
                }
                //上传成功
                $('#show_img').attr('src', res.datas.domain + res.datas.image);
                $('[name=thumbnail]').val(res.datas.domain + res.datas.image);
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });

    });

    // 有草稿提交权限时的关闭网页询问
    window.onbeforeunload = function(event) {
        if ($('[name=permission]').val() == 1) {
            return '有修改部分未保存，是否继续离开？';
        }
    };

    // 定时自动保存一次
    window.setInterval(function(){
        if ($('[name=permission]').val() == 1 && auto_hold_auth == 1) {
            $('.auto_hold_tips').fadeIn(100, function(){
                $(this).html(auto_txt).delay(3000).fadeOut(100);
                $('[name="auto_hold"]').val(1);
                submit_draft();
                console.log(auto_txt);
            });
        }
    }, intervals);

    // 内容修改时开启草稿提交权限
    $('input[type=text],textarea,select').change(function(){
        $('[name=permission]').val(1);
    })

}

/**
 * 百度编辑器相关（含配置）
 * @ 释义: 存在百度编辑器id-全局引用
 * @return {null}
 */
function ueditor_onload() {
     var ue = UE.getEditor('ueditor', {
        toolbars: [[
            'html',
            'source', //源代码
            'paragraph', //段落格式
            'fontsize', //字号
            'forecolor', //字体颜色
            'bold', //加粗
            'italic', //斜体
            'indent', //首行缩进
            'subscript', //下标
            'fontborder', //字符边框
            'superscript', //上标
            'blockquote', //引用
            'link', //超链接
            'unlink', //取消链接
            'pasteplain', //纯文本粘贴模式
            'horizontal', //分隔线
            'pagebreak', //分页
            'insertcode', //代码语言
            'simpleupload', //单图上传
            'insertvideo', //视频
            'emotion', //表情
            'fullscreen', //全屏
            'attachment', //附件
            'removeformat', //清除格式
            'preview', //预览
            ]],
        wordCount: false,
        elementPathEnabled: false,
        autoHeightEnabled: false,
        initialFrameWidth: '85%',
        initialFrameHeight: '600',
        imageActionName: 'uploadimage',
        serverUrl: '/index.php/api/Ue/init/sign/dev_sign', // 服务器统一请求接口路径
    });

    ue.ready(function() {
        var html = ue.getContent();
        var txt = ue.getContentTxt();
        // 百度编辑器失去焦点
        ue.addListener('blur',function(editor){
            // 内容修改
            if (html != ue.getContent()) {
                $('[name=auto_hold]').val(1);
                $('[name=permission]').val(1);
                $('[name=content]').val(ue.getContent());
                html = ue.getContent();
                submit_draft();
                // 自动存档提示
                $('.auto_hold_tips').fadeIn(100, function(){
                    $(this).html("自动存档中...").delay(3000).fadeOut(100);
                });
            }
            console.log("百度编辑器失去焦点");
        });
        // 重置编辑器内容
        $('[type=reset]').click(function(){
            ue.setContent(html);
            ue.reset();
        })
    });
}