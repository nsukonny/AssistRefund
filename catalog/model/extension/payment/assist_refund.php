<?php
class ModelExtensionPaymentAssistRefund extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/assist_refund');

		$method_data = array();

		return $method_data;
	}
}