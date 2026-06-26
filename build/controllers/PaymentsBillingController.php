<?php

namespace App\Controllers;

class PaymentsBillingController extends Controller
{
    public function index()
    {
        $this->view('payments-billing/payment_methods');
    }

    public function paymentMethods()
    {
        $this->view('payments-billing/payment_methods');
    }

    public function paymentAccounts()
    {
        $this->view('payments-billing/payment_accounts');
    }

    public function payments()
    {
        $this->view('payments-billing/payments');
    }

    public function invoices()
    {
        $this->view('payments-billing/invoices');
    }

    public function invoiceLineItems()
    {
        $this->view('payments-billing/invoice_line_items');
    }

    public function discounts()
    {
        $this->view('payments-billing/discounts');
    }

    public function discountUsages()
    {
        $this->view('payments-billing/discount_usages');
    }

    public function extraCharges()
    {
        $this->view('payments-billing/extra_charges');
    }

    public function billing()
    {
        $this->view('payments-billing/billing');
    }
}
