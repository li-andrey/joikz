var DOM = (typeof(document.getElementById) != 'undefined');
var main_check=0;

function Colorize(Element, CHBElement)
{
	if (Element && CHBElement) {
		var oldClassName = Element.className;
		Element.className = (CHBElement.checked ? 'selectStr' : 'noSelect');
		if (oldClassName.indexOf("noread")!=-1) Element.className = Element.className + ' noread';
	}
}

function CheckTR(Element)
{
	if (DOM) 
	{
	thisCheckbox = document.getElementById(Element.id.replace('str','chb'));
	thisCheckbox.checked = !thisCheckbox.checked;
	Colorize(Element, thisCheckbox);
	}
}

function CheckAll()
{
	for (var i=0;i<document.form_index.elements.length;i++)
	{
	var e = document.form_index.elements[i];
	if (e.name != "allbox")
	e.checked = document.form_index.allbox.checked;
	}
}

function view_elements(id_elem)
{
	var div =document.getElementById('filter_'+id_elem);
	if(div.style.display=='none')
	{
	document.cookie = 'filter' + "=" + escape('filter_'+id_elem);
	div.style.display='block';
	}else
	{
	div.style.display='none';
	}
}
function stopMoving() { var wasMoving = (isMoved == 2); isMoved = 0; return wasMoving; }
function otherClicks(evt) { if (evt.shiftKey || (evt.button == 2)) return true; }
function startMoving() { isMoved = 1; }


function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function SectionClick(id)
{	
	var div = document.getElementById(id);
	if(div.style.display=='none')
	{
	div.style.display='block';
	}else
	{
	div.style.display='none';
	}
	
}


// фунция определения "рабочего пространства"
function windowWorkSize(){
var wwSize = new Array();
	if (window.innerHeight !== undefined) wwSize= [window.innerWidth,window.innerHeight]    // для основных браузеров
		else	
			{	// для "особо одарённых" (ИЕ6-8)
				wwSizeIE = (document.body.clientWidth) ? document.body : document.documentElement; 
				wwSize= [$(window).width(), $(window).height()];
			};
			
	return wwSize;
};


$(document).ready(function(){
   //align element in the middle of the screen
  $.fn.alignCenter = function() {
  		var	wsize = windowWorkSize(),                                               // размеры "рабочей области"
		testElem = $(this),                        									// ложим наш блок в переменную
		testElemWid =  testElem.outerWidth(),                                    	// ширина блока
		testElemHei =  testElem.outerHeight();                                   	// высота блока
	
		var ll=wsize[0]/2 - testElemWid/2 + "px";
		var hh=wsize[1]/2 - testElemHei/2 + (document.body.scrollTop || document.documentElement.scrollTop) + "px";
	
	    return $(this).css({'left':ll, 'top':hh});
  };

});



function show_w() {
	if($('#window').css('display') == 'none') {
		$('#window').css({"margin-left":"0","margin-top":"0"});
		$('#window').alignCenter();
		$('#window').show();
		if($.browser.msie){
			$('#opaco').height($(document).height()).toggleClass('hidden');
		}else{
			$('#opaco').height($(document).height()).toggleClass('hidden').fadeTo('slow', 0.7);
   		}
	}
	return false;
}


function show_w1() {
	if($('#window1').css('display') == 'none') {
		$('#window1').css({"left":"0","top":"0","margin-left":"0","margin-top":"0"});
		$('#window1').alignCenter();
		$('#window1').show();
		if($.browser.msie){
			$('#opaco1').height($(document).height()).toggleClass('hidden');
		}else{
     		$('#opaco1').height($(document).height()).toggleClass('hidden').fadeTo('slow', 0.7);
 		}
	}
	return false;
}

$(document).ready(function(){
	
	$('#windowClose').bind('click',	function(){
		$('#window').TransferTo({
			to:'windowOpen',
			className:'transferer2', 
			duration: 400
		}).hide();
		$('#opaco').toggleClass('hidden').removeAttr('style');
	});
		
	$('#windowMin').bind('click',function(){
		$('#windowContent').SlideToggleUp(300);
		$('#windowBottom, #windowBottomContent').animate({height: 10}, 300);
		$('#window').animate({height:40},300).get(0).isMinimized = true;
		$(this).hide();
		$('#windowResize').hide();
		$('#windowMax').show();
	});
	
	$('#windowMax').bind('click',function(){
		var windowSize = $.iUtil.getSize(document.getElementById('windowContent'));
		$('#windowContent').SlideToggleUp(300);
		$('#windowBottom, #windowBottomContent').animate({height: windowSize.hb + 13}, 300);
		$('#window').animate({height:windowSize.hb+43}, 300).get(0).isMinimized = false;
		$(this).hide();
		$('#windowMin, #windowResize').show();
	});
	
	
	
});


function save_param(name, type, id){
	if (type=='i1')
	{
		var value =	$('#'+name).val();
	}
	else
	{
		
		if (document.getElementById(''+name+'').checked) var value=1;
		else var value=0;
		
	}
	
	$.ajax({
		url: "/admin/modules/inf_block/ajax.php",
		data: "do=add_param&id="+id+"&value="+value+"&x=secure",
		type: "POST",
		dataType : "html",
		cache: false,
		success:  function(data)  { 
			
		},
		error: function(){ alert("Ошибка выбора.");  }
	});	
	
}

