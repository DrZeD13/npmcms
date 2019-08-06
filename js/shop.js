$( document ).ready(function( $ ) {
		//top product slider
	var product_slider = $('#product-slider');
	if(product_slider.length == 1) {
		product_slider.owlCarousel({
			items: 1,
			nav: true,
			navText: false,
			dots: true, 
			smartSpeed: 1200,
			autoplay: true,
			autoplayHoverPause: true,
			loop: true,		

			singleItem: true,
			slideSpeed: 1300,
			paginationSpeed: 1400,
			
			onInitialized: afterOWLinit, // do some work after OWL init
			afterUpdate: afterOWLinit // do some work after Update
		});
	} 
	
    function afterOWLinit() {

        // adding A to div.owl-page
        $('.owl-controls .owl-page').append('<a class="item-link" href="#"/>');
        $('#product-slider .owl-pagination a').click(function() {
        $('#product-slider').trigger('slideTo', '#' + this.href.split('#').pop() );
         return false;
        });		

        var pafinatorsLink = $('.owl-controls .owl-dots .owl-dot');     
        $.each(this._items, function (i) {
			console.log(pafinatorsLink[i]);
            $(pafinatorsLink[i])
                // i - counter
                // Give some styles and set background image for pagination item
                .css({
                    'background': 'url(' + $(this).find('img').attr('src') + ') center center no-repeat',
                    '-webkit-background-size': 'cover',
                    '-moz-background-size': 'cover',
                    '-o-background-size': 'cover',
                    'background-size': 'cover'
                })
        });         

    };
});

// typeof $.fn.ajaxForm == 'function' || document.write('<script src="'+miniShop2Config.jsUrl+'lib/jquery.form.min.js"><\/script>');
typeof $.fn.jGrowl == 'function' || document.write('<script src="/js/jquery.jgrowl.min.js"><\/script>');

