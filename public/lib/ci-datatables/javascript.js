;$(document).ready(function()
{
	/*
	$('#data').dataTable({
		"bDestroy":true, 	//***销毁已经存在的Datatables实例并替换新的选项
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "POST",
		"sAjaxSource": "data/getTable",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[0, 'asc']],
		"aoColumns": [
			{ "bVisible": true, "bSearchable": true, "bSortable": true },
			{ "bVisible": true, "bSearchable": true, "bSortable": true },
			{ "bVisible": true, "bSearchable": true, "bSortable": true }
		]
	}).fnSetFilteringDelay(700);
	*/
	$.fn.extend({
		getTable: function(url) {
			$(this).dataTable({
				"bFilter": 			false,   //搜索框方法三：这种方法可以
				"bAutoWidth": 		false,//宽度是否自动，感觉不好使的时候关掉试试
				"bProcessing": 		false,//开关，以指定当正在处理数据的时候，是否显示“正在处理数据”这个提示信息
				"bServerSide": 		true,//开启此模式后，你对datatables的每个操作 每页显示多少条记录、下一页、上一页、排序（表头）、搜索，这些都会传给服务器相应的值
				"sServerMethod": 	"POST",
				"sPaginationType": 	"full_numbers",//用于指定分页器风格
				"sAjaxSource": 		url,//给服务器发请求的url
				"iDisplayLength":	10,//每页显示多少信息
				"bPaginate": 		true, //开始翻页功能
				"destroy": 			true, //***销毁已经存在的Datatables实例并替换新的选项
				"bInfo": 			true,//页脚信息,就是Showing 1 to 10 of 57 entries
				"bStateSave": 		true, //保存当前页的状态  当修改或删除时保存到当前页
				"aoColumnDefs": 	[{"bSortable": false, "aTargets":[ 0,2,3 ]}],//指定某些字段不能排序
				"order": 			[[ 1, 'asc' ]],//对应的字段排序
				"bLengthChange": 	false,   //去掉每页显示多少条数据方法
				"aLengthMenu": 		[[15, 20, 25, -1], [15, 20, 25, "显示所有"]],//显示搜索的条目数
				//"sDom":				'<"top"l>rt<"wrapper"><"wrapper"p><"clear">',
				//回调函数
				/*
				"fnServerData": function ( sSource, aoData, fnCallback ) {
					$.ajax( {
						"dataType": 'json',
						"type": "POST",
						"url": sSource,
						"data": aoData,
						"success": fnCallback,
						"complete":function(){
							console.log('add class text-c');
							$(this).find('tr').addClass('text-c');
						}
					});
				},
				//向服务器传额外的参数
				"fnServerParams": function ( aoData ) {
					aoData.push(
						{ "name": "name1", "value": value1},
						{"name":'name2',"value":value2}
					);

				},
				 */
				"oLanguage": {
					"sLengthMenu": 		"每页显示 _MENU_ 条记录",
					"sZeroRecords": 	"抱歉， 没有找到",//提示信息
					"sInfo": 			"从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
					"sInfoEmpty": 		"没有数据",
					"sInfoFiltered": 	"(从 _MAX_ 条数据中检索)",
					"oPaginate": {
						"sFirst": 		"首页",
						"sPrevious": 	"上一页",
						"sNext": 		"下一页",
						"sLast": 		"尾页"
					}
					//"sProcessing": "<img src='./logo.png' />"
				}/*,
				"aoColumns": [
					{ "sName": "id" },
					{ "sName": "id" },
					{ "sName": "path" },
					{ "sName": "title" },
					{ "sName": "states" },
					{ "sName": "description" }

				]*/
				// $aColumns = array('id', 'path', 'title','states','description');
			});
			return this;
		}
	});

});