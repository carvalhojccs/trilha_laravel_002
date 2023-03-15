<div>
    <div class="mb-4 d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-0 text-gray-800 h3">States</h1>
    </div>
    <div class="row">
        <div class="mx-auto card">
            <div>
                @if (session()->has('state-message'))
                    <div class="alert alert-success">
                        {{ session('state-message') }}
                    </div>
                @endif
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <form>
                            <div class="form-row align-items-center">
                                <div class="col">
                                    <input type="search" wire:model.debounce.750ms='search' class="mb-2 form-control"
                                        id="inlineFormInput" placeholder="Search...">
                                </div>
                                <div class="col" wire:loading>
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>
                        <button wire:click='showStateModal' class="btn btn-primary">
                            New State
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" wire:loading.remove>
                    <thead>
                        <tr>
                            <th scope="col">#Id</th>
                            <th scope="col">Country</th>
                            <th scope="col">Name</th>
                            <th scope="col">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($states as $state)
                            <tr>
                                <th scope="row">{{ $state->id }}</th>
                                <td>{{ $state->country->name }}</td>
                                <td>{{ $state->name }}</td>
                                <td>
                                    <button wire:click='showEditModal({{ $state->id }})'
                                        class="btn btn-success">Edit</button>
                                    <button wire:click='deleteState({{ $state->id }})'
                                        class="btn btn-danger">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th>No results found!</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                {{ $states->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- New state Modal -->
    <div class="modal fade" id="new-state-modal" tabindex="-1" aria-labelledby="new-state-modal-label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    @if ($editMode)
                        <h5 class="modal-title" id="new-state-modal-label">Edit State</h5>
                    @else
                        <h5 class="modal-title" id="new-state-modal-label">New State</h5>
                    @endif

                    <button wire:click='closeModal' class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group row">
                            <label for="countryId" class="col-md-4 col-form-label text-md-right">
                                {{ __('State Code') }}
                            </label>
                            <div class="col-md-6">                            
                                <select wire:model.defer='countryId' class="custom-select">
                                    <option selected>Choose</option>                                    
                                    @foreach (App\Models\Country::all() as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>                                    
                                    @endforeach
                                </select>                               
                                @error('countryId')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name"
                                class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" wire:model.defer='name'>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='closeModal()'>Close</button>
                    @if ($editMode)
                        <button type="button" class="btn btn-primary" wire:click='updateState()'>Update
                            state</button>
                    @else
                        <button type="button" class="btn btn-primary" wire:click='storeState()'>Store
                            state</button>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
