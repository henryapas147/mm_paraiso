<?php

namespace App\Controllers;

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
}
