<x-guest-layout>
    @if (Route::has('login'))
    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
        @auth
        <a href="/phpunit" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
        <a href="/logout" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log Out</a>
        @else
        <a href="{{ route('login') }}"
            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
            in</a>

        @if (Route::has('register'))
        <a href="{{ route('register') }}"
            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
        @endif
        @endauth
    </div>
    @endif
    <p class="dark:text-gray-300 text-center">
        @auth
        Welcome, {{ Auth::user()->name }}
        <form method="post" action="/phpunit/palm-testing/">
            @csrf
            <input class="form-control" name="post" placeholder="What's on your mind, {{ Auth::user()->name }}" 
            style=" margin-bottom: 20px;
            border: 1px solid;
            width: 100%;
            margin-top: 5px;
            padding: 5px;
            border-radius: 5px;">
            <button  type="submit" class="btn btn-primary custom-button-sign-in-modal" name='chat'
            style="padding: 5px;
            width: 30%;
            border-radius: 3px;
            background: lightskyblue;
            cursor: pointer;
            color: #fafafa;"
            >Chat</button>
        </form>
        <div style="padding:10px 0;">
            {{ $result_chat }} 
            
            {{ $result_error }} 
        <div>

        @else
        Welcome, login to view your dashboard.
        @endauth
    </p>
</x-guest-layout>