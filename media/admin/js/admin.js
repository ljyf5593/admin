// ++++++++++++++++++++++++++++++++++++++++++

!function ($) {

  $(function(){

      // ajax初始化
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
                          complete: function(event, request, settings){
                              main.bar.animate({
                                  width: "100%"
                              }, 1500);
                              main.process.delay(1500).hide();
                          }
                      });
                  }
              });
              main.initAjaxSetup();
              return this;
          }
      }).init();


      $("#content").on("loaded", function(e){
          var $main = $(this);

          $main.extend({
              //加载json数据
              loadData : function(json, showmessage){

                  // 如果有页面跳转信息
                  if(json.location){
                      setTimeout(function(){
                          window.location = json.location;
                      }, json.time*1000);
                  }

                  // 如果返回的信息中有内容输入，则输入到页面中
                  if(json.content){
                      this.html(json.content);
                  }

                  if(json.status == 'success'){ // 如果是返回操作成功的信息
                      $(json.content).find("div.show-msg")
                      this.trigger("loaded");
                      //操作提示信息
                      if(showmessage && json.status_info){
                          $main.msgDiv.addClass("span3 alert-success").find("span").html(json.status_info);
                      }

                  } else { // 如果返回的是操作失败的信息

                      if( typeof(json.status_info) == 'object'){ // 如果返回的信息是一个对象
                          var status_info = json.status_info;
                          var key;
                          for(key in status_info){
                              this.find("#"+key+"-control-group").addClass("error").find("#"+key+"-label").removeClass("hide").html(status_info[key]);
                          }

                          if(typeof(status_info._external) == 'object'){
                              var external_status_info = status_info._external;
                              var key;
                              for(key in external_status_info){
                                  this.find("#"+key+"-control-group").addClass("error").find("#"+key+"-label").removeClass("hide").html(external_status_info[key]);
                              }
                          }

                      } else {

                          //操作提示信息
                          this.trigger("loaded");
                          if(showmessage && json.status_info){
                              $main.msgDiv.addClass("span3 alert-error").find("span").html(json.status_info);
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
              msgDiv: $main.find("div.show-msg")
          });

          // 消息提示框动画
          $main.msgDiv.delay(5000).hide(800, function(){$(this).removeClass("span3")});

          // CRUD ajax,会有操作提示信息
          $("a.ajax").on("click", function(e){
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

          // Form ajax
          $("form.ajaxform").ajaxForm({
                  dataType: "json",
                  success: function(json){
                      $main.loadData(json, true);
                  }
              });

          //导航栏处的动作提交
          $main.find("div.top-bar a, div.pagination ul li a").on("click", function(e){
              e.preventDefault();
              var $this = $(this);

              // 排除下拉
              if($this.hasClass('dropdown-toggle')){
                  return;
              }

              // 排除链接为空
              var url = $this.attr("href");
              if(url.length <= 0){
                  return;
              }

              $.get($this.attr("href"), { _t: new Date().getTime() }, function(json){
                  $main.loadData(json, false);
              }, "json");
          });

          //批量操作
          $("a.batch").on("click", function(e){
              var $this = $(this);
              //获取当前点击的动作，加入到form表单中进行提交
              var operation = $this.attr('rel');
              $this.closest("form").append("<input type='hidden' name=\"operation\" value=\""+operation+"\">").submit();
          });

          //help text
          $main.find("i.popover-help").popover();
          $main.find("i.popover-help-top").popover({placement:'top'});

          // Select-all
          $("input.select-all").on("click", function(e){
              if($(this).attr("checked") == "checked"){
                  $("input.selection:enabled").attr("checked", true);
              }
              else{
                  $("input.selection:enabled").attr("checked",false);
              }
          });

          //时间控件
          $("span.datetime").on('click', function(){
              WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss', el: $(this).attr('target')});
          });
          $("span.date").on('click', function(){
              WdatePicker({dateFmt:'yyyy-MM-dd', el: $(this).attr('target')});
          });

          //富文本编辑器
          $("#content-edit").length > 0 && KindEditor.create('#content-edit',{
              uploadJson : UPLOAD_URL,
              fileManagerJson : FILE_MANAGE_URL,
              allowFileManager : true
          });

          //图片选择
          $("#image").on('click', function(){
              var $main = $(this);
              var image_editor = KindEditor.editor({
                  uploadJson : UPLOAD_URL,
                  fileManagerJson : FILE_MANAGE_URL,
                  allowFileManager : true
              });

              image_editor.loadPlugin('image', function(){
                  image_editor.plugin.imageDialog({
                      imageUrl: $main.val(),
                      clickFn: function(url, title, width, height, border, align) {
                          $main.val(url);
                          image_editor.hideDialog();
                      }
                  })
              })
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
              listTable.append("<tr><td></td><td><input type=\"text\" value=\"0\" name=\"newmodel["+listTableTr+"][displayorder]\" class=\"input-mini\"></td><td><input type=\"text\" value=\"\" name=\"newmodel["+listTableTr+"][name]\" class=\"input-xlarge\"></td><td><input type=\"text\" value=\"\" name=\"newmodel["+listTableTr+"][label]\" class=\"input-xlarge\"></td></tr>");
              listTableTr++;
          });

          // 增加列表项
          $main.find('#change-submit select').on('change', function(e){
              $(this).closest('form').submit();
          });

          // 联动下拉菜单
          $main.find('select.chain').on('change', function(e){
              var base_url = $(this).data('chain_url');
              var target = $(this).data('chain_target');
              $.getJSON(base_url, {'label':this.value, '_t':new Date().getTime()}, function(json){
                if(json.status == 'success'){
                    var insertHTML = '<option value="">--请选择--</option>';
                    for(var item in json.list){
                        insertHTML += '<option value="'+item+'">'+json.list[item]+'</option>';
                    }
                    $main.find('#'+target).html(insertHTML);
                }
              });
          });

          //=========== Application =========//


      }).trigger("loaded");

      //loading btn
      $('.stateful').on("click", function () {
          var $btn = $(this);
          $btn.button("loading");
      }),

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
                  if(json.status == 'success'){
                      $("#msg").addClass('alert alert-success').html(json.status_info);
                      window.location.href = window.location.href;
                  } else if(json.status == 'error') {
                      $("#msg").addClass('alert alert-error').html(json.status_info);
                  }
              }
          });

      // Nav Pagination ajax
      $("ul.nav, div.pagination ul").delegate("li", 'click', function(e){
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

          $.get(url, { _t: new Date().getTime() }, function(json){
              //触发自定义事件
              if (json.location) {
                  window.location.href = json.location;
              } else if (json.url){
                  setTimeout(function(){
                      $.get(json.url, { _t : new Date().getTime() }, function(response){
                          $('#content').html(response.content).trigger("loaded");
                      }, "json");
                  }, json.time*1000);
              } else if(json.status == 'success'){
                  $('#content').html(json.content).trigger("loaded");
              } else {
                  $('#content').html(json.status_info);
              }
          }, "json");
      });
  })
}(window.jQuery)