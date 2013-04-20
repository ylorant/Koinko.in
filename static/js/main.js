koinkoin = {};

koinkoin.shorten = function(form)
{
	if($(form).find('input[type="text"]').val() != null && !$(form).find('button').hasClass('disabled'))
	{
		$(form).find('button').addClass('disabled');
		$(form).find('button').attr('disabled', 'disabled');
		
		$.ajax({
			type: "POST",
			url: BASE_URL + "shorten",
			data: "url=" + $(form).find('input[type="text"]').val(),
			dataType: "json"
		})
		.done(
			function(data)
			{
				if(typeof data.debug != "undefined")
					debug.addTab(data.debug);
				if(data.result == true)
				{
					$(form).find('div').fadeOut(function()
					{
						$(form).find('button').remove();
						$(form).find('div').removeClass('input-append');
						$(form).find('input[type="text"]').attr('onclick', 'koinkoin.selectContent(this);');
						$(form).find('input[type="text"]').attr('readonly', 'readonly');
						$(form).find('input[type="text"]').val(data.url);
						$(form).find('div').fadeIn();
					});
				}
				else
				{
					koinkoin.prompt.create( {	title: "An error occured", 
												type: "error",
												message: data.message
											});
					
					$(form).find('button').removeAttr('disabled');
					$(form).find('button').removeClass('disabled');
				}
			}
		);
	}
	
	return false;
}

koinkoin.selectContent = function(element)
{
	element.focus();
	element.select();
}

koinkoin.prompt = {};
koinkoin.prompt.i = 0;

koinkoin.prompt.create = function(data)
{
	koinkoin.prompt.i++;
	
	var defaultOptions =
	{	buttons:
				{	"OK":	function(event)
							{
								event.data.prompt.trigger('close');
							}
				},
		title: "",
		message: "",
		html: "",
		id: "",
		width: "",
		height: "",
		type: "message"
	};
	
	data = $.extend(defaultOptions, data);
	
	var prompt = $('<div></div>');
	var title = $('<h2></h2>');
	var titleContent = $('<span></span>');
	var content = $('<p></p>');
	var buttonContainer = $('<div></div>');
	
	prompt.addClass('prompt prompt-' + data.type);
	
	if(data.class != null)
		prompt.addClass(data.class);
	
	if(data.id == "")
		prompt.attr('id', 'prompt-' + koinkoin.prompt.i);
	else
		prompt.attr('id', 'prompt-' + data.id);
	
	buttonContainer.addClass('button-container');
	titleContent.text(data.title);
	if(data.html == "")
		content.text(data.message);
	else
		content.html(data.html);
	
	for(var i in data.buttons)
	{
		var button = $('<a></a>');
		button.addClass('btn');
		button.text(i);
		button.bind('click', {"prompt": prompt}, data.buttons[i]);
		
		buttonContainer.append(button);
	}
	
	prompt.bind('close', 	function(event)
							{
								prompt.fadeOut(200, function()
								{
									prompt.remove();
								});
							});
	
	title.append(titleContent);
	prompt.append(title);
	prompt.append(content);
	prompt.append(buttonContainer);
	
	prompt.css("display", "none");
	
	if(data.width != "")
		prompt.css("width", data.width);
	
	if(data.height != "")
		prompt.css("height", data.height);
	
	$('body').append(prompt);
	
	prompt.fadeIn(200);
	
	prompt.css('left', ($(document).width() / 2) - ($(prompt).width() / 2));
	prompt.css('top', ($(document).height() / 2) - ($(prompt).height() / 2) - 25);
	
	return prompt;
}

koinkoin.showMyLinks = function()
{
	if($('#prompt-mylinks').length != 0)
		return 1;
	
	var content = '	<div class="preloader">\
						<div class="preloader-shadow"></div>\
						<img class="preloader-img" src="static/images/loader.gif" />\
					</div>';
	
	var prompt = koinkoin.prompt.create({	title: "My links",
											type: "message",
											html: content,
											id: "mylinks",
											width: "50%",
											height: "80%",
											class: "full",
											buttons:
											{	"Close":function(event)
														{
															event.data.prompt.trigger('close');
														}
											}
										});
	
	var preloader = prompt.find('div.preloader')[0];
	var img = prompt.find('img.preloader-img')[0];
	var promptContent = prompt.find('p')[0];
	$(preloader).css('left', ($(promptContent).width() / 2) - ($(preloader).width() / 2) - 12);
	$(preloader).css('top', ($(prompt).height() / 2) - 64);
	$(img).css('top', ($(preloader).height() / 2) - 16);
	$(img).css('left', ($(preloader).width() / 2) - 16);
	
	var interval = setInterval(function()
	{
		var left = $(img).css('left');
		left = (parseFloat(left) + 1);
		$(img).css('left', left + "px");
		
		if(left >= $(preloader).width())
			$(img).css('left', "-32px");
	}, 40);
	
	$.ajax({
		type: "POST",
		url: BASE_URL + "mylinks",
		dataType: "json"
	})
	.done(
		function(data)
		{
			if(typeof data.debug != "undefined")
				debug.addTab(data.debug);
			
			if(data.result == true)
			{
				$(promptContent).fadeOut(function()
				{
					clearInterval(interval);
					$(preloader).remove();
					$(promptContent).html(data.html);
					$(promptContent).fadeIn();
				});
			}
			else
			{
				prompt.remove();
				koinkoin.prompt.create( {	title: "An error occured", 
											type: "error",
											message: data.message
										});
			}
		}
	);
}
