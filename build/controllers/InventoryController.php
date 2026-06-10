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

    public function productUomConversions()
    {
        $this->view('inventory/product_uom_conversions');
    }

    public function inventoryTransactions()
    {
        $this->view('inventory/inventory_transactions');
    }

    public function purchaseOrders()
    {
        $this->view('inventory/purchase_orders');
    }

    public function purchaseOrdersReceiving()
    {
        $this->view('inventory/purchase_orders_receiving');
    }

    public function stockTransactions()
    {
        $this->view('inventory/stock_transactions');
    }

    public function stockTransfers()
    {
        $this->view('inventory/stock_transfers');
    }

    public function stockIn()
    {
        $this->view('inventory/stock_in');
    }

    public function inventorySales()
    {
        $this->view('inventory/inventory_sales');
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
