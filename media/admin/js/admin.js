jQuery(function($){
    var msgDiv = $('#show-msg');

    // ajax初始化,同时展现进度条信息
    $('#ajaxstatus').extend({
      init: function(){
          var main = this.extend({
              process: this.find("div.progress"),
              bar: this.find("div.bar"),
              initAjaxSetup: function(){

                  // 设置ajax错误信息显示方式
                  $.ajaxSetup({
                      // 当服务器端有错误时，在提示错误信息
                      error: function(XMLHttpRequest, textStatus, errorThrown){
                          alert(textStatus + ': '+errorThrown+"\n"+
                              "thrownError:"+ XMLHttpRequest.responseText);
                      },
                      // 在ajax开始发送数据之前，显示进度条
                      beforeSend: function(XMLHttpRequest){
                          main.process.show();
                          main.bar.css({width: '25%'});
                      },
                      // 在ajax请求完整后完成进度条后隐藏
                      complete: function(XMLHttpRequest, status){
                          var json = XMLHttpRequest.responseJSON;
                          main.bar.animate({
                              width: "100%"
                          }, 1500);
                          main.process.delay(1500).hide();

                          // 如果有页面跳转信息
                          if(json.location){
                              setTimeout(function(){
                                  window.location = json.location;
                              }, json.time*1000);
                          }

                          //操作提示信息
                          if( json.status && json.status_info && typeof(json.status_info)=='string' ){
                              msgDiv.addClass("span3 alert-"+json.status).show().delay(3000).hide(800, function(){
                                    $(this).removeClass("span3")
                                }).find("span").html(json.status_info);
                          }
                      }
                  });
              }
          });
          main.initAjaxSetup();
          return this;
      }
    }).init();

    // Login ajax
    $("#login-form").ajaxForm({
      beforeSubmit: function(formData){
          var validate = false;
          $(formData).each(function(){
              var control = $('#'+this.name+'-control');
              control.removeClass('error');
              if(!this.value){
                  control.addClass('error');
                  validate = true;
              }
          });

          if(validate) {
              $('.stateful').button('reset')
              return false;
          }
      },
      dataType: "json",
      success: function(json){
          $('.stateful').button('reset');
          $("#msg").addClass('alert alert-'+json.status).html(json.status_info);
          if(json.status == 'success'){
              window.location.href = window.location.href;
          }
      }
    });

    var modal_content = $("#modal-content").on("loaded", function(e){
        var $main = $(this);
        
        //富文本编辑器
        if($main.find("#content-edit").length > 0){
          var editor = KindEditor.create('#content-edit',{
              uploadJson : UPLOAD_URL,
              fileManagerJson : FILE_MANAGE_URL,
              filterMode : false,
              allowFileManager : true
          });
        } 
        // 表单ajax绑定
        $main.find("form.ajaxform").ajaxForm({
            dataType: "json",
            beforeSerialize: function($form, options) { 
                editor && editor.sync();
            },
            success: function(json){
                $main.loadData(json);
                if(json.status == 'success'){
                  $main.modal('hide');
                }
            }
        });

        //图片选择
        $main.find("#image").on('click', function(){
            // 如果编辑器没有出现过则创建一个用于加载编辑器样式文件
            editor || KindEditor.create();
            var $me = $(this);
            var target = $me;
            if(target_id = $me.data('target')){
                target = $('#'+target_id);
            }

            var image_editor = KindEditor.editor({
                uploadJson : $me.data('upload') || UPLOAD_URL,
                fileManagerJson : $me.data('file') || FILE_MANAGE_URL,
                allowFileManager : true
            });

            image_editor.loadPlugin('image', function(){
                image_editor.plugin.imageDialog({
                    imageUrl: target.val(),
                    clickFn: function(url, title, width, height, border, align) {
                        target.val(url);
                        image_editor.hideDialog();
                    }
                })
            })
        });

        $main.initLoadedEvent();

        return $main;
    });

    var view_content = $("#content").on("loaded", function(e){
        var $main = $(this);

        // 表单ajax绑定
        $main.find("form.ajaxform").ajaxForm({
            dataType: "json",
            success: function(json){
                $main.loadData(json, true);
            }
        });

        // 模态弹出层，用于编辑和创建
        $main.find("a.ajax-modal").on('click', function(e){
            e.preventDefault();
            $.get($(this).attr("href"), {_t : new Date().getTime(), modal: 'true' }, function(json){
              // 弹出模态
              modal_content.modal().loadData(json);
            }, "json")
        });

        // CRUD ajax,会有操作提示信息
        $main.find("a.ajax").on("click", function(e){
            e.preventDefault();
            var $this = $(this);

            // 如果需要弹窗提示
            var confirm_data = $this.data('confirm');
            if(confirm_data != undefined){

                bootbox.confirm(confirm_data, function(confirmed) {
                    if(confirmed){
                        $.get($this.attr("href"), { _t : new Date().getTime() }, function(json){
                            $main.loadData(json, true);
                        }, "json");
                    }
                });

            } else {
                $.get($this.attr("href"), { _t : new Date().getTime() }, function(json){
                    $main.loadData(json, true);
                }, "json");

            }
        });

        //批量操作
        $main.find("a.batch").on("click", function(e){
            var $this = $(this);
            //获取当前点击的动作，加入到form表单中进行提交
            var operation = $this.attr('rel');
            $this.closest("form").append("<input type='hidden' name=\"operation\" value=\""+operation+"\">").submit();
        });

        //文章栏目添加一行
        $main.find('td a.addtr').on('click', function(e){
          e.preventDefault();
          var $this = $(this);

          //添加的栏目级别
          var level = $this.attr('level');
          var parentid = $this.attr('catid');
          var tr = $this.closest("tr")
          var td_len = tr.find("td").length;
          var newtr = "<tr>";
          for(var i = 0; i < td_len; i++){
              if(i == 1){
                  newtr += "<td class=\"level"+(++level)+"\"><input type=\"text\" name=\"newname["+parentid+"][]\" ></td>";
              } else {
                  newtr += "<td></td>";
              }
          }

          // 如果是添加顶级栏目，则在其上方添加新行
          if(level == 0){
              $(tr).before(newtr);

          }else { // 默认在点击的下方添加新行
              $(tr).after(newtr);
          }
        });

        //=========== Application =========//
        var listTable = $main.find('#list-table');
        var listTableTr = listTable.find('tr').length;
        // 增加列表项
        $main.find('div.btn-group a.addtr').on('click', function(e){
          e.preventDefault();
          listTable.append("<tr><td></td><td></td><td><input type=\"text\" value=\"0\" name=\"newmodel["+listTableTr+"][displayorder]\" class=\"input-mini\"></td><td><input type=\"text\" value=\"\" name=\"newmodel["+listTableTr+"][name]\" class=\"input-xlarge\"></td><td><input type=\"text\" value=\"\" name=\"newmodel["+listTableTr+"][label]\" class=\"input-xlarge\"></td></tr>");
          listTableTr++;
        });
        //=========== end Application =========//

        $main.initLoadedEvent();

        return $main;

    }).trigger("loaded");

    // Nav Pagination pjax
    $(document).on('click', "ul.nav li, div.top-bar ul li, div.pagination ul li", function(e){
      e.preventDefault();
      $this = $(this);
        
      // 如果导航中不存在地址，则直接返回
      var url = $this.find('a').attr('href');
      if(url.length <= 0){
          return ;
      }
      
      $this.parent().find('li').removeClass('active');
      $(this).addClass('active');

      // 如果存在下拉菜单，则直接返回，不再ajax提交
      if($this.hasClass('dropdown')){
          return ;
      }

      $.pjax({
        url : url,
        container: '#content'
      });
    }).on('pjax:end', function(){
      view_content.trigger('loaded');
    });

    //loading btn
    $('.stateful').on("click", function () {
      var $btn = $(this);
      $btn.button("loading");
    });
});

