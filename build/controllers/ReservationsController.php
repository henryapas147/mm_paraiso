<?php

namespace App\Controllers;

class ReservationsController extends Controller
{
    public function index()
    {
        $this->view('reservations/reservations');
    }

    public function reservations()
    {
        $this->view('reservations/reservations');
    }

    public function reservationRooms()
    {
        $this->view('reservations/reservation_rooms');
    }

    public function reservationGuests()
    {
        $this->view('reservations/reservation_guests');
    }

    public function reservationRequirementTypes()
    {
        $this->view('reservations/reservation_requirement_types');
    }

    public function reservationRequirements()
    {
        $this->view('reservations/reservation_requirements');
    }

    public function checkInLogs()
    {
        $this->view('reservations/check_in_logs');
    }

    public function entranceLogs()
    {
        $this->view('reservations/entrance_logs');
    }
}
