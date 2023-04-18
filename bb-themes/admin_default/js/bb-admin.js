var bb = {
    post: function (url, params, jsonp, onError) {
        $.ajax({
            type: "POST",
            url: bb.restUrl(url),
            data: params,
            dataType: 'json',
            error: function(jqXHR, textStatus, e) {
                bb.msg(e, 'error');
                if (onError !== undefined) {
                    onError();
                }
            },
            success: function(data) {
                if(data.error) {
                    bb.msg(data.error.message, 'error');
                    if (onError !== undefined) {
                        onError();
                    }
                } else {
                    if(typeof jsonp === 'function') {
                        return jsonp(data.result);
                    } else if(window.hasOwnProperty('console')) {
                        console.log(data.result);
                    }
                }
            }
        });
    },
    get: function (url, params, jsonp, onError) {
        $.ajax({
            type: "GET",
            url: bb.restUrl(url),
            data: params,
            dataType: 'json',
            error: function(jqXHR, textStatus, e) {
                bb.msg(e, 'error');
                if (onError !== undefined) {
                    onError();
                }
            },
            success: function(data) {
                if(data.error) {
                    if (onError !== undefined) {
                        onError();
                    }
                    bb.msg(data.error.message, 'error');
                    
                } else {
                    if(typeof jsonp === 'function') {
                        return jsonp(data.result);
                    } else if(window.hasOwnProperty('console')) {
                        console.log(data.result);
                    }
                }
            }
        });
    },
    restUrl: function(url) {
        if(url.indexOf('http://') > -1 || url.indexOf('https://') > -1) {
            return url;
        }
        return $('meta[property="bb:url"]').attr("content") + 'index.php?_url=/api/' + url;
    },
    error: function(txt, code) {
        $("#modalerror").modal('show');
        $("#modalerror .modal-body p").html(txt);
        $("#modalerror h4.tx-danger").html( 'Error code: '+code);
       // jAlert(txt, 'Error code: '+code);
    },
    msg: function(txt, type) {
        if(type=='error'){
            $("#modalerror").modal('show');
            $("#modalerror .modal-body p").html(txt);
            $("#modalerror h4.tx-danger").html( type);
        }else{
            $("#modalsuccess").modal('show');
            $("#modalsuccess .modal-body p").html(txt);
            $("#modalsuccess h4.tx-success").html( type);
        }

        //jAlert(txt, type);
    },
    redirect: function(url) {
        if(url === undefined) {
            this.reload();

        }
        window.location = url;
    },
    reload: function() {
        window.location.reload(true);
    },
    load: function(url, params) {
        var r = '';
        $.ajax({
            url: url,
            data: params,
            type: "GET",
            success: function(data){ r = data; },
            async: false
        });
        return r;
    },
    _afterComplete: function(obj, result) {
        var jsonp = obj.attr('data-api-jsonp');
        if(jsonp !== undefined && window.hasOwnProperty(jsonp)) {
            return window[jsonp](result);
        }
        if(obj.hasClass('bb-rm-tr')) {
            obj.closest('tr').addClass('highlight').fadeOut();
            return;
        }
        if(obj.attr('data-api-reload') !== undefined) {
            this.reload();
            return;
        }
        if(obj.attr('data-api-redirect') !== undefined) {
            return this.redirect(obj.attr('data-api-redirect'));
        }
        if(obj.attr('data-api-msg') !== undefined) {
            this.msg(obj.attr('data-api-msg'), 'success');
            return ;
        }
        if(result) {
            this.msg('Form updated', 'success');
            return ;
        }
    },
    apiForm: function() {
        $('form.api-form').bind('submit', function(){
            var obj = $(this);
            let btnSubmit = $(this).find('.btn-form-submit');
            let btnSubmitIcon = null;
            if (btnSubmit !== undefined) {
                btnSubmitIcon = $(btnSubmit).find('i');
                if (btnSubmitIcon !== undefined) {
                    bb.btnLoader(btnSubmit, btnSubmitIcon);
                }
            }
            bb.post(obj.attr('action'), obj.serialize(), 
            function(result) {
                if (btnSubmitIcon !== undefined) {
                    bb.btnLoader(btnSubmit, btnSubmitIcon, true);
                }
                return bb._afterComplete(obj, result);
            }, function(){
                if (btnSubmitIcon !== undefined) {
                    bb.btnLoader(btnSubmit, btnSubmitIcon, true);
                }
            });
            return false;
        });
    },
    btnLoader: function (btnSubmit, btnSubmitIcon, status) {
        if (status === undefined) {
            btnSubmit.prop('disabled', true).addClass('disabled');
            btnSubmitIcon.data('last-icon', btnSubmitIcon.attr('class'));
            btnSubmitIcon.removeClass().addClass('fa fa-spinner fa-spin');
        } else {
            btnSubmit.prop('disabled', false).removeClass('disabled');
            btnSubmitIcon.removeClass().addClass(btnSubmitIcon.data('last-icon'));
        }
    },
    apiLink: function() {
        $("a.api-link").bind('click', function(){
            var obj = $(this);
            if(obj.attr('data-api-confirm') !== undefined) {
                jConfirm(obj.attr('data-api-confirm'), 'Confirm your action', function(r) {
                    let btnIco = null;
                    btnIco = obj.find('i');
                    bb.btnLoader(obj, btnIco);
                    if(r) bb.get( obj.attr('href'), {}, function(result) {
                        if (btnIco !== undefined) {
                            bb.btnLoader(obj, btnIco, true);
                        }
                        return bb._afterComplete(obj, result);
                    } );
                });
            } else if(obj.attr('data-api-prompt') !== undefined) {
                jPrompt(obj.attr('data-api-prompt-text'), obj.attr('data-api-prompt-default'), obj.attr('data-api-prompt-title'), function(r) {
                    if(r) {
                        var p = {};
                        var name = obj.attr('data-api-prompt-key');
                        p[name] = r;
                        bb.get( obj.attr('href'), p, function(result) {return bb._afterComplete(obj, result);} );
                    }
                });
            } else {
                bb.get( obj.attr('href'), {}, function(result) {return bb._afterComplete(obj, result);} );
            }
            return false;
        });
    },
    menuAutoActive: function() {
        var matches = $('ul#menu li a').filter(function() {
            return document.location.href == this.href;
        });
        matches.parents('li').addClass('active');
    },
    cookieCreate: function (name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    },
    cookieRead: function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    },
    insertToTextarea: function(areaId, text){
        var txtarea = document.getElementById(areaId);
        var scrollPos = txtarea.scrollTop;
        var strPos = 0;
        var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
            "ff" : (document.selection ? "ie" : false ) );
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            strPos = range.text.length;
        }
        else if (br == "ff") strPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0,strPos);
        var back = (txtarea.value).substring(strPos,txtarea.value.length);
        txtarea.value=front+text+back;
        strPos = strPos + text.length;
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            range.moveStart ('character', strPos);
            range.moveEnd ('character', 0);
            range.select();
        }
        else if (br == "ff") {
            txtarea.selectionStart = strPos;
            txtarea.selectionEnd = strPos;
            txtarea.focus();
        }
        txtarea.scrollTop = scrollPos;
        if ('undefined' !== typeof CKEDITOR ){
            CKEDITOR.instances[areaId].insertText(text);
        }

        return false
    }
}

