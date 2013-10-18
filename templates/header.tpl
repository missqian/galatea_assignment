<!DOCTYPE html>
<html>
<head>
<title>{$Title}</title>
<link rel='stylesheet' id='style-css'  href='css/style.css' type='text/css' media='all' />
<script type='text/javascript' src='js/jquery.js'></script>
<script type="text/javascript">
show = true;
	$(document).ready(function(){
		$(".edit").click(function(){
			persion_id = $(this).attr('id');
			entry_tr = $(this).parent().parent();
			if ($(this).html() == 'Edit'){
				name_td = entry_tr.find("td:nth-child(1)");
				name = name_td.html().trim();
				type_td = entry_tr.find("td:nth-child(2)");
				type = type_td.html().trim();
				desc_td = entry_tr.find("td:nth-child(3)");
				desc = desc_td.html().trim();
				name_td.html("<input id='edit-name' type='text' value='" + name +"'/>");
				type_td.html("<input id='edit-type' type='text' value='" + type +"'/>");
				desc_td.html("<input id='edit-desc' type='text' value='" + desc +"'/>");
				$(this).html("Done");
			}
			else if ($(this).html() == 'Done'){
				name_td = entry_tr.find("td:nth-child(1)");
				name = $(name_td.children()[0]).val();
				type_td = entry_tr.find("td:nth-child(2)");
				type = $(type_td.children()[0]).val();
				desc_td = entry_tr.find("td:nth-child(3)");
				desc = $(desc_td.children()[0]).val();
				update(this, entry_tr, name, type, desc, persion_id);
			}
		});

		$("#add-new").click(function(){
			the_form = "<h4>Add New User</h4>" +
				"<form>" +
				"<label>User Name</label><br />"+
				"<input type = 'text' /><br />" +
				"<label>Password</label><br />"+
				"<input type = 'password' /><br />" +
				"<label>Confirm Password</label><br />"+
				"<input type = 'password' /><br />" +
				"<label>Type</label><br />"+
				"<input type = 'text' /><br />" +
				"<label>Description</label><br />"+
				"<input type = 'text' /><br />" +
				"<input type='button' id='add-new-submit' value='Submit' /><br/>" +
				"</form>";
			if (show)
			{
				$("#add-new-form").html(the_form);
				$("#add-new-submit").click(function(){
					form = $(this).parent();
					name = $($(form).children("input")[0]).val();
					pwd = $($(form).children("input")[1]).val();
					cpwd = $($(form).children("input")[2]).val();
					type = $($(form).children("input")[3]).val();
					desc = $($(form).children("input")[4]).val();
					add(name, pwd, cpwd, type, desc);
				});	
				$("#add-new-form").css("border", "solid 1px #cc0");
			}
			else
			{
				$("#add-new-form").html("");
				$("#add-new-form").css("border", "none");
			}
			show = !show;

		});
	});
	function add(n, p, cp, t, d){
		data_to_send = new FormData();
		data_to_send.append("name", n);
		data_to_send.append("type", t);
		data_to_send.append("password", p);
		data_to_send.append("confirm_pwd", cp);
		data_to_send.append("desc", d);
		jqxhr = $.ajax({
			url: "update.php",
			type: 'POST',
			data: data_to_send,
			processData: false,
			contentType: false,
			dataType: "json"
			})
		.done(function(data){
			if (data.errno == 0)
			{
				num = $("#people table tbody").children().length;
				if (num < 11)
				{
					location.reload();
				}
			}
			else
			{
				$("#index-message").html(data.err_msg);
			}
		})
		.fail(function(){
			alert("network error");
		});
	}
	function update(button, entry_tr, n, t, d, i){
		data_to_send = new FormData();
		data_to_send.append("name", n);
		data_to_send.append("type", t);
		data_to_send.append("desc", d);
		if (i != undefined)
			data_to_send.append("id", i);
		jqxhr = $.ajax({
			url: "update.php",
			type: 'POST',
			data: data_to_send,
			processData: false,
			contentType: false,
			dataType: "json"
			})
		.done(function(data){
			if (data.errno == 0)
			{
				$(entry_tr.children()[0]).html(data.name);
				$(entry_tr.children()[1]).html(data.type);
				$(entry_tr.children()[2]).html(data.desc);
				$(button).html("Edit");
				$("#index-message").html("");
			}
			else
			{
				$("#index-message").html(data.err_msg);
			}
		})
		.fail(function(){
			alert("network error");
		});
	}
</script>
</head>
<body>