(function(window, document, $, undefined) {
	miniShop2 = {};
miniShop2Config = {
	actionUrl: "/cart/actions/"//"send.php"
	,ctx: "web"
	,close_all_message: "закрыть все"
	,price_format: [2, ".", " "]
	,price_format_no_zeros: 1
	,weight_format: [3, ".", " "]
	,weight_format_no_zeros: 1
	,callbacksObjectTemplate: function() {
		return {
			before: function() {/*return false to prevent send data*/}
			,response: {success: function(response) {},error: function(response) {}}
			,ajax: {done: function(xhr) {},fail: function(xhr) {},always: function(xhr) {}}
		};
	}
};
miniShop2.Callbacks = miniShop2Config.Callbacks = {
	Cart: {
		add: miniShop2Config.callbacksObjectTemplate()
		,remove: miniShop2Config.callbacksObjectTemplate()
		,change: miniShop2Config.callbacksObjectTemplate()
		,clean: miniShop2Config.callbacksObjectTemplate()
	}
};
	
	
	
	miniShop2.ajaxProgress = false;
	miniShop2.setup = function() {
		// selectors & $objects
		this.actionName = 'addCart';
		this.action = ':submit[name=' + this.actionName + ']';
		this.form = '.form_shop_el';
		this.$doc = $(document);

		this.sendData = {
			$form: null,
			action: null,
			formData: null
		};
	};
	miniShop2.initialize = function() {
		miniShop2.setup();
		// Indicator of active ajax request

		miniShop2.$doc
			.ajaxStart(function() {
				miniShop2.ajaxProgress = true;
			})
			.ajaxStop(function() {
				miniShop2.ajaxProgress = false;
			})
			.on('submit', miniShop2.form, function(e) {
				e.preventDefault();
				var $form = $(this);
				var action = $form.find(miniShop2.action).val();

				if (action) {
					var formData = $form.serializeArray();
					formData.push({
						name: miniShop2.actionName,
						value: action
					});
					miniShop2.sendData = {
						$form: $form,
						action: action,
						formData: formData
					};
					miniShop2.controller();
				}
			})
		miniShop2.Cart.initialize();
		miniShop2.Message.initialize();
	}
	miniShop2.controller = function() {
		var self = this;
		switch (self.sendData.action) {
			case 'cart/add':
				miniShop2.Cart.add();
				break;
			case 'cart/remove':
				miniShop2.Cart.remove();
				break;
			case 'cart/change':
				miniShop2.Cart.change();
				break;
			case 'cart/clean':
				miniShop2.Cart.clean();
				break;
			default:
				return;
		}
	};
	miniShop2.send = function(data, callbacks, userCallbacks) {
		var runCallback = function(callback, bind) {
			if (typeof callback == 'function') {
				return callback.apply(bind, Array.prototype.slice.call(arguments, 2));
			}
			else {
				return true;
			}
		}
		// send
		var xhr = function(callbacks, userCallbacks) {	
			$.ajax({
					type: "POST",
					url: "/cart/actions/",
					data: data,
					success: function(response) {
						var resj= JSON.parse(response);
						if (resj["success"])
						{
							miniShop2.Message.success(resj["message"]);

							runCallback(callbacks.response.success, miniShop2, resj);
							runCallback(userCallbacks.response.success, miniShop2, resj);
						}
						else
						{
							miniShop2.Message.error(resj["message"]);
							runCallback(callbacks.response.error, miniShop2, resj);
							runCallback(userCallbacks.response.error, miniShop2, resj);
						}
					},
					error: function(){
						miniShop2.Message.success("Ошибка");
					}
				});
		}(callbacks, userCallbacks);
	};

	miniShop2.Cart = {
		callbacks: {
			add: miniShop2Config.callbacksObjectTemplate(), remove: miniShop2Config.callbacksObjectTemplate(), change: miniShop2Config.callbacksObjectTemplate(), clean: miniShop2Config.callbacksObjectTemplate()
		}
		,setup: function() {
			miniShop2.Cart.cart = '#msCart';
			miniShop2.Cart.miniCart = '#msMiniCart';
			miniShop2.Cart.miniCartNotEmptyClass = 'full';
			miniShop2.Cart.countInput = 'input[name=count]';
			miniShop2.Cart.totalWeight = '.ms2_total_weight';
			miniShop2.Cart.totalCount = '.ms2_total_count';
			miniShop2.Cart.totalCost = '.ms2_total_cost';
		}
		,initialize: function() {
			miniShop2.Cart.setup();
			if (!$(miniShop2.Cart.cart).length) return;
			miniShop2.$doc.on('change', miniShop2.Cart.cart + ' ' + miniShop2.Cart.countInput, function() {
					$(this).closest(miniShop2.form).submit();
				});
		}
		,add: function() {
			var callbacks = miniShop2.Cart.callbacks;	
			callbacks.add.response.success = function(response) {				
				this.Cart.status(response.data);
			}
			miniShop2.send(miniShop2.sendData.formData, miniShop2.Cart.callbacks.add, miniShop2.Callbacks.Cart.add);
		}
		,remove: function() {
			var callbacks = miniShop2.Cart.callbacks;
			callbacks.remove.response.success = function(response) {
				this.Cart.remove_position(miniShop2.Utils.getValueFromSerializedArray('key'));
				this.Cart.status(response.data);
			}
			miniShop2.send(miniShop2.sendData.formData, miniShop2.Cart.callbacks.remove, miniShop2.Callbacks.Cart.remove);
		}
		,change: function() {
			var callbacks = miniShop2.Cart.callbacks;
			callbacks.change.response.success = function(response) {
				if (typeof(response.data.key) == 'undefined') {
					this.Cart.remove_position(miniShop2.Utils.getValueFromSerializedArray('key'));
				}
				else {
					$('#' + miniShop2.Utils.getValueFromSerializedArray('key')).find('');
				}
				this.Cart.status(response.data);
			}
			miniShop2.send(miniShop2.sendData.formData, miniShop2.Cart.callbacks.change, miniShop2.Callbacks.Cart.change);
		}
		,status: function(status) {
			if (status['total_count'] < 1) {
				location.reload();
			}
			else {
				var $cart = $(miniShop2.Cart.cart);
				var $miniCart = $(miniShop2.Cart.miniCart);
				if (status['total_count'] > 0 && !$miniCart.hasClass(miniShop2.Cart.miniCartNotEmptyClass)) {
					$miniCart.addClass(miniShop2.Cart.miniCartNotEmptyClass);
				}
				$(miniShop2.Cart.totalWeight).text(miniShop2.Utils.formatWeight(status['total_weight']));
				$(miniShop2.Cart.totalCount).text(status['total_count']);
				$(miniShop2.Cart.totalCost).text(miniShop2.Utils.formatPrice(status['total_cost']));
				$("#cart-count-text").text(miniShop2.Utils.pluralForm(status['total_count'], "товар", "товара", "товаров"));
				$("#cart-cost-text").html("на сумму " + miniShop2.Utils.formatPrice(status['total_cost']) + ' <span class="rur">₽</span>');				
			}
		}
		,clean: function() {
			var callbacks = miniShop2.Cart.callbacks;
			callbacks.clean.response.success = function(response) {
				this.Cart.status(response.data);
			}

			miniShop2.send(miniShop2.sendData.formData, miniShop2.Cart.callbacks.clean, miniShop2.Callbacks.Cart.clean);
		}
		,remove_position: function(key) {
			$('#' + key).remove();
		}
	};

	miniShop2.Message = {
		initialize: function() {
			if (typeof $.fn.jGrowl != 'undefined') {
				$.jGrowl.defaults.closerTemplate = '<div>[ ' + miniShop2Config.close_all_message + ' ]</div>';
				miniShop2.Message.close = function() {
					$.jGrowl('close');
				}
				miniShop2.Message.show = function(message, options) {
					if (!message) return;
					$.jGrowl(message, options);
				}
			}
			else {
				miniShop2.Message.close = function() {};
				miniShop2.Message.show = function(message) {
					if (message) {
						alert(message);
					}
				};
			}
		}
		,success: function(message) {
			miniShop2.Message.show(message, {
				theme: 'ms2-message-success',
				sticky: false
			});
		}
		,error: function(message) {
			miniShop2.Message.show(message, {
				theme: 'ms2-message-error',
				sticky: false
			});
		}
		,info: function(message) {
			miniShop2.Message.show(message, {
				theme: 'ms2-message-info',
				sticky: false
			});
		}
	};

	miniShop2.Utils = {
		empty: function(val) {
			return (typeof(val) == 'undefined' || val == 0 || val === null || val === false || (typeof(val) == 'string' && val.replace(/\s+/g, '') == '') || (typeof(val) == 'array' && val.length == 0));
		}, formatPrice: function(price) {
			var pf = miniShop2Config.price_format;
			price = this.number_format(price, pf[0], pf[1], pf[2]);

			if (miniShop2Config.price_format_no_zeros) {
				price = price.replace(/(0+)$/, '');
				price = price.replace(/[^0-9]$/, '');
			}

			return price;
		}, formatWeight: function(weight) {
			var wf = miniShop2Config.weight_format;
			weight = this.number_format(weight, wf[0], wf[1], wf[2]);

			if (miniShop2Config.weight_format_no_zeros) {
				weight = weight.replace(/(0+)$/, '');
				weight = weight.replace(/[^0-9]$/, '');
			}

			return weight;
		}
		// Format a number with grouped thousands
		, number_format: function(number, decimals, dec_point, thousands_sep) {
			// original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
			// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
			// bugfix by: Michael White (http://crestidg.com)
			var i, j, kw, kd, km;

			// input sanitation & defaults
			if (isNaN(decimals = Math.abs(decimals))) {
				decimals = 2;
			}
			if (dec_point == undefined) {
				dec_point = ",";
			}
			if (thousands_sep == undefined) {
				thousands_sep = ".";
			}

			i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

			if ((j = i.length) > 3) {
				j = j % 3;
			}
			else {
				j = 0;
			}

			km = (j
				? i.substr(0, j) + thousands_sep
				: "");
			kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
			kd = (decimals
				? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, '0').slice(2)
				: '');

			return km + kw + kd;
		}
		,getValueFromSerializedArray: function(name, arr) {
			if (!$.isArray(arr)) {
				arr = miniShop2.sendData.formData;
			}
			for (var i = 0, length = arr.length; i < length; i++) {
				if (arr[i].name = name) {
					return arr[i].value;
				}
			}
			return null;
		}
		,pluralForm: function (n, form1, form2, form5) {
			n = Math.abs(n) % 100;
			n1 = n % 10;
			if (n > 10 && n < 20) return form5;
			if (n1 > 1 && n1 < 5) return form2;
			if (n1 == 1) return form1;
			return form5;
		}
	};

	$(document).ready(function($) {
		miniShop2.initialize();
		var html = $('html');
		html.removeClass('no-js');
		if (!html.hasClass('js')) {html.addClass('js');}
	});
})(this, document, jQuery);

