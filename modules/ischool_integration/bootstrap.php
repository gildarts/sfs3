<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<link href="css/bootstrap.css" rel="stylesheet">

	<script>
		$(function(){
			// $('#ex-alert').addClass('in');
			// alert('hi');
			// $('#ex-alert').removeClass('in');
			// alert('hi');
			// $('#myModal').modal('show');
		});
	</script>
</head>
<body>
	<div class='well'>
		<div class="alert alert-block alert-danger fade in">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4>Oh snap! You got an error!</h4>
			<p>Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
			<p>
				<a class="btn btn-danger" href="#">Take this action</a> <a class="btn btn-default" href="#">Or do this</a>
			</p>
		</div>

		<div class="alert alert-warning fade" id = 'ex-alert'>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Holy guacamole!</strong> Best check yo self, you're not looking too good.
		</div>
	</div>

	<div class="modal fade" id = 'myModal'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					<p>One fine body&hellip;</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="row">
		<div class="col-xs-6 col-sm-3">a.col-xs-6 .col-sm-3</div>
		<div class="col-xs-6 col-sm-3">b.col-xs-6 .col-sm-3</div>

		<!-- Add the extra clearfix for only the required viewport -->
		<div class="clearfix visible-xs"></div>

		<div class="col-xs-6 col-sm-3">c.col-xs-6 .col-sm-3</div>
		<div class="col-xs-6 col-sm-3">d.col-xs-6 .col-sm-3</div>
	</div>
</body>
</html>