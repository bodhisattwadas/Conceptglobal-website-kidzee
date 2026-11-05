<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('price', 'asc')->get();

        $programs = [
            [
                'id' => 'nuvo',
                'name' => 'Nuvo',
                'age' => '1.5 – 2 Years',
                'tagline' => 'Early Sensorial & Tactile Discovery',
                'badge_color' => 'bg-amber-100 text-amber-800 border-amber-300',
                'theme_color' => 'from-amber-400 to-yellow-500',
                'icon' => 'baby',
                'description' => 'Gentle transition into social spaces with sensory play, fine motor grasping, musical bonding, and rhythm imitation.',
                'highlights' => ['Sensory play stations', 'Soft gym & crawling maze', '1:5 Caregiver ratio', 'Daily milestone journal'],
                'timing' => '9:00 AM – 11:30 AM',
            ],
            [
                'id' => 'playgroup',
                'name' => 'Playgroup',
                'age' => '2 – 3 Years',
                'tagline' => 'Curiosity, Words & Social Spark',
                'badge_color' => 'bg-purple-100 text-purple-800 border-purple-300',
                'theme_color' => 'from-purple-500 to-indigo-600',
                'icon' => 'blocks',
                'description' => 'Igniting curiosity through experiential play, dramatic puppet theatre, conversational phonics, and emotional expression.',
                'highlights' => ['Pentamind iLLUME methodology', 'Circle time & rhymes', 'Sand & water kinetic play', 'Vocabulary builder kits'],
                'timing' => '9:00 AM – 12:00 PM',
            ],
            [
                'id' => 'nursery',
                'name' => 'Nursery',
                'age' => '3 – 4 Years',
                'tagline' => 'Foundational Literacy & Numerical Concepts',
                'badge_color' => 'bg-sky-100 text-sky-800 border-sky-300',
                'theme_color' => 'from-sky-400 to-blue-600',
                'icon' => 'book-open',
                'description' => 'Developing pre-reading and phonics recognition, basic numeracy, spatial logic, and joyful collaborative tasks.',
                'highlights' => ['Jolly phonics integration', 'Montessori sensorial math', 'Colour mixing & creative arts', 'Field trips & nature discovery'],
                'timing' => '9:00 AM – 12:30 PM',
            ],
            [
                'id' => 'junior-kg',
                'name' => 'Junior KG',
                'age' => '4 – 5 Years',
                'tagline' => 'Critical Thinking & Scientific Wonder',
                'badge_color' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'theme_color' => 'from-emerald-400 to-teal-600',
                'icon' => 'puzzle',
                'description' => 'Fostering analytical curiosity, sentence formulation, number operations, and early STEM exploration.',
                'highlights' => ['Sight words & early readers', 'Basic addition & patterning', 'Hands-on junior science lab', 'Public speaking show-and-tell'],
                'timing' => '8:30 AM – 1:00 PM',
            ],
            [
                'id' => 'senior-kg',
                'name' => 'Senior KG',
                'age' => '5 – 6 Years',
                'tagline' => 'Grade-1 Readiness & Confident Leadership',
                'badge_color' => 'bg-rose-100 text-rose-800 border-rose-300',
                'theme_color' => 'from-rose-500 to-pink-600',
                'icon' => 'graduation-cap',
                'description' => 'Comprehensive preparatory curriculum ensuring flawless transition into formal primary schools with high confidence.',
                'highlights' => ['Fluid cursive & creative writing', 'Mental math & word problems', 'Coding blocks & logical sequencing', 'Confidence graduation gala'],
                'timing' => '8:30 AM – 1:30 PM',
            ],
            [
                'id' => 'daycare',
                'name' => 'Day Care & Activity Club',
                'age' => '1.5 – 10 Years',
                'tagline' => 'Nurturing Home-Away-From-Home',
                'badge_color' => 'bg-orange-100 text-orange-800 border-orange-300',
                'theme_color' => 'from-orange-400 to-amber-600',
                'icon' => 'home-heart',
                'description' => 'Secure, sanitized, full/half-day environment with warm meals, homework assistance, structured hobby clubs, and live parent CCTV.',
                'highlights' => ['Nutritious meals & snacks', 'Sanitized air-conditioned nap pods', 'Live mobile CCTV stream for parents', 'Evening music, taekwondo & dance'],
                'timing' => '8:30 AM – 6:30 PM (Flexible)',
            ],
        ];

        return view('home', compact('plans', 'programs'));
    }

    public function programs(): View
    {
        return view('programs');
    }

    public function subscriptions(): View
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('subscriptions', compact('plans'));
    }
}
