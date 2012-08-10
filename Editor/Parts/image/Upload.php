<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../Include/Private.php';

// hide warnings
error_reporting(E_ERROR);

$response = ImageService::createUploadedImage();

if ($response->getSuccess()) {
	ImagePartController::setLatestUploadId($response->getObject()->getId());
	In2iGui::respondUploadSuccess();
} else {
	ImagePartController::setLatestUploadId(null);
	Log::debug($response);
	In2iGui::respondUploadFailure();
}
?>