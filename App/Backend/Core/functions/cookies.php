<?php
defined( 'ACCESS' ) or exit( 'Access Denied' );

setcookie(
	COOKIE_NAME,
	bin2hex( random_bytes( 16 ) ),
	array(
		'expires'  => time() + REMEMBER_ME_SESSION_LIFETIME,
		'path'     => '/',
		'domain'   => DOMAIN,
		'secure'   => true,
		'httponly' => true,
		'samesite' => SESSION_SAMESITE,
	)
);
