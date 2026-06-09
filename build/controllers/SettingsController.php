<?php

namespace App\Controllers;

class SettingsController extends Controller
{
    public function index()
    {
        $this->view('settings/system_settings');
    }

    public function systemSettings()
    {
        $this->view('settings/system_settings');
    }

    public function pricingRules()
    {
        $this->view('settings/pricing_rules');
    }

    public function seasonalRates()
    {
        $this->view('settings/seasonal_rates');
    }

    public function bookingPolicies()
    {
        $this->view('settings/booking_policies');
    }

    public function promotions()
    {
        $this->view('settings/promotions');
    }

    public function promotionImages()
    {
        $this->view('settings/promotion_images');
    }

    public function notifications()
    {
        $this->view('settings/notifications');
    }

    public function notificationRecipients()
    {
        $this->view('settings/notification_recipients');
    }

    public function rbacSettings()
    {
        $this->view('settings/rbac_settings', [
            'csrf_token' => $_SESSION['csrf_token'] ?? ''
        ]);
    }

    public function company()
    {
        $this->view('settings/company');
    }

    public function carouselSlides()
    {
        $this->view('content-management/carousel');
    }
}
