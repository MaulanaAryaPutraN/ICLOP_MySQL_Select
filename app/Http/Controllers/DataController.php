<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $cards = [
            [
                'image' => './images/cards/Android.png',
                'title' => 'Android programming with Java and Kotlin',
                'topics' => '18 learning topics',
            ],
            [
                'image' => './images/cards/Flutter.png',
                'title' => 'Mobile programming with Flutter',
                'topics' => '18 learning topics',
            ],
            [
                'image' => './images/cards/Node.js.png',
                'title' => 'Web application with Node.JS',
                'topics' => '18 learning topics',
            ],
        ];

        $cardsData = [
            [
                'image' => './images/cards/computer.png',
                'title' => 'Fully Computer-Assisted Learning Platform',
                'description' => 'Digital educational platform that utilizes artificial intelligence and machine learning to provide a comprehensive and interactive learning experience entirely driven by computer technology.',
            ],
            [
                'image' => './images/cards/eos-icons_machine-learning.png',
                'title' => 'Intelligence Guidance',
                'description' => 'System or technology that provides automated support and guidance to learners, assisting them in their learning journey through intelligent algorithms and machine learning.',
            ],
            [
                'image' => './images/cards/Grading.png',
                'title' => 'Auto Grading',
                'description' => 'Technology that automatically evaluates and scores assignments, exams, or assessments, eliminating the need for manual grading by instructors and providing efficient and consistent feedback to students.',
            ],
        ];

        $cardsData2 = [
            [
                'image' => './images/cards/Intelligence.png',
                'title' => 'Intelligence Learning Guidance',
                'description' => 'Intelligence Learning Guidance utilizes AI and smart algorithms to provide personalized support, adapting to learners needs and optimizing their educational outcomes.',
            ],
            [
                'image' => './images/cards/coding.png',
                'title' => 'Practical Coding Approach',
                'description' => 'Focuses on teaching coding skills through real-world examples, projects, and problem-solving scenarios, enabling learners to develop practical coding proficiency and problem-solving abilities.',
            ],
            [
                'image' => './images/cards/Machine.png',
                'title' => 'Online Virtual Machine',
                'description' => 'Virtual computing environment accessible over the internet, enabling users to run applications, perform tasks, and store data without requiring physical hardware.',
            ],
        ];

        return view('welcome', compact('cards', 'cardsData', 'cardsData2'));
    }
}
