<?php

class ControllerExtensionPaymentAssistRefund extends Controller {

	private $error = array();

	public function index() {
		$this->load->language( 'extension/payment/assist_refund' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$this->load->model( 'setting/setting' );

		if ( ( $this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validate() ) {
			$this->model_setting_setting->editSetting( 'payment_assist_refund', $this->request->post );
			$this->session->data['success'] = $this->language->get( 'text_success' );
			$this->response->redirect( $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true ) );
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get( 'text_home' ),
			'href' => $this->url->link( 'common/dashboard', 'user_token=' . $this->session->data['user_token'], true )
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get( 'text_extension' ),
			'href' => $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true )
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get( 'asist_heading_title' ),
			'href' => $this->url->link( 'extension/payment/assist_refund', 'user_token=' . $this->session->data['user_token'], true )
		);

		$data['action'] = $this->url->link( 'extension/payment/assist_refund', 'user_token=' . $this->session->data['user_token'], true );
		$data['cancel'] = $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true );

		$data['payment_assist_refund_status']      = isset( $this->request->post['payment_assist_refund_status'] ) ? $this->request->post['payment_assist_refund_status'] : $this->config->get( 'payment_assist_refund_status' );
		$data['payment_assist_refund_merchant_id'] = isset( $this->request->post['payment_assist_refund_merchant_id'] ) ? $this->request->post['payment_assist_refund_merchant_id'] : $this->config->get( 'payment_assist_refund_merchant_id' );
		$data['payment_assist_refund_login']       = isset( $this->request->post['payment_assist_refund_login'] ) ? $this->request->post['payment_assist_refund_login'] : $this->config->get( 'payment_assist_refund_login' );
		$data['payment_assist_refund_password']    = isset( $this->request->post['payment_assist_refund_password'] ) ? $this->request->post['payment_assist_refund_password'] : $this->config->get( 'payment_assist_refund_password' );
		$data['payment_assist_refund_lifetime']    = isset( $this->request->post['payment_assist_refund_lifetime'] ) ? $this->request->post['payment_assist_refund_lifetime'] : $this->config->get( 'payment_assist_refund_lifetime' );
		$data['payment_assist_refund_url']         = isset( $this->request->post['payment_assist_refund_url'] ) ? $this->request->post['payment_assist_refund_url'] : $this->config->get( 'payment_assist_refund_url' );
		$data['payment_assist_refund_status']      = isset( $this->request->post['payment_assist_refund_status'] ) ? $this->request->post['payment_assist_refund_status'] : $this->config->get( 'payment_assist_refund_status' );

		$data['error_warning'] = isset( $this->error['warning'] ) ? $this->error['warning'] : '';

		$data['entry_1']             = $this->language->get( 'entry_1' );
		$data['entry_merchant_id']   = $this->language->get( 'entry_merchant_id' );
		$data['entry_login']         = $this->language->get( 'entry_login' );
		$data['entry_password']      = $this->language->get( 'entry_password' );
		$data['entry_url']           = $this->language->get( 'entry_url' );
		$data['entry_status_return'] = $this->language->get( 'entry_status_return' );

		$this->load->model( 'localisation/order_status' );
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['header']      = $this->load->controller( 'common/header' );
		$data['column_left'] = $this->load->controller( 'common/column_left' );
		$data['footer']      = $this->load->controller( 'common/footer' );

		$this->response->setOutput( $this->load->view( 'extension/payment/assist_refund', $data ) );
	}

	protected function validate() {
		if ( ! $this->user->hasPermission( 'modify', 'extension/payment/assist_refund' ) ) {
			$this->error['warning'] = $this->language->get( 'error_permission' );
		}

		if ( ! $this->request->post['payment_assist_refund_lifetime']
		     || ! is_numeric( $this->request->post['payment_assist_refund_lifetime'] ) ) {
			$this->error['warning'] = $this->language->get( 'error_lifetime' );
		}

		return ! $this->error;
	}

	public function refund() {
		if ( isset( $this->request->get['order_id'] ) ) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model( 'sale/order' );
		$data['order_info'] = $this->model_sale_order->getOrder( $order_id );

		$this->load->model( 'setting/setting' );
		$data['payment_assist_start_order_status_id'] = $this->config->get( 'payment_assist_start_order_status_id' );
		$data['payment_assist_refund_status']         = $this->config->get( 'payment_assist_refund_status' );
		$data['payment_assist_refund_merchant_id']    = $this->config->get( 'payment_assist_refund_merchant_id' );
		$data['payment_assist_refund_login']          = $this->config->get( 'payment_assist_refund_login' );
		$data['payment_assist_refund_password']       = $this->config->get( 'payment_assist_refund_password' );
		$data['payment_assist_refund_lifetime']       = $this->config->get( 'payment_assist_refund_lifetime' );
		$data['payment_assist_refund_url']            = $this->config->get( 'payment_assist_refund_url' );

		$this->load->language( 'extension/payment/assist_refund' );
		$data['text_assist_refund'] = $this->language->get( 'text_assist_refund' );
		$this->document->setTitle( $this->language->get( 'heading_title' ) );
		$data['text_edit']        = $this->language->get( 'text_return_form_title' ) . ' #' . $order_id;
		$data['entry_summ']       = $this->language->get( 'entry_summ' );
		$data['entry_billnumber'] = $this->language->get( 'entry_billnumber' );
		$data['entry_password']   = $this->language->get( 'entry_password' );
		$data['entry_return']     = $this->language->get( 'entry_return' );

		$data['order_total'] = round( $data['order_info']['total'] );
		$data['billnumber']  = isset( $data['order_info']['payment_custom_field']['billnumber'] )
			? $data['order_info']['payment_custom_field']['billnumber'] : 0;

		$data['header']      = $this->load->controller( 'common/header' );
		$data['column_left'] = $this->load->controller( 'common/column_left' );
		$data['footer']      = $this->load->controller( 'common/footer' );

		$this->response->setOutput( $this->load->view( 'extension/payment/assist_refund_form', $data ) );
	}
}