//===== Left navigation submenu animation =====//

$("ul.sub li a").hover(function() {
$(this).stop().animate({color: "#3a6fa5"}, 400);
},function() {
$(this).stop().animate({color: "#494949"}, 400);
});


//===== Tabs =====//
$.fn.simpleTabs = function(){

    //Default Action
    $(this).find(".tab_content").hide(); //Hide all content
    $(this).find("ul.tabs li:first").addClass("activeTab").show(); //Activate first tab
    $(this).find(".tab_content:first").show(); //Show first tab content

    //On Click Event
    $("ul.tabs li").click(function() {
        $(this).parent().parent().find("ul.tabs li").removeClass("activeTab"); //Remove any "active" class
        $(this).addClass("activeTab"); //Add "active" class to selected tab
        $(this).parent().parent().find(".tab_content").hide(); //Hide all tab content
        var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        $(activeTab).show(); //Fade in the active content
        //document.location.hash = activeTab;
        return false;
    });

    // select active tab
    if($(document.location.hash).length) {
        $('a[href="'+document.location.hash+'"]').parent().click();
        $(window).scrollTo(window.location.href.indexOf('#'))
    }

};//end function

$(function() {

	//===== Global ajax methods =====//
    $('.loading').ajaxStart(function() {
        $(this).show();
    }).ajaxStop(function() {
        $(this).hide();
    });

	//===== Api forms and links =====//
    if($("form.api-form").length){bb.apiForm();}
    if($("a.api-link").length){bb.apiLink();}
    //if($("ul#menu").length){bb.menuAutoActive();}

   // $().UItoTop();

    /*//===== Datepickers =====//
	$( ".datepicker" ).datepicker({
		defaultDate: +7,
		autoSize: true,
		//appendText: '(yyyy-mm-dd)',
		dateFormat: 'yy-mm-dd'
	});

	//===== Tooltip =====//

	$('.leftDir').tipsy({fade: true, gravity: 'e'});
	$('.rightDir').tipsy({fade: true, gravity: 'w'});
	$('.topDir').tipsy({fade: true, gravity: 's'});
	$('.botDir').tipsy({fade: true, gravity: 'n'});
	//===== Form elements styling =====//
    $(".mainForm select, .mainForm input:checkbox, .mainForm input:radio, .mainForm input:file").uniform();

	//===== Collapsible elements management =====//
	$('.exp').collapsible({
		defaultOpen: 'current',
		cookieName: 'navAct',
		cssOpen: 'active',
		cssClose: 'inactive',
		speed: 300
	});

    $("div.simpleTabs").simpleTabs();

*/
	$('.dd').click(function () {
		$('ul.menu_body', this).slideToggle(100);
	});



    $(document).delegate('div.msg span.close', 'click', function() {
        $(this).parent().slideUp(70);
        return false;
    });

	//===== Information boxes =====//
	$(".hideit").click(function() {
		$(this).fadeOut(400);
	});

    $("select.language_selector").bind('change', function(){
        bb.cookieCreate('BBLANG', $(this).val(), 7);
        bb.reload();
        return false;
    }).val(bb.cookieRead('BBLANG'));


   // $('#generalSearch').autocomplete({
    $('#generalSearch').keyup(function(){
        var canti=$("#generalSearch").val();
        if(canti.length >=3){
            // source: function( request, response ) {
            $.ajax({
                url: bb.restUrl('admin/client/get_pairs'),
                dataType: "json",
                data: {
                    per_page: 10,
                    search: $("#generalSearch").val()
                },
                success: function( data ) {
                    $("#SearchFullCliente").html('');
                    var cantidadCliente=0;
                    var contentCliente='';
                    var itemRes='';

                    $.map( data.result, function( name, id) {
                        itemRes+='<a class="d-flex mx-3 p-2 border-bottom" href="/bb-admin/client/manage/'+id+'">' +
                            '<div class="tx-gray-500 ">' +
                            '<i class="fe fe-user"></i>' +
                            '</div>' +
                            '<div class="ms-3">' +
                            '<h6 class="tx-gray-600 mb-1">'+name.full_name+'</h6>' +
                            '<div class="notification-subtext">'+name.email+'</div>' +
                            '</div>' +
                            '<div class="ms-auto tx-gray-500 ">' +
                            '<i class="fe fe-chevron-right"></i>' +
                            '</div>' +
                            '</a>'

                        /*  return {
                              label: name,
                              value: id,
                              info:'/bb-admin/client/manage/'+id
                          }*/
                        cantidadCliente++;
                    });
                    if(cantidadCliente>0) {
                        contentCliente = '<div class="p-1 bg-primary text-white  text-start border-bottom"> Clients ( ' + cantidadCliente + ' )</div><div style="max-height: 180px;overflow-y: auto">';
                        $("#SearchFullCliente").html(contentCliente + itemRes+'</div>');
                    }
                }
            });

            $.ajax({
                url: bb.restUrl('admin/product/get_pairs'),
                dataType: "json",
                data: {
                    per_page: 10,
                    search: $("#generalSearch").val()
                },
                success: function( data ) {
                    $("#SearchFullProd").html('');
                    var cantidadCliente=0;
                    var contentCliente='';
                    var itemRes='';

                    $.map( data.result, function( name, id) {
                        itemRes+='<a class="d-flex mx-3 p-2 border-bottom" href="/bb-admin/product/manage/'+id+'">' +
                            '<div class="tx-gray-500 ">' +
                            '<i class="fe fe-server"></i>' +
                            '</div>' +
                            '<div class="ms-3">' +
                            '<h6 class="tx-gray-600 mb-1">'+name+'</h6>' +
                            '</div>' +
                            '<div class="ms-auto tx-gray-500 ">' +
                            '<i class="fe fe-chevron-right"></i>' +
                            '</div>' +
                            '</a>';

                        /*  return {
                              label: name,
                              value: id,
                              info:'/bb-admin/client/manage/'+id
                          }*/
                        cantidadCliente++;
                    });
                    if(cantidadCliente>0){
                        contentCliente='<div class="p-1 bg-primary text-white  text-start border-bottom"> Products / Services ( ' + cantidadCliente + ' )</div><div style="max-height: 180px;overflow-y: auto">';
                        $("#SearchFullProd").html(contentCliente+itemRes+'</div>');
                    }
                }
            });
            $("#SearchFull").slideDown('fast');
            $.ajax({
                url: bb.restUrl('admin/invoice/get_pairs'),
                dataType: "json",
                data: {
                    per_page: 10,
                    search: $("#generalSearch").val()
                },
                success: function( data ) {
                    $("#SearchFullInvoce").html('');
                    var cantidadCliente=0;
                    var contentCliente='';
                    var itemRes='';

                    $.map( data.result, function( name, id) {
                        var coloStatus=(name.status == 'paid')?'bg-success-transparent text-success':'bg-danger-transparent text-danger';
                        var nombrest=(name.status == 'paid')?'Paid':'Unpaid';
                        itemRes+='<a class="d-flex mx-3 p-2 border-bottom" href="/bb-admin/invoice/manage/'+id+'">' +
                            '<div class="tx-gray-500 ">' +
                            '<i class="fe fe-tag"></i>' +
                            '</div>' +
                            '<div class="ms-3"  style="width: 50%">' +
                            '<h6 class="tx-gray-600 mb-1">#'+id+'</h6>' +
                            '<div class="notification-subtext">'+name.full_name+'</div>' +
                            '</div>' +
                            '<div class="ms-auto text-end tx-gray-500 ">' +
                            '<span class="badge '+coloStatus+'">'+nombrest+'</span>' +
                            '</div>' +
                            '<div class="ms-auto tx-gray-500 ">' +
                            '<i class="fe fe-chevron-right"></i>' +
                            '</div>' +
                            '</a>';
                        cantidadCliente++;
                    });
                    if(cantidadCliente>0){
                        contentCliente='<div class="p-1 bg-primary text-white  text-start border-bottom"> Invoices ( ' + cantidadCliente + ' )</div><div style="max-height: 180px;overflow-y: auto">';
                        $("#SearchFullInvoce").html(contentCliente+itemRes+'</div>');
                    }
                }
            });
            $.ajax({
                url: bb.restUrl('admin/order/get_pairs'),
                dataType: "json",
                data: {
                    per_page: 10,
                    search: $("#generalSearch").val()
                },
                success: function( data ) {
                    $("#SearchFullOrder").html('');
                    var cantidadCliente = 0;
                    var contentCliente = '';
                    var itemRes = '';

                    $.map(data.result, function (name, id) {
                        if(name.status == 'active'){
                            var coloStatus ='bg-success-transparent text-success';
                            var nombrest='Active';
                        }else if(name.status == 'suspended'){
                            var coloStatus ='bg-danger-transparent text-danger';
                            var nombrest='Suspended';
                        }else{
                            var coloStatus ='bg-warning-transparent text-warning';
                            var nombrest='Pending Setup';
                        }

                        itemRes += '<a class="d-flex mx-3 p-2 border-bottom" href="/bb-admin/order/manage/' + id + '">' +
                            '<div class="tx-gray-500 ">' +
                            '<i class="fe fe-tag"></i>' +
                            '</div>' +
                            '<div class="ms-3" style="width: 50%">' +
                            '<h6 class="tx-gray-600 mb-1">#' + id + '</h6>' +
                            '<div class="notification-subtext">' + name.full_name + '</div>' +
                            '</div>' +
                            '<div class="ms-auto text-end tx-gray-500 ">' +
                            '<span class="badge ' + coloStatus + '">' + nombrest + '</span>' +
                            '</div>' +
                            '<div class="ms-auto tx-gray-500 ">' +
                            '<i class="fe fe-chevron-right"></i>' +
                            '</div>' +
                            '</a>';
                        cantidadCliente++;
                    });
                    if (cantidadCliente > 0) {
                        contentCliente = '<div class="p-1 bg-primary text-white  text-start border-bottom"> Orders ( ' + cantidadCliente + ' )</div><div style="max-height: 180px;overflow-y: auto">';
                        $("#SearchFullOrder").html(contentCliente + itemRes+'</div>');
                    }
                }
            });
            /* },
             minLength: 1,
             select: function( event, ui ) {
                 $("#generalSearch").val(ui.item.label);
                 window.location.href=ui.item.info;
                 return false;
             }*/

        }

    });
$("body").click(function(){
   $("#SearchFull").hide();
});
});