jQuery.fn.extend({
  //加载json数据
  loadData : function(json, showmessage){
    var $main = this;
    var msgDiv = $('#show-msg');

    // 如果返回的信息中有内容输入，则输入到页面中
    if(json.content){
        $main.html(json.content).trigger('loaded');
    }

    if( typeof(json.status_info) == 'object'){ // 如果返回的信息是一个对象
        var status_info = json.status_info;
        var key;
        for(key in status_info){
            $main.find("#"+key+"-control-group").addClass("error").find("#"+key+"-label").removeClass("hide").html(status_info[key]);
        }

        if(typeof(status_info._external) == 'object'){
            var external_status_info = status_info._external;
            var key;
            for(key in external_status_info){
                $main.find("#"+key+"-control-group").addClass("error").find("#"+key+"-label").removeClass("hide").html(external_status_info[key]);
            }
        }
    }

    // 如果有ajax跳转信息
    if(json.url){
        setTimeout(function(){
            $.get(json.url, { _t : new Date().getTime() }, function(response){
                $main.loadData(response, true);
            }, "json");
        }, json.time*1000);
    }
  },

  // 绑定常用数据项
  initLoadedEvent: function(){
    var $main = this;

    //help text
    $main.find("i.popover-help").popover();
    $main.find("i.popover-help-top").popover({placement:'top'});

    // Select-all
    $main.find("input.select-all").on("click", function(e){
        $("input.selection:enabled").attr("checked", $(this).is(":checked"));
    });

    // 下拉菜单提交时更新
    $main.find('#change-submit select').on('change', function(e){
        $(this).closest('form').submit();
    });

    // 联动下拉菜单
    $main.find('select.chain').on('change', function(e){
      var base_url = $(this).data('chain_url');
      var target = $(this).data('chain_target');
      $.getJSON(base_url, {'label':this.value, '_t':new Date().getTime()}, function(json){
        if(json.status == 'success'){
            var insertHTML = '';
            for(var item in json.list){
                insertHTML += '<option value="'+item+'">'+json.list[item]+'</option>';
            }
            $main.find('#'+target).html(insertHTML);
        }
      });
    });

    //时间控件
    $main.find("span.datetime").on('click', function(){
        WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss', el: $(this).attr('target')});
    });
    $main.find("span.date").on('click', function(){
        WdatePicker({dateFmt:'yyyy-MM-dd', el: $(this).attr('target')});
    });
    $main.find("input.datetime").on('click', function(){
        WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
    });
    $main.find("input.date").on('click', function(){
        WdatePicker({dateFmt:'yyyy-MM-dd'});
    });
  }
})