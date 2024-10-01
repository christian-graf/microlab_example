<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Inertia\Controller;

class CalendarController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Calendar');
    }
}
