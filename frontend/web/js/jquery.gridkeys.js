	$("input.flat").keypress( function (e) {
		
		switch(e.keyCode)
		{
			// Enter
			case 13:
				var currentRowE =$(this).parent().parent().closest('tr');
				currentRowE.children('td').children('a:first').click();
				console.log(currentRowE.children('td').children('a:first').attr('href'));

				break;
			// left arrow
			case 37:
				$(this).parent()
					.prev()
					.children("input.flat")
					.focus();
				break;
			// right arrow
			case 39:
				$(this).parent()
					.next()
					.children("input.flat")
					.focus();
				break;
			// down arrow
			case 40:
				var currentRowD =$(this).parent().parent().closest('tr');
				var lastRowD = $('tr:last');
				if (currentRowD.is(lastRowD) ) {
					break;
				}
				currentRowD.children('td,th').css('background-color','#fff');
				$(this).css('background-color','#fff');
				
				$(this).parent()
					.parent()
					.next()
					.children("td")
					.children("input.flat")
					.focus();
				break;
			// up arrow
			case 38:
				var currentRowU =$(this).parent().parent().closest('tr');
				var firstRowU = $('tbody tr:first');
				if (currentRowU.is(firstRowU) ) {
					break;
				}
				currentRowU.children('td,th').css('background-color','#fff');
				$(this).css('background-color','#fff');				
							
				$(this).parent()
					.parent()
					.prev()
					.children("td")
					.children("input.flat")
					.focus();
				break;
			
		}
	});
	$("input.flat").focus( function (e) {
		$(this).parent().parent().closest('tr').children('td,th').css('background-color','#d0e9c6');
		$(this).css('background-color','#d0e9c6');
	});
