OAuth2.0 Client

1./src/Provider/Test.php
Server side class

2.provider.php
initialize Test provider

3.index.php
if $_SESSION[‘token’] is existed —>1) token expired —> using refresh token to request protected resource
				   2) token isn’t expired —> using token in $_SESSION(or database) to request protected resource

$_SESSION[‘token’] isn’t existed —>1) isset(authorization code) —> Using Authorization Code to request token
				   2) empty(authorization code) —> location to Authorization Url(server side) to get authorization code 				      then jump to 1).

Using token to request protected resource.