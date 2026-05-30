<?php

namespace App\Controllers;

use App\Core\RBAC;

class InventoryController extends Controller
{
    public function index()
    {
        $this->view('inventory/product');
    }

    public function product()
    {
        $this->view('inventory/product');
    }

    public function inventoryCategories()
    {
        $this->view('inventory/inventory_categories');
    }

    public function unitsOfMeasure()
    {
        $this->view('inventory/units_of_measure');
    }

    public function inventoryLocations()
    {
        $this->view('inventory/inventory_locations');
    }

    public function suppliers()
    {
        $this->view('inventory/suppliers');
    }

    public function productStockPerLocation()
    {
        $this->view('inventory/product_stock_per_location');
    }

    public function purchaseOrders()
    {
        $this->view('inventory/purchase_orders');
    }

    public function stockTransactions()
    {
        $this->view('inventory/stock_transactions');
    }

    public function stockTransfers()
    {
        $this->view('inventory/stock_transfers');
    }

    public function inventorySales()
    {
        $this->view('inventory/inventory_sales');
    }

    public function inventoryActions()
    {
        $this->view('inventory/inventory_actions');
    }

    public function pos()
    {
        // Check if user has POS access permission
        if (!RBAC::canAccessPOS()) {
            // Redirect to dashboard with error
            $_SESSION['error'] = 'You do not have permission to access the Point of Sale.';
            header('Location: ' . ($this->base_url ?? './'));
            exit;
        }
        $this->view('inventory/pos', ['hide_sidebar' => true]);
    }
}
