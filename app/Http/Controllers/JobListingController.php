<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JobListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jobListings = JobListing::orderBy('created_at', 'desc')->get();
        return view('admin.job-listings', compact('jobListings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.add-job-listing');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'phone' => 'required|string|max:20',
        ], [
            'job_title.required' => 'İlan başlığı gereklidir.',
            'job_description.required' => 'İlan açıklaması gereklidir.',
            'phone.required' => 'Telefon numarası gereklidir.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        JobListing::create([
            'job_title' => $request->job_title,
            'job_description' => $request->job_description,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.job-listings.index')->with('success', 'İlan başarıyla oluşturuldu!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $jobListing = JobListing::findOrFail($id);
        return view('admin.edit-job-listing', compact('jobListing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'phone' => 'required|string|max:20',
        ], [
            'job_title.required' => 'İlan başlığı gereklidir.',
            'job_description.required' => 'İlan açıklaması gereklidir.',
            'phone.required' => 'Telefon numarası gereklidir.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $jobListing = JobListing::findOrFail($id);
        $jobListing->update([
            'job_title' => $request->job_title,
            'job_description' => $request->job_description,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.job-listings.index')->with('success', 'İlan başarıyla güncellendi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $jobListing = JobListing::findOrFail($id);
        $jobListing->delete();

        return redirect()->route('admin.job-listings.index')->with('success', 'İlan başarıyla silindi!');
    }
}

























