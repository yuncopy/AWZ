<?php $this->load->view('admin/_header'); ?>
<div class="pd-20">
  <form action="/admin/index/permission_add.html" method="post" class="form form-horizontal" id="form-member-add">
    <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>节点名称：</label>
      <div class="formControls col-5">
        <input type="text" class="input-text" value="" placeholder="添加管理员" id="title" name="title" datatype="*2-16" nullmsg="节点名称不能为空">
      </div>
      <div class="col-4"> </div>
    </div>
    <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>节点路径：</label>
      <div class="formControls col-5">
        <input type="text" class="input-text" value="" placeholder="admin/member/add.html" id="path" name="path" datatype="*6-32" nullmsg="节点路径不能为空">
      </div>
      <div class="col-4"> </div>
    </div>
    <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>是否验证：</label>
      <div class="formControls col-5 skin-minimal">
        <div class="radio-box">
          <input type="radio" value="1" id="sex-1" checked name="status" datatype="*" nullmsg="是否验证！">
          <label for="sex-1">需验证</label>
        </div>
        <div class="radio-box">
          <input type="radio" value="2" id="sex-2" name="status">
          <label for="sex-2">无验证</label>
        </div>
      </div>
      <div class="col-4"> </div>
    </div>

    <div class="row cl">
      <label class="form-label col-3">备注：</label>
      <div class="formControls col-5">
        <textarea name="desc" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" nullmsg="备注不能为空！" onKeyUp="textarealength(this,100)"></textarea>
        <p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>
      </div>
      <div class="col-4"> </div>
    </div>
    <div class="row cl">
      <div class="col-9 col-offset-3">
        <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
      </div>
    </div>
  </form>
</div>
</div>
<?php $this->load->view('admin/_javascript'); ?>
<script type="text/javascript" src="/public/lib/icheck/jquery.icheck.min.js"></script>
<script type="text/javascript" src="/public/lib/Validform/5.3.2/Validform.min.js"></script>
<script type="text/javascript">
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});

	$("#form-member-add").Validform({
		tiptype:2,
		callback:function(form){
			form[0].submit();
			//var index = parent.layer.getFrameIndex(window.name);
			//parent.$('.btn-refresh').click();
			//parent.layer.close(index);
		}
	});
});
</script>
</body>
</html>