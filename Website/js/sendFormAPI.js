$('document').ready(function() { 
	/* handling form validation */
	$("#reg_form").validate({
		rules: {
			username: {
				required: true,
			},
			firstname: {
				required: true,
			},
			surname: {
				required: true,
			},
			password: {
				required: true,
			},
			email: {
				required: true,
			},
			identityNum: {
				required: true,
			},
			birthdate: {
				required: true,
			},
			telephone: {
				required: true,
			},
			address: {
				required: true,
			},
			bloodType:{
				required: true,
			}
		},
		messages: {
			username:{
			  required: "please enter your username"
			 },
			firstname: "please enter your firstname",
			surname: "please enter your surname",
			password: "please enter your password",
			email: "please enter your email",
			identityNum: "please enter your identityNum",
			birthdate: "please enter your birthdate",
			telephone: "please enter your telephone",
			address: "please enter your address",
			bloodType: "please enter your bloodType",
		},
		submitHandler: JSON.parse(submitForm)	
	});	   
	/* Handling login functionality */
	function submitForm() {		
		var data = $("#reg_form").serialize();				
		$.ajax({				
			type : 'GET',
			dataType : 'json',
			url  : 'api/_/register/',
			data : data,
			beforeSend: function(){	
				$("#error").fadeOut();
				$("#btn_submit").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; sending ...');
			},
			success : function(response){
				//response = $.parseJSON(response);
				$.each(data, function(index, element) {
		            console.log	(element.error);
		        });	
								
				if(response[0].error !== true){									
					//$("#login_button").html('<img src="ajax-loader.gif" /> &nbsp; Signing In ...');
					setTimeout(' window.location.href = "welcome.php"; ',2000);
				} else {									
					$("#error").fadeIn(1000, function(){						
						$("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+response+' !</div>');
						$("#btn_submit").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In');
					});
				}
			}
		});
		return false;
	}
 
});