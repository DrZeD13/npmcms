//получение GET параметра
function $_GET(key) {
	var p = window.location.search;
	p = p.match(new RegExp(key + '=([^&=]+)'));
	return p ? p[1] : false;
}

function limitChars(textid, min, limit, infodiv, type)
{
	var text = $('#'+textid).val();	
	var textlength = text.length;
	if(textlength > limit)
	{
			$('#'+textid).css('background', '#F2DEDE');
			if (type == "title")
			{
				$('#' + infodiv).html('Вы ввели '+ textlength +' символов. Рекомендуемое от 10 до 70 символов');
			}
			else
			{
				$('#' + infodiv).html('Вы ввели '+ textlength +' символов. Рекомендуемое от 70 до 160 символов');
			}	
		return false;
	}
	if (textlength < min)
	{
		$('#'+textid).css('background', '#F2DEDE');
		if (type == "title")
		{
			$('#' + infodiv).html('Вы ввели '+ textlength +' символов. Рекомендуемое от 10 символов');
		}
		else
		{
			$('#' + infodiv).html('Вы ввели '+ textlength +' символов. Рекомендуемое от 70 символов');
		}
		return false;
	}
	else
	{
		$('#'+textid).css('background', '#F5F5F5');
		$('#' + infodiv).html('Вы ввели '+ textlength +' символов');
		return true;
	}
}
/*
//если нужно что бы сразу выводилось
$(document).ready(function() {
	limitChars('description', 160, 'charlimitinfo');
});*/

$(function(){
	$('#description').keyup(function(){
		limitChars('description', 70, 160, 'charlimitinfo', 'description');
	})
});

$(function(){
	$('#head_title').keyup(function(){
		limitChars('head_title', 10, 70, 'charlimitinfotitle', 'title');
	})
});

//спойлер
$(document).ready(function(){
 $('.seo-spoiler-links').click(function(){
  $(this).parent().children('table.seo-spoiler-body').toggle('normal');
  return false;
 });
});

// красивый селект
$(document).ready(function(){
var config = {
  '.chosen-select'           : {},
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}
});

// вкладки
(function($) {
$(function() {

	$('ul.tabs').each(function(i) {
		var storage = localStorage.getItem('tab'+i);
		if (storage) $(this).find('li').eq(storage).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').hide().eq(storage).show();
	})

	$('ul.tabs').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
		var ulIndex = $('ul.tabs').index($(this).parents('ul.tabs'));
		// закоментировать две строчки что бы не сохранялаь последняя открытая вкладка при перезагрузке страницы
		//localStorage.removeItem('tab'+ulIndex);
		//localStorage.setItem('tab'+ulIndex, $(this).index());
	})

})

})(jQuery)

// кнопка выбрать все checkbox
$(document).ready(function(){
var flag = true;
$("#checkbox_all").click(function() {
		$('input[type="checkbox"]').prop('checked', flag);
		flag = (flag)?false:true;
	});
});

// подгоняем высоту тело под sidebar
function AutoHeightContentWrapper()
{
	var footer_height = $('.main-footer').outerHeight() || 0;
	var sidebar_height = $(".main-sidebar").height() || 0;
    $(".content-wrapper").css('min-height', sidebar_height - footer_height);
}
$(document).ready(function() {
	AutoHeightContentWrapper();
});
$(window).resize(function() {
	AutoHeightContentWrapper();
});
// запускаем маску для форм
$(function() {
	$('input').inputmask();
});
// переключатель класса для sidebar
$(document).ready(function() {
  $("#menutoggle").click(function() {
    $("body").toggleClass("sidebar-collapse");
    $("body").toggleClass("sidebar-open");
  });
  
  // удаление элемента из заказа
  $('.del_tr_order_item').click(function(e) {
		e.preventDefault();
		$(this).closest('tr').remove()
		$.ajax({
			type: 'POST',					
			url: '/ajax/delitemorder/?id=' + $_GET('id'),								
			data: {order_product_id:$(this).data("id")},
		}) 
	})
  
});

//import catalog

/*function import_catalog(i){
$.ajax({
  url: '/ajax/import/?string='+i,
  type: 'GET',  
  success: function(data) {	
	console.log(data);
	var arr = JSON.parse(data);
	console.log(arr);
	addRow(i, arr.data[0].id, arr.data[0].name, arr.data[0].status);
    if (arr.data[0].status != "Завершено")
	{import_catalog(i + 1);}
	else
	{$('#result').append("Завершено");}
	document.getElementById( 'height-result' ).scrollTop = document.getElementById( 'height-result' ).clientHeight;
  }
});

}*/

function import_catalog(i){
$.ajax({
  url: '/ajax/import/?string='+i,
  type: 'GET',  
  success: function(data) {
    /*$('#result').append(document.getElementById( 'height-result' ).clientHeight);
    $('#result').append("<br>");*/
	
	//console.log(data);
	var arr = JSON.parse(data);
	//console.log(arr);
	var j = (i-1)*60 + 1;
	arr.data.map(function(arr) {		
	  addRow(document.getElementById('import').getElementsByTagName('TBODY')[0], j, arr.id, arr.name, arr.status);
	  j++;
	});
    if (arr.end != "Завершено")
	{import_catalog(i+1);}
	else
	{$('#result').append("Завершено");}
	//document.getElementById( 'height-result' ).scrollTop = document.getElementById( 'height-result' ).clientHeight;
  }
});

}

function import_product(i){
$.ajax({
  url: '/ajax/importproduct/?string='+i,
  type: 'GET',  
  success: function(data) {
    /*$('#result').append(document.getElementById( 'height-result' ).clientHeight);
    $('#result').append("<br>");*/
	
	//console.log(data);
	var arr = JSON.parse(data);
	//console.log(arr);
	var j = (i-1)*600 + 1;
	arr.data.map(function(arr) {		
	  addRow(document.getElementById('import-product').getElementsByTagName('TBODY')[0], j, arr.sku, arr.name, arr.status);
	  j++;
	});
    if (arr.end != "Завершено")
	{import_product(i+1);}
	else
	{$('#result-product').append("Завершено");}
	//document.getElementById( 'height-result' ).scrollTop = document.getElementById( 'height-result' ).clientHeight;
  }
});

}



function addRow(tbody, number, id, name, status)
{
    // Находим нужную таблицу
    //var tbody = document.getElementById('import').getElementsByTagName('TBODY')[0];

    // Создаем строку таблицы и добавляем ее
    var row = document.createElement("TR");
    tbody.appendChild(row);

    // Создаем ячейки в вышесозданной строке
    // и добавляем тх
    var td1 = document.createElement("TD");
    var td2 = document.createElement("TD");
    var td3 = document.createElement("TD");
    var td4 = document.createElement("TD");

    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);

    // Наполняем ячейки
    td1.innerHTML = number;
    td2.innerHTML = id;
    td3.innerHTML = name;
    td4.innerHTML = status;
}