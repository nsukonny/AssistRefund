<?php

class ModelExtensionPaymentAssistRefund extends Model {

	public function addOrderHistory( $order_id, $order_status_id, $comment = '') {
		$this->load->model( 'sale/order' );

		$order_info = $this->model_sale_order->getOrder( $order_id );

		if ( $order_info ) {
			$this->db->query( "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'" );

			$this->db->query( "INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', comment = '" . $this->db->escape( $comment ) . "', date_added = NOW()" );

			$this->cache->delete( 'product' );
		}
	}

}