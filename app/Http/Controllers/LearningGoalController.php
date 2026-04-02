<?php

namespace App\Http\Controllers;

use App\Models\LearningGoal;
use Illuminate\Http\Request;

class LearningGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goals = LearningGoal::orderBy('month', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($goal) {
                return $goal->month->format('Y-m'); // Group by year-month
            });

        return view('learning-goals.index', compact('goals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'title' => 'required|string|max:255',
            'course_url' => 'nullable|url|max:255',
            'total_videos' => 'required|integer|min:1',
            'completed_videos' => 'nullable|integer|min:0',
        ]);

        $completed = $request->completed_videos ?? 0;
        if ($completed > $request->total_videos) {
            $completed = $request->total_videos;
        }

        LearningGoal::create([
            'user_id' => auth()->id(),
            'month' => $request->month . '-01',
            'title' => $request->title,
            'course_url' => $request->course_url,
            'total_videos' => $request->total_videos,
            'completed_videos' => $completed,
        ]);

        return redirect()->route('learning-goals.index')->with('success', 'تمت إضافة الهدف بنجاح 🎯');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LearningGoal $learningGoal)
    {
        $request->validate([
            'completed_videos' => 'required|integer|min:0',
            'title' => 'nullable|string|max:255',
            'course_url' => 'nullable|url|max:255',
            'total_videos' => 'nullable|integer|min:1',
            'month' => 'nullable|date_format:Y-m',
        ]);

        $data = $request->only(['title', 'course_url', 'total_videos', 'completed_videos']);
        
        if ($request->filled('month')) {
            $data['month'] = $request->month . '-01';
        }

        // Ensure completed_videos doesn't exceed total_videos
        $total = $data['total_videos'] ?? $learningGoal->total_videos;
        $completed = $data['completed_videos'] ?? $learningGoal->completed_videos;
        
        if ($completed > $total) {
            $completed = $total;
        }
        $data['completed_videos'] = $completed;

        $learningGoal->update($data);

        return back()->with('success', 'تم تحديث الإنجاز 🚀');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningGoal $learningGoal)
    {
        $learningGoal->delete();

        return redirect()->route('learning-goals.index')->with('success', 'تم حذف الهدف');
    }
}
