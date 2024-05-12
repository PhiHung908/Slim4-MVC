<?php
//
?>



<script src="/gStatic/pako.min.js" ></script>
<script src="/gStatic/phUtils.js" ></script>
<script src="/gStatic/PH_Utils.UiBase.min.js"></script>
<link href="/gStatic/dd-content.css" rel="stylesheet">
<script src="/gStatic/dd-content.js" ></script>
<script src="/gStatic/dd-data.js"></script>


<link href="/gStatic/hwg.nav.css" rel="stylesheet">
<link href="/gStatic/site.css" rel="stylesheet">
<script src="/gStatic/wDesign.js"></script>




<div>
<p><span><button class="btn btn-primary">Tao layout</button>
<span><button class="btn btn-primary">Tuy chinh bo cuc</button></p>
</div>

<div class="d-none     two-col-drag-drop-container" style="display: inherit;"><div class="drag-drop-container"><div class="row row-for-two-col"><div class="col col-sm-3"><div class="row"><div class="row-dd-container"><div class="col hwgDdContent"><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div></div></div></div><div class="row"><div class="row-dd-container"><div class="col hwgDdContent"><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div></div></div></div></div><div class="col col-sm-9"><div class="row"><div class="row-dd-container"><div class="col hwgDdContent"><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div></div><div class="col hwgDdContent"><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div></div></div><div class="row-dd-container"><div class="col hwgDdContent"><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div></div><div class="col hwgDdContent"><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div></div><div class="col hwgDdContent"><div class="col-dd-container"><div class="dd-lines-container"><div class="dd-line-container"><div>noi dung</div></div></div></div></div></div></div></div></div></div></div>


<div class="w-100 dd-toolbar-page-fixed dd-toolbar-fixed d-none" style="min-height:4rem;">
</div>

<div id="dd-dyn-toolbar" class="dd-toolbar-fixed col-auto">
</div>

	<div class="dd-dyn-tools-container dd-toolbar-fixed col-auto d-none"></div>
	<div class="wrap-action wrap-action--no-padding action-container invisible">
		<div class="mr-2 act_move">
			<button class="btn-action primary" title="Kéo thả để di chuyển mục">
				<i class="fa-solid fa-arrows-up-down-left-right"></i>
			</button>
		</div>
		<div class="mr-2 act_plus" title="Thêm #LABEL#">
			<div class="btn-action">
				<i class="fa-solid fa-plus"></i>
			</div>
		</div>
		<div class="mr-2 act_add_padding">
			<div class="btn-action btn-action--active" title="Tăng khoảng cách lề">
				<img src="/gStatic/icon-padding-close.svg">
			</div>
		</div>
		<div class="mr-2 act_remove_padding">
			<div class="btn-action" title="Giảm khoảng cách lề">
				<img src="/gStatic/icon-padding-open.svg"/>
			</div>
		</div>
		<div class="mr-2 act_delete">
			<div class="btn-action delete" title="Xóa">
				<i class="fa-solid fa-trash"></i>
			</div>
		</div>
		<div class="mr-2 act_copy">
			<div class="btn-action" title="Tạo bản sao">
				<i class="fa-light fa-copy"></i>
			</div>
		</div>
		<div class="mr-2 act_line_up" >
			<div class="btn-action primary" title="Di chuyển lên trên">
				<i class="fa-solid fa-arrow-up"></i>
			</div>
		</div>
		<div class="mr-2 act_line_down">
			<div class="btn-action primary" title="Di chuyển xuống dưới">
				<i class="fa-solid fa-arrow-down"></i>
			</div>
		</div>
		<div class="mr-2 act_line_hidden" >
			<div class="btn-action">
				<i class="fa-solid fa-eye-slash"></i>
			</div>
		</div>
		<div class="mr-2 act_line_show" >
			<div class="btn-action" title="Ẩn/Hiện mục">
				<i class="fa-solid fa-eye"></i>
			</div>
		</div>
		<div class="mr-2 act_ql_toolbar" >
			<div class="btn-action bg-success" title="Mở thanh công cụ">
				<i class="fa-solid fa-edit"></i>
			</div>
		</div>
	</div>
	
<div class="container"><div class="row">
	<div id="col3_toggle" class="col col-3" >
		<div class="dd-col-menu-container">
			<div class="p-head">
				<div class="fa-light fa-close float-end end-0 p-0" onclick="toggleAddComponent();return false;"></div>
				<div><span XXclass="fa-light fa-circle"></span><span class="ms-2">Thêm mục</span></div>
			</div>
			<div class="p-menu-container">
				<div class="menu-item-add-yes">
					<div class="p-title">Mục chưa sử dụng</div>
					<div class="p-hint">Kéo và thả mục bất kỳ vào vị trí bạn muốn thêm trên CV</div>
					<div class="p-menu line-menu"><span class="fa fa-plus"></span><span class="menu-title">Thêm mục</span></div>
				</div>
				<div class="menu-item-add-no">
					<div class="p-title">Mục đã sử dụng</div>
					<div class="p-hint">Click để xem vị trí của mục trên CV</div>
					<div class="p-menu dd-col-menu"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col col-9">
		<div class="g-2 dd-cont2">
		</div>
		<div id="playground"></div>
	</div>
</div></div>



<script>
window.addEventListener('load', function() {
	$('.dd-cont2').mkDdCont({
		containerDotClass: '.dd-cont2',
		eventDotClass: '.action-container',
		cloneEventDotClass: true,
		col: 3,
		settings: {
			indentClass: 'ps-3',
			paddingClass: 'p-3',
		},
		toolbarSelector: '#dd-dyn-toolbar',
		notAutoThumbToolbar: false,
		delayForUpdate: 0,
		ddData: JSON.parse(window.ddData)
	});
	
	
})

/*
declare
  c clob := regexp_replace(regexp_replace(regexp_replace(:P5_JS_DD_SENDER,'\\n','\\\\n'),'\\t','\\\\t'),'\\"','\\\\"');
begin
  update cv_demo set dd_data = c where id = :P5_ID;
end;
*/
function toggleAddComponent() {
  var h = !$("#col3_toggle").hasClass('d-none')
  if (h) {
	$("#col3_toggle").addClass('d-none').next().removeClass('col-9'); 
	if (!phUtils.isMobile() && $('#t_Button_navControl[aria-expanded!="true"]').length) $('#t_Button_navControl[aria-expanded!="true"]').trigger('click')
  } else {
    $("#col3_toggle").removeClass('d-none').next().addClass('col-9');
	if ($('#t_Button_navControl[aria-expanded="true"]').length) $('#t_Button_navControl[aria-expanded!="true"]').trigger('click')
  }
}
</script>

