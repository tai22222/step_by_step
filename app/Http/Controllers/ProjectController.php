<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index() {
      return Inertia::render('Project/Create');
    }

    public function create() {
      return Inertia::render('Project/Create');
    }

    public function store() {

    }

    public function show() {
      return Inertia::render('Project/Create');
    }

    public function edit() {
      return Inertia::render('Project/Create');
    }

    public function update() {

    }

    public function destroy() {

    }
}
