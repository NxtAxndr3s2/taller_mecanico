<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        require_auth(); // ✅ aquí sí
        $this->view('dashboard/index');
    }
}
