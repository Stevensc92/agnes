<?php
namespace Agnes\Util;

class FlashMessage
{
    private $message;
    private $type;

	public function setFlashMessage($message, $type = 'error', $modal = false): void
	{
		$_SESSION['flashMessage'] = array(
			'message' => $message,
			'type'	  => $type,
			'modal'   => $modal,
		);
	}
}
