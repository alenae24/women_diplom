<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\Service;
use App\Models\Gallery;
use Illuminate\Http\Request;


class HomeController extends Controller
{

    public function index()
    {
        $masters = Master::with('servicePrices.service')->take(4)->get();
        $services = Service::take(6)->get();
        $advantages = [
            ['icon' => 'bi-calendar-check', 'title' => 'Удобная онлайн-запись', 'text' => 'Выбирайте удобное время и мастера в пару кликов'],
            ['icon' => 'bi-scissors', 'title' => 'Профессиональные мастера', 'text' => 'Опытные мастера с высокой квалификацией'],
            ['icon' => 'bi-brush', 'title' => 'Современные материалы', 'text' => 'Используем только качественные и безопасные средства'],
            ['icon' => 'bi-chat-heart', 'title' => 'Индивидуальный подход', 'text' => 'Учитываем все пожелания клиентов'],
        ];
        $gallery = Gallery::with(['master', 'service'])->orderBy('created_at', 'desc')->take(6)->get();

        return view('home', compact('masters', 'services', 'advantages', 'gallery'));
    }
}