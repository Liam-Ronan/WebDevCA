{{-- Using the project component --}}
<x-layout>
    {{-- Using the search bar created in the partials folder --}}
    @include('partials._search')

    <a href="{{URL::previous()}}" class="inline-block bg-black text-white ml-4 mb-4 p-3 rounded-xl hover:opacity-80"><i class="fa-solid fa-arrow-left"></i> Back</a>
    <div class="mx-4">

    {{-- May Need to Use for back button! --}}
    {{-- {{ redirect()->getUrlGenerator()->previous() }} --}}

        <x-card class="p-10">
            <div class="flex flex-col items-center justify-center text-center">
                <img class="h-96 mr-6 mb-6" src="{{$project->image ? asset
                    ('storage/' . $project->image) : asset('/images/andras-vas-Bd7gNnWJBkU-unsplash.jpg')}}" alt=""/>

                <h3 class="text-3xl font-bold mb-3 mt-3">{{$project->title}}</h3>
                <div class="text-xl font-normal mb-3 mt-3"><h3>Project Uploaded:</h3>{{$project->date_created}}</div>

                <div class="text-xl font-normal mb-3 mt-3"><h3>Creator: </h3>{{$project->creator->name}}</div>

                <div class="text-xl font-normal mb-3 mt-3"><h3>Creator Address: </h3>{{$project->creator->address}}</div>

                
                <div class="text-xl font-normal mb-3 mt-3"><h3>Developers: <h3></div>  
                @foreach ($project->developers as $developer)
                <h3>
                    {{$developer->name}}
                </h3>                                              
                @endforeach
                

                <x-project-tags :tagsCsv="$project->tags"/>

                <div>
                    <h3 class="text-2xl font-bold mb-3">Project Description</h3>
                    <div class="text-lg space-y-6">
                        <div class="w-96">
                            {{$project->description}}
                        </div>

                        <a href="{{$project->email}}" class="block bg-black text-white mt-6 py-2 rounded-xl hover:opacity-80">
                            <i class="fa-solid fa-envelope"></i> Contact developer</a>

                        <a href=" {{$project->website}}" target="_blank" class="block bg-black text-white mt-6 py-2 rounded-xl hover:opacity-80">
                            <i class="fa-solid fa-globe"></i> Visit Project Website</a>

                        <a href="{{ route('admin.projects.edit', $project) }}" class="block bg-black text-white mt-6 py-2 rounded-xl hover:opacity-80">
                            <i class="fa-solid fa-pencil p-1"></i>Update</a>

                        <form method="POST" class="block bg-laravel text-white mt-6 py-2 rounded-xl hover:opacity-80" action="{{ route('admin.projects.destroy', $project) }}">
                            {{-- Using csrf to prevent Cross-site request forgeries --}}
                            @csrf
                            {{-- Delete methods --}}
                            @method('DELETE')
                            <button><i class="fa-solid fa-trash"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-layout>
