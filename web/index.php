<?php
require ('../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application ();
$bot = new CU\LineBot ();

$app->register ( new Silex\Provider\MonologServiceProvider (), array (
		'monolog.logfile' => 'php://stderr'
) );

$app->before ( function (Request $request) use ($bot) {
	// Signature validation
	$request_body = $request->getContent ();
	$signature = $request->headers->get ( 'X-LINE-CHANNELSIGNATURE' );
	if (! $bot->isValid ( $signature, $request_body )) {
		return new Response ( 'Signature validation failed.', 400 );
	}
} );

$app->post ( '/callback', function (Request $request) use ($app, $bot) {
	// Let's hack from here!
	$body = json_decode ( $request->getContent (), true );

	foreach ( $body ['result'] as $obj ) {
		$app ['monolog']->addInfo ( sprintf ( 'obj: %s', json_encode ( $obj ) ) );
		$from = $obj ['content'] ['from'];
		$content = $obj ['content'];

		$emoticon_array = get_tsv_emoticon ();
		if (array_key_exists ( $content ['text'], $emoticon_array ) === TRUE) {
			$key = array_rand ( $emoticon_array [ $content ['text']] );
			$bot->sendText ( $from, sprintf ( "%s", $emoticon_array [$content ['text']] [$key] ) );
		} else {
			$bot->sendText ( $from, sprintf ( "「%s」じゃねーよ！！", $content ['text'] ) );
		}
	}

	return 0;
} );

$app->run ();
function get_tsv_emoticon() {
	$file = '../emoticon_dic_utf8.txt';
	$data = file_get_contents ( $file );
	$temp = tmpfile ();

	fwrite ( $temp, $data );
	rewind ( $temp );

	$emoticon_array = array ();
	$value = array ();
	$key = 'しらんがな';
	while ( ($data = fgetcsv ( $temp, 0, "\t" )) !== FALSE ) {
		if ($key != $data [0]) {
			$emoticon_array += array (
					$key => $value
			);
			$value = array ();
			$key = $data [0];
		}
		array_push ( $value, $data [1] );
	}
	$emoticon_array += array (
			$key => $value
	);
	fclose ( $temp );
	return $emoticon_array;
}
