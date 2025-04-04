<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LinkRequest;
use App\Models\Modulo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $links = Link::with('modulo')->paginate();

        return view('link.index', compact('links'))
            ->with('i', ($request->input('page', 1) - 1) * $links->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $link = new Link();
        $modulo = Modulo::all();
        return view('link.create', compact('link', 'modulo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LinkRequest $request): RedirectResponse
    {
        Link::create($request->validated());

        return Redirect::route('links.index')
            ->with('success', 'Link created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $link = Link::find($id);

        return view('link.show', compact('link'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $link = Link::find($id);
        $modulo = Modulo::all();
        return view('link.edit', compact('link', 'modulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LinkRequest $request, Link $link): RedirectResponse
    {
        $link->update($request->validated());

        return Redirect::route('links.index')
            ->with('success', 'Link updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Link::find($id)->delete();

        return Redirect::route('links.index')
            ->with('success', 'Link deleted successfully');
    }
}