/* global jQuery */
jQuery(document).ready(function ($) {
	/* global miniShop2 */

	'use strict';


	var countMin = 1;
	var countMax = 99;


	$('.j-plus').off('click').on('click', btnPlusClick);
	$('.j-minus').off('click').on('click', btnMinusClick);

	$('.j-result')
		.on('keydown', countKeydown)
		.on('change keyup', countChange);

	//$('button[value="cart/add"]').on('click', updateMiniCart);

	/**
	 * Обновление миникорзины
	 **/
	function updateMiniCart() {
		var form = $(this).parents('.form_shop_el:eq(0)');
		var id = form.find('input[name="id"]').val();
		var addCount = parseInt(form.find('input[name="count"]').val());

		// Элемент миникорзины
		var item = $('.j-minicart-item-' + id);

		// Элемент есть в Миникорзине, меняем количество
		if (item.length > 0) {
			var inpCount = item.find('input[name="count"]');
			inpCount.val(parseInt(inpCount.val()) + addCount);

			item.find('.j-cost').text(miniShop2.Utils.formatPrice(
				parseFloat(item.find('.j-price-by-item').text()) *
				parseInt(inpCount.val())
			));
		} else {
			// Элемента нет в Миникорзине, добавляем
			setTimeout(function () {

				/*$.get(
					'/connectors/ajax/',
					{},
					function (data) {

						$('.js-minicart-content').html(data);

						$('.j-plus')
							.off('click')
							.on('click', btnPlusClick);
						$('.j-minus')
							.off('click')
							.on('click', btnMinusClick);

						$('.j-result')
							.off('keydown change keyup')
							.on('keydown', countKeydown)
							.on('change keyup', countChange);
					}
				);*/

			}, 150);
		}
	}



	/**
	 * Нажатие на кнопку плюса
	 **/
	function btnPlusClick() {
		var form = $(this).parents('form:eq(0)');
		if (incrementInput(form.find('input[name="count"]'), +1)) {
			if ($(this).parents().is('.cart-table'))
				form.find('button[type="submit"]').click();
		}
	}

	/**
	 * Нажатие на кнопку минуса
	 **/
	function btnMinusClick() {
		var form = $(this).parents('form:eq(0)');
		if (incrementInput(form.find('input[name="count"]'), -1)) {
			if ($(this).parents().is('.cart-table'))
				form.find('button[type="submit"]').click();
		}
	}


	/**
	 * Нажатие клавиши клавиатуры в поле количества
	 */
	function countKeydown(event) {
		if (event.which === 38) {
			// Клавиша [Вверх]
			incrementInput($(this), +1);
		} else if (event.which === 40) {
			// Клавиша [Вниз]
			incrementInput($(this), -1);
		} else if (event.which === 13) {
			return false;
		}
	}

	/**
	 * Изменение значения количества
	 */
	function countChange() {
		var self = $(this);
		var value = $.trim(self.val());

		if (value > 0) {
			var item = self.parents('.j-minicart-item:eq(0)');
			item.find('.j-cost').text(miniShop2.Utils.formatPrice(
				parseFloat(item.find('.j-price-by-item').text().replace(/\s/g, '')) * parseInt(value)
			));

			item.find('#refresh').click();
		}
	}

	/**
	 * Увеличить значение инпута `input` на число `increment`.
	 * @param {jQuery} input
	 * @param {Number} increment
	 * @returns {boolean}
	 */
	function incrementInput(input, increment) {
		var val = (parseInt(input.val()) || 0) + increment;
		if (val >= countMin && val <= countMax) {
			input.val(val);
			var item = input.parents('.j-minicart-item:eq(0)');
			item.find('.j-cost').text(miniShop2.Utils.formatPrice(
				parseFloat(item.find('.j-price-by-item').text().replace(/\s/g, '')) * val
			));
			return true;
		}

		return false;
	}
	
	$(".radio").on( "click", function() {
		$('.additions input[type=checkbox]').removeAttr("checked");	
		//$('#price').html(miniShop2.Utils.formatPrice(+parseFloat($('#price').text().replace(/\s/g, '')) + +val));
		$('#price').html(miniShop2.Utils.formatPrice($(this).attr('data-price')));
	});

	
	
	/*убираем все выбранные чекбоксы*/
	$('.additions input[type=checkbox]').removeAttr("checked");

	$('.additions input[type=checkbox]').click(function(){
		var val = $(this).val();
		if ($(this).is(':checked'))
		{
			$('#price').html(miniShop2.Utils.formatPrice(+parseFloat($('#price').text().replace(/\s/g, '')) + +val));
		}
		else
		{
			$('#price').html(miniShop2.Utils.formatPrice(+parseFloat($('#price').text().replace(/\s/g, '')) - +val));
		}
	});
	
	var flag = false;
	function UpdateFitter()
	{	
		 $("#catalog-overlay").css('display', "block");
		 var string = "?";
		 $(".filter-block").each(function (index, value) {		
			var sq = "";
			 $('input[name^=\''+$(this).data("fitlerurl")+'\']:checked').each(function(element) {
				//console.log($(this).data("fitlervalue"));
				var param = String($(this).data("fitlervalue"));		
				//var paramNew = param.replace("/", "%2F"); 	
				//var str = "№24/6"
				//console.log(str); 
				//console.log(param.replace(/\//g,"%2F"));
				sq += param.replace(/\//g, "%2F")+";"; //%2F
			});
			if (sq.length > 0)
			{
				sq = $(this).data("fitlerurl") + "=" + sq.substring(0, sq.length - 1) + "&";
				string += sq;
			}
		 });
		string = document.location.protocol + "//" + document.location.host + document.location.pathname + string.substring(0, string.length - 1);	
		window.location.href = string;
	}
	
	$('.filter-block input[type=checkbox]').click(function(){		
		flag = true;
		setTimeout(UpdateFitter, 2000);
	});
	
	/*$(".filter-block").mouseout(function(){           
		// отвели курсор с объекта
		
	});*/
	
	$(".filter-block").hover(function(){             
                 // навели курсор на объект (не учитываются переходы внутри элемента)            
        },function(){           
                // отвели курсор с объекта (не учитываются переходы внутри элемента) 
				// если был выбран какой-то елемент фильтра
				if (flag)
					UpdateFitter();				
        });
	
	$("#filter_view").on( "click", function() {
		UpdateFitter();
	});
	
	$("#filter_clear").on( "click", function(e) {
		e.preventDefault();
		window.location.href = document.location.protocol + "//" + document.location.host + document.location.pathname + "?pg=0";		
	});
	
	// отправка заказа
	
	$("#form_order_ajax").submit(function(e) {
		e.preventDefault();
		var fb = new FormData( this );
		$.ajax({
			url: '/ajax/send_order',
			type: 'POST',
			contentType: false,
			processData: false,
			data: fb,
			success: function(res){
				console.log(res);
				var resj= JSON.parse(res);
				
				if (resj["success"]) {
					var string = document.location.protocol + "//" + document.location.host + "/login/orders";
					window.location.href = string;
				}
				else
				{
					$("#result_order").html(resj["message"]);
				}
			}
		});	
	})
	
	
	$(".order-readmore a").click(function(e) {
		e.preventDefault();
		// находим на одном уровне блок
		$(this).parent().siblings(".order-item").slideToggle('');		
		var text = $(this).text();
		$(this).text(text == "Скрыть заказ" ? "Посмотреть заказ" : "Скрыть заказ")
		
	})
	$('[name=phone], [name=tel]').mask('+7 (999) 999-99-99');
	$('#y_eqv_f').click(function(){
		var val = $(this).val();
		if ($(this).is(':checked'))
		{
			$("#faddress").hide();
		}
		else
		{
			$("#faddress").show();
		}
	});
	
	$('#buttonbik').click(function(e){
		e.preventDefault();
		//console.log($("[name=bik").val());
		var temp =/^\d{9}$/;
		if (temp.test($("[name=bik").val())==0)
		{
			$(this).siblings(".error").html("Введите кооректный БИК");			
		}
		else
		{
			$(this).siblings(".error").html("");				
			$.ajax({
				url: 'http://www.bik-info.ru/api.html?type=json&bik=' + $("[name=bik").val(),
				type: 'GET',
				success: function(res){
					//console.log(res);
					if (res["error"]) {
						$(this).siblings(".error").html("Введите кооректный БИК");
					}
					else
					{
						$("#bank").find("textarea").val(res["name"] + ", г. " + res["city"] + ", " + res["address"] +", к/с " + res["ks"])
					}
				}
			});
			
			
		}
	
	});
	
	
	// выпадашка личного кабинета
	function hideallDropdowns() {
        $(".dropped .drop-menu-main-sub").hide();
        $(".dropped").removeClass('dropped');
        $(".dropped .drop-menu-main-sub .title").unbind("click");
    }
 
    function showDropdown(el) {
        var el_li = $(el).parent().addClass('dropped');
        el_li
            .find('.title')
            .click(function () {
                hideallDropdowns();
            })
            .html($(el).html());
 
        el_li.find('.drop-menu-main-sub').show();
    }
 
    $(".drop-down").click(function(){
        showDropdown(this);
    });
 
    $(document).mouseup(function () {
        hideallDropdowns();
    });
	
});