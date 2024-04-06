<section>
    <form method="post" action="{{ route('tokens.create') }}" class="mt-6 space-y-6">
        @csrf

        <div class="flex items-center gap-4">
            <x-primary-button>Create Token</x-primary-button>
        </div>
    </form>

    @if(session('token'))
        <p class="text-white">{{ session('token') }}</p>
    @endif

</section>
