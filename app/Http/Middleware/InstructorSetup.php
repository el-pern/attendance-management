<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Instructor;
use Symfony\Component\HttpFoundation\Response;

class InstructorSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(auth()->check()){

            $reyal_instructor = Instructor::where('email', auth()->user()->email)->exists();

            if(!$request->is('instructor/fill') && !$reyal_instructor){
                return redirect('/instructor/fill')->with('error', 'Please complete your instructor profile.');
            }

            if($request->is('instructor/fill') && $reyal_instructor){
                return redirect('/')->with('info', 'Instructor profile already completed.');
            }

        }

        return $next($request);
    }
}
