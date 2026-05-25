<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<form action="{{ url('proses-login') }}" method="GET">
		
		@csrf 

		<input type="email" name="email" class="form-control" placeholder=". . ." />
		<input type="password" name="password" class="form-control" placeholder=". . ." />

		<input type="submit" value="submit">
	</form>
</body>
</html